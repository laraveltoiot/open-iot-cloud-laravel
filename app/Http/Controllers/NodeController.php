<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NodeResource;
use App\Models\Node;

final class NodeController extends Controller
{
    public function index()
    {
        $nodes = Node::with('users')->get();

        return (request()->wantsJson() || request()->is('api/*'))
            ? NodeResource::collection($nodes)
            : view('nodes.index', compact('nodes'));
    }
}
