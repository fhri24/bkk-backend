<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * API Example Controller
 *
 * This is a generic REST API controller example for junior developers to learn from.
 * It demonstrates best practices for building professional APIs with Laravel.
 *
 * Copy this controller and modify it for your specific resource needs.
 *
 * @package App\Http\Controllers\Api
 * @author <fauzy>
 * @version 1.0.0
 */
class ApiExampleController extends Controller
{
    /**
     * Retrieve a paginated list of items
     *
     * Returns all items from the database with support for pagination.
     * Items are ordered by most recent first (DESC by created_at).
     *
     * @param Request $request The HTTP request object
     * @return JsonResponse Returns JSON with items and pagination details
     *
     * @example
     * GET /api/items?per_page=10&page=1
     *
     * @response 200 {
     *   "success": true,
     *   "data": [...],
     *   "pagination": {
     *     "total": 25,
     *     "per_page": 10,
     *     "current_page": 1,
     *     "last_page": 3
     *   }
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);

        $items = User::orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $items->items(),
            'pagination' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
            ],
        ]);
    }

    /**
     * Create a new item
     *
     * Creates and stores a new item in the database. Validates required fields
     * and returns the created item with its assigned ID.
     *
     * @param Request $request The HTTP request containing item data
     * @return JsonResponse Returns JSON with created item (201 Created) or validation errors (422)
     *
     * @throws \Illuminate\Validation\ValidationException If validation fails
     *
     * @example
     * POST /api/items
     * {
     *   "name": "Item Name",
     *   "email": "item@example.com",
     *   "password": "password123",
     *   "password_confirmation": "password123"
     * }
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Item created successfully",
     *   "data": {
     *     "id": 5,
     *     "name": "Item Name",
     *     "email": "item@example.com",
     *     "created_at": "2026-02-05T10:30:00Z",
     *     "updated_at": "2026-02-05T10:30:00Z"
     *   }
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Hash password before storing
        $validated['password'] = bcrypt($validated['password']);

        $item = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item created successfully',
            'data' => $item,
        ], 201);
    }

    /**
     * Retrieve a single item by ID
     *
     * Fetches a specific item from the database by its ID.
     * Uses Laravel's implicit route model binding for automatic resolution.
     *
     * @param User $item The item model instance (auto-resolved by Laravel)
     * @return JsonResponse Returns JSON with the requested item
     *
     * @example
     * GET /api/items/1
     *
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "id": 1,
     *     "name": "Item Name",
     *     "email": "item@example.com",
     *     "created_at": "2026-02-05T10:00:00Z",
     *     "updated_at": "2026-02-05T10:00:00Z"
     *   }
     * }
     *
     * @response 404 {
     *   "message": "No query results found for model [App\\Models\\User]."
     * }
     */
    public function show(User $item): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $item,
        ]);
    }

    /**
     * Update an existing item
     *
     * Updates one or more fields of an existing item. All fields are optional
     * (use partial updates). Validates data and ensures uniqueness constraints.
     *
     * @param Request $request The HTTP request containing update data
     * @param User $item The item model instance to update (auto-resolved by Laravel)
     * @return JsonResponse Returns JSON with updated item
     *
     * @throws \Illuminate\Validation\ValidationException If validation fails
     *
     * @example
     * PUT /api/items/1
     * {
     *   "name": "Updated Item Name",
     *   "email": "updated@example.com"
     * }
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Item updated successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Updated Item Name",
     *     "email": "updated@example.com",
     *     "created_at": "2026-02-05T10:00:00Z",
     *     "updated_at": "2026-02-05T10:35:00Z"
     *   }
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     */
    public function update(Request $request, User $item): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $item->id,
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item updated successfully',
            'data' => $item,
        ]);
    }

    /**
     * Delete an item
     *
     * Permanently deletes an item from the database. This action cannot be undone.
     * Returns success response if deletion was successful.
     *
     * @param User $item The item model instance to delete (auto-resolved by Laravel)
     * @return JsonResponse Returns JSON with success message
     *
     * @example
     * DELETE /api/items/1
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Item deleted successfully"
     * }
     *
     * @response 404 {
     *   "message": "No query results found for model [App\\Models\\User]."
     * }
     */
    public function destroy(User $item): JsonResponse
    {
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully',
        ]);
    }

    /**
     * Search items by query
     *
     * Searches items by name or email using LIKE operator. Performs a full-text
     * search across multiple fields and limits results to prevent large result sets.
     *
     * @param string $query The search query string (minimum 2 characters)
     * @return JsonResponse Returns JSON with matching items and count
     *
     * @example
     * GET /api/items/search/john
     *
     * @response 200 {
     *   "success": true,
     *   "query": "john",
     *   "results": [
     *     {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com",
     *       "created_at": "2026-02-05T10:00:00Z",
     *       "updated_at": "2026-02-05T10:00:00Z"
     *     }
     *   ],
     *   "count": 1
     * }
     *
     * @response 422 {
     *   "success": false,
     *   "message": "Search query must be at least 2 characters"
     * }
     */
    public function search(string $query): JsonResponse
    {
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Search query must be at least 2 characters',
            ], 422);
        }

        $items = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'query' => $query,
            'results' => $items,
            'count' => $items->count(),
        ]);
    }
}
