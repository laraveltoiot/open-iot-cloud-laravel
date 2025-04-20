<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NodeResource;
use App\Models\Node;
use App\Traits\ApiHelpers;
use Illuminate\Http\Request;
use Str;

final class NodeController extends Controller
{
    use ApiHelpers;
    public function index()
    {
        $this->checkPermission('read');

        $nodes = Node::with('users')->get();

        return $this->isApiRequest()
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

        $this->checkPermission('update');

        if ($this->isApiRequest()) {
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
        $this->checkPermission('create');

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

        $this->checkPermission('read');

        return $this->isApiRequest()
            ? new NodeResource($node)
            : view('nodes.show', compact('node'));
    }

    public function destroy($id)
    {
        $node = Node::where('id', $id)->orWhere('node_uuid', $id)->firstOrFail();

        $this->checkPermission('delete');

        $node->delete();

        return response()->json([
            'message' => 'The node was successfully deleted.',
        ]);
    }
}
