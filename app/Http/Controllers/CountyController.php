<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $populationSub = DB::table('cities')
            ->select('id_county', DB::raw('SUM(population) as total_population'))
            ->groupBy('id_county');

        $query = DB::table('counties')
            ->leftJoinSub($populationSub, 'city_sums', function ($join) {
                $join->on('counties.id', '=', 'city_sums.id_county');
            })
            ->select('counties.*', DB::raw('COALESCE(city_sums.total_population, 0) as total_population'));

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('counties.name', 'like', "%{$search}%")
                  ->orWhere('counties.badge', 'like', "%{$search}%");
            });
        }

        $counties = $query->orderBy('counties.name')->get();

        return response()->json(compact('counties'));
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'operation' => 'Add',
            'method'    => 'POST',
            'data'      => null,
            'action'    => route('counties.store'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $parsed = $request->validate([
            "name"  => ["required", "string"],
            "badge" => ["required", "string"],
        ]);

        $id = DB::table('counties')->insertGetId([
            "name"  => $parsed["name"],
            "badge" => $parsed["badge"],
        ]);

        return response()->json([
            'message' => 'County created.',
            'data'    => DB::table('counties')->find($id),
        ], 201);
    }

    public function edit(int $id): JsonResponse
    {
        return response()->json([
            'operation' => 'Modify',
            'data'      => County::findOrFail($id),
            'method'    => 'PATCH',
            'action'    => route('counties.update', $id),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $parsed = $request->validate([
            "name"  => ["required", "string"],
            "badge" => ["required", "string"],
        ]);

        $county = County::findOrFail($id);
        $county->update([
            "name"  => $parsed["name"],
            "badge" => $parsed["badge"],
        ]);

        return response()->json([
            'message' => 'County updated.',
            'data'    => $county->fresh(),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        County::find($id)?->delete();

        return response()->json(['message' => 'County deleted.']);
    }
}