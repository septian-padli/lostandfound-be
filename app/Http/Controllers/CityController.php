<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Http\Resources\CityResource;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::orderBy('name')->get();

        if ($cities->isEmpty()) {
            return response()->json(['errors' => ['message' => 'Cities not found']], 404);
        }

        return CityResource::collection($cities);
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
    public function store(StoreCityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idCityOrSlug)
    {
        $city = City::where('id', $idCityOrSlug)
            ->orWhere('slug', $idCityOrSlug)
            ->first();

        if (! $city) {
            return response()->json(['errors' => ['message' => 'City not found']], 404);
        }

        return new CityResource($city);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityRequest $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        //
    }
}
