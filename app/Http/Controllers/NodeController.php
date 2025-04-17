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

    public function edit($id)
    {
        $node = Node::with('users')->where('id', $id)->orWhere('node_uuid', $id)->firstOrFail();
        if (request()->is('api/*') && auth('sanctum')->check()) {
            if (! auth('sanctum')->user()?->tokenCan('update')) {
                return response()->json([
                    'message' => 'Unauthorized - Missing permission: update',
                ], 403);
            }
        }
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'node' => new NodeResource($node),
            ]);
        }

        return view('nodes.edit', compact('node'));
    }

    public function update(Request $request, $id)
    {
        $node = Node::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:50',
        ]);
        $node->update($validated);

        return response()->json([
            'message' => 'Nodul a fost actualizat cu succes.',
            'node' => $node,
        ]);
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

    public function show($id)
    {
        $node = Node::with('users')->where('id', $id)->orWhere('node_uuid', $id)->firstOrFail();
        if (request()->is('api/*') && auth('sanctum')->check()) {
            if (! auth('sanctum')->user()?->tokenCan('read')) {
                return response()->json([
                    'message' => 'Unauthorized - Missing permission: read',
                ], 403);
            }
        }

        return (request()->wantsJson() || request()->is('api/*'))
            ? new NodeResource($node)
            : view('nodes.show', compact('node'));
    }
}
