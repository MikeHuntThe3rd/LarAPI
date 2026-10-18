<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DB::table('cities')
            ->leftJoin('counties', 'cities.id_county', '=', 'counties.id')
            ->select('cities.*', 'counties.name as county_name');

        // Search by city name or zip code
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('cities.city', 'like', "%{$search}%")
                  ->orWhere('cities.zip_code', 'like', "%{$search}%");
            });
        }

        if ($countyId = $request->input('county_id')) {
            $query->where('cities.id_county', $countyId);
        }

        $cities = $query->orderBy('cities.city')
            ->paginate(15)
            ->withQueryString();

        $counties = DB::table('counties')->orderBy('name')->get();

        return response()->json(compact('cities', 'counties'));
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'operation' => 'Add',
            'method'    => 'POST',
            'data'      => null,
            'action'    => route('cities.store'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $parsed = $request->validate([
            "zip_code"   => ["required", "int"],
            "city"       => ["required", "string"],
            "population" => ["required", "int"],
            "id_county"  => ["required", "int"],
        ]);

        $id = DB::table('cities')->insertGetId([
            "zip_code"   => $parsed["zip_code"],
            "city"       => $parsed["city"],
            "population" => $parsed["population"],
            "id_county"  => $parsed["id_county"],
        ]);

        return response()->json([
            'message' => 'City created.',
            'data'    => DB::table('cities')->find($id),
        ], 201);
    }

    public function edit(int $id): JsonResponse
    {
        return response()->json([
            'operation' => 'Modify',
            'data'      => City::findOrFail($id),
            'method'    => 'PATCH',
            'action'    => route('cities.update', $id),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $parsed = $request->validate([
            "zip_code"   => ["required", "int"],
            "city"       => ["required", "string"],
            "population" => ["required", "int"],
            "id_county"  => ["required", "int"],
        ]);

        $city = City::findOrFail($id);
        $city->update([
            "zip_code"   => $parsed["zip_code"],
            "city"       => $parsed["city"],
            "population" => $parsed["population"],
            "id_county"  => $parsed["id_county"],
        ]);

        return response()->json([
            'message' => 'City updated.',
            'data'    => $city->fresh(),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        City::find($id)?->delete();

        return response()->json(['message' => 'City deleted.']);
    }
}