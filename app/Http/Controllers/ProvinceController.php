<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinceResource;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $provinces = Province::orderBy('name')->get();

        if ($provinces->isEmpty()) {
            return response()->json([
                'errors' => ['message' => 'Provinces not found'],
            ], 404);
        }

        return response()->json([
            'data' => ProvinceResource::collection($provinces),
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idOrSlug)
    {
        $province = Province::with('cities')
            ->where('id', $idOrSlug)
            ->orWhere('slug', $idOrSlug)
            ->first();

        if (!$province) {
            return response()->json([
                'errors' => ['message' => 'Province not found'],
            ], 404);
        }

        return response()->json([
            'data' => new ProvinceResource($province),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Province $province)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        //
    }
}
