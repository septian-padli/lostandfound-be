<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreItemRequest;
use Illuminate\Support\Facades\Storage;
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
}
