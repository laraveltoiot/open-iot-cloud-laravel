<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NodeResource;
use App\Models\Node;

final class NodeController extends Controller
{
    public function index()
    {
        if (request()->is('api/*') && auth('sanctum')->check()) {
            if (! auth('sanctum')->user()?->tokenCan('read')) {
                return response()->json([
                    'message' => 'Unauthorized - Missing permission: read',
                ], 403);
            }
        }

        $nodes = Node::with('users')->get();

        return (request()->wantsJson() || request()->is('api/*'))
            ? NodeResource::collection($nodes)
            : view('nodes.index', compact('nodes'));
    }
}
