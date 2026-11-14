<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->tokenCan('assets:read')) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $validated = $request->validate([
            'ids' => ['required', 'array', 'list', 'min:1', 'max:100'],
            'ids.*' => ['required', 'integer', 'min:1', 'distinct'],
        ]);

        $ids = array_map('intval', $validated['ids']);

        $fields = [
            'id', 'name', 'manufacturer', 'model', 'description', 'specifications', 'is_public',
        ];

        $assets = $request->user()->assets()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        $data = [];
        $unavailableIds = [];

        foreach ($ids as $id) {
            $asset = $assets->get($id);

            if (!$asset) {
                $unavailableIds[] = $id;
                continue;
            }

            $data[] = $asset->only($fields);
        }

        return response()->json([
            'data' => $data,
            'unavailable_ids' => $unavailableIds,
        ]);
    }
}