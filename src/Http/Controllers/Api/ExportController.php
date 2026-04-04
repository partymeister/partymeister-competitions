<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Partymeister\Competitions\Http\Resources\EntryResource;
use Partymeister\Competitions\Models\Entry;

class ExportController extends Controller
{
    public function entries(Request $request): JsonResponse
    {
        $query = Entry::with(['competition', 'visitor', 'options', 'media'])
            ->whereHas('competition')
            ->orderBy('competition_id')
            ->orderBy('sort_position');

        if ($request->has('competition_id')) {
            $query->where('competition_id', $request->input('competition_id'));
        }

        if ($request->has('competition')) {
            $query->whereHas('competition', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('competition') . '%');
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $entries = $query->get();

        return response()->json([
            'data' => EntryResource::collection($entries),
            'meta' => [
                'total' => $entries->count(),
            ],
        ]);
    }
}
