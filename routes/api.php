<?php declare(strict_types=1);

use App\Http\Controllers\NodeController;
use App\Models\Node;
use App\Models\UserNodeMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        if (! $request->user()?->tokenCan('read')) {
            return response()->json(['message' => 'Unauthorized - Missing permission: read'], 403);
        }

        return $request->user();
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/nodes', [NodeController::class, 'index']);
    Route::post('/create-node', [NodeController::class, 'store']);
    Route::get('/nodes/{id}', [NodeController::class, 'show']);
    Route::get('/nodes/{id}', [NodeController::class, 'edit']);
    Route::patch('/nodes/{id}', [NodeController::class, 'update']);
    Route::delete('/nodes/{id}', [NodeController::class, 'destroy']);
});

Route::post('/pair-device', function (Request $request) {
    $validated = $request->validate([
        'node_uuid' => 'required|uuid',
        'secret_key' => 'required|uuid',
    ]);

    $node = Node::where('node_uuid', $validated['node_uuid'])->first();
    if (! $node) {
        return response()->json(['message' => 'Node not found.'], 404);
    }

    $mapping = UserNodeMapping::where('node_id', $node->id)
        ->where('secret_key', $validated['secret_key'])
        ->first();

    if (! $mapping) {
        return response()->json(['message' => 'Invalid pairing credentials.'], 401);
    }

    if ($mapping->status === 'confirmed') {
        return response()->json(['message' => 'Device already paired.'], 200);
    }

    $mapping->status = 'confirmed';
    $mapping->save();

    return response()->json([
        'status' => 'paired',
        'node_id' => $node->id,
        'mqtt' => [
            'host' => config('MQTT_HIVEMQ_HOST'),
            'port' => config('MQTT_HIVEMQ_PORT'),
            'topic' => "node/{$node->node_uuid}/data",
        ],
    ]);
});

// Route::get('/debug-token', function (Request $request) {
//    return [
//        'user' => $request->user(),
//        'accessToken' => $request->user()?->currentAccessToken(),
//        'abilities' => $request->user()?->currentAccessToken()?->abilities,
//    ];
// })->middleware('auth:sanctum');
