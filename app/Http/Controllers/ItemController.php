<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Image;
use Pest\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreItemRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SearchItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 20);
        $cursor = $request->query('cursor');

        $query = Item::query()
            ->with(['category', 'city', 'province', 'images', 'user'])
            ->orderBy('created_at', 'desc');

        if ($cursor) {
            $query->where('id', '<', $cursor);
        }

        $items = $query->limit($limit + 1)->get();

        $nextCursor = null;
        if ($items->count() > $limit) {
            $nextCursor = $items->pop()->id;
        }

        if ($items->isEmpty()) {
            return response()->json(['errors' => ['message' => 'Items not found']], 404);
        }

        return response()->json([
            'data' => ItemResource::collection($items),
            'nextCursor' => $nextCursor,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();

        $item = Item::create([
            'id_user' => $validated['userId'],
            'id_category' => $validated['categoryId'],
            'id_city' => $validated['cityId'],
            'id_province' => $validated['provinceId'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'address' => $validated['address'],
            'found_at' => now(), // default saat ini, atau bisa dari request jika ada
        ]);

        // Handle images (optional: simpan di tabel images)
        // Contoh: $item->images()->createMany([...]);

        return response()->json([
            'data' => new ItemResource($item->load(['category', 'city', 'province', 'images', 'user'])),
        ], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::with(['category', 'city', 'province', 'images', 'user'])
            ->find($id);

        if (! $item) {
            return response()->json(['errors' => ['message' => 'Item not found']], 404);
        }

        return response()->json(['data' => new ItemResource($item)], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        $item = Item::find($id);

        if (! $item) {
            return response()->json(['errors' => ['message' => 'Item not found']], 404);
        }

        $item->fill($validated);
        $item->updated_at = now(); // otomatis, jika pakai timestamps
        $item->save();

        return response()->json([
            'data' => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'address' => $item->address,
            ],
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }

    public function markAsFound(string $idItem)
    {
        $item = Item::find($idItem);

        if (! $item) {
            return response()->json(['errors' => ['message' => 'Item not found']], 404);
        }

        // Opsional: pastikan hanya pemilik item yang bisa update
        if ($item->id_user !== Auth::id()) {
            return response()->json(['errors' => ['message' => 'Unauthorized']], 403);
        }

        $item->is_found = true;
        $item->found_at = now();
        $item->save();

        return response()->json(['data' => true]);
    }

    public function search(SearchItemRequest $request)
    {
        $validated = $request->validated();

        $query = Item::query()
            ->with([
                'category:id,name,slug',
                'city:id,name,slug',
                'province:id,name,slug',
                'images:id,id_item,url',
                'user:id,name,username,photoprofile'
            ])
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('is_found', false)
                    ->orWhere(function ($q2) {
                        $q2->where('is_found', true)
                            ->where('found_at', '>=', now()->subWeek());
                    });
            });

        // ➤ Cek apakah semua parameter kosong
        $hasFilter = !empty($validated['q']) || !empty($validated['category']) || !empty($validated['city']) || !empty($validated['province']);

        if (!$hasFilter) {
            // Filter default by user's city
            $query->where('id_city', $request->user()->id_city);
        }

        // ➤ Jika ada query
        if (!empty($validated['q'])) {
            $keywords = explode(' ', $validated['q']);
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->where(function ($sub) use ($keyword) {
                        $sub->where('name', 'like', "%$keyword%")
                            ->orWhere('slug', 'like', "%$keyword%")
                            ->orWhere('id', $keyword); // id harus exact
                    });
                }
            });
        }

        if (!empty($validated['category'])) {
            $query->whereHas(
                'category',
                fn($q) =>
                $q->where('id', $validated['category'])
                    ->orWhere('slug', $validated['category'])
            );
        }

        if (!empty($validated['city'])) {
            $query->whereHas(
                'city',
                fn($q) =>
                $q->where('id', $validated['city'])
                    ->orWhere('slug', $validated['city'])
            );
        }

        if (!empty($validated['province'])) {
            $query->whereHas(
                'province',
                fn($q) =>
                $q->where('id', $validated['province'])
                    ->orWhere('slug', $validated['province'])
            );
        }

        // ➤ Infinite scroll
        if (!empty($validated['cursor'])) {
            $query->where('id', '<', $validated['cursor']);
        }

        $limit = $validated['limit'] ?? 20;
        $items = $query->orderBy('created_at', 'desc')
            ->limit($limit + 1)
            ->get();

        $nextCursor = null;
        if ($items->count() > $limit) {
            $nextCursor = $items->pop()->id;
        }

        return response()->json([
            'data' => ItemResource::collection($items),
            'nextCursor' => $nextCursor,
        ]);
    }
}
