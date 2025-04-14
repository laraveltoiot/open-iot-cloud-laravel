<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NodeResource;
use App\Models\Node;
use Illuminate\Http\Request;
use Str;

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

    public function create()
    {
        return view('nodes.create');
    }

    public function store(Request $request)
    {
        if ($request->is('api/*') && auth('sanctum')->check()) {
            if (! auth('sanctum')->user()?->tokenCan('create')) {
                return response()->json([
                    'message' => 'Unauthorized - Missing permission: write',
                ], 403);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'fw_version' => 'nullable|string|max:255',
        ]);

        $node = Node::create([
            'node_uuid' => Str::uuid(),
            'name' => $validated['name'],
            'type' => $validated['type'] ?? null,
            'fw_version' => $validated['fw_version'] ?? null,
        ]);

        return response()->json([
            'message' => 'Node created successfully.',
            'node' => new NodeResource($node),
        ], 201);
    }
}
