<?php

namespace App\Http\Controllers;

use App\Services\SemanticSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SemanticSearchController extends Controller
{
    public function __construct(
        protected SemanticSearchService $searchService
    ) {}

    /**
     * Endpoint API Pencarian Semantik AI
     */
    public function search(Request $request): JsonResponse
    {
        $query = (string) $request->input('q', '');
        $results = $this->searchService->search($query);

        return response()->json([
            'status' => 'success',
            'query' => $query,
            'count' => count($results),
            'results' => $results,
        ]);
    }
}
