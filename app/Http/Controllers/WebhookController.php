<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\WebhookMicroservice;
use App\Traits\ApiHelpers;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class WebhookController extends Controller
{
    use ApiHelpers;

    public function __construct(
        protected WebhookMicroservice $webhookService
    ) {}

    /**
     * @throws ConnectionException
     */
    public function index()
    {
        $this->checkPermission('read');

        // Get data from microservice
        $response = $this->webhookService->getAll();

        if ($response->failed()) {
            // Return an error
            if ($this->isApiRequest()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to fetch webhooks from microservice',
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to fetch webhooks');
        }

        $data = $response->json();

        if ($this->isApiRequest()) {
            return response()->json($data, 200);
        }

        return view('webhooks.index', [
            'webhooks' => $data['webhooks'] ?? [],
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        if ($this->isApiRequest()) {
            return response()->json([
                'message' => 'This endpoint is not available via API',
            ], 405);
        }

        return view('webhooks.create');
    }

    /**
     * @throws ConnectionException
     */
    public function store(Request $request)
    {
        $this->checkPermission('create');
        $validated = $request->validate([
            'name' => 'required|string',
            'url' => 'required|url',
            'method' => 'required|in:POST,GET,PUT,DELETE',
            'topic_filter' => 'required|string',
            'enabled' => 'boolean',
            'headers' => 'array',
            'timeout' => 'integer',
            'retry_count' => 'integer',
            'retry_delay' => 'integer',
        ]);

        $response = $this->webhookService->create($validated);

        if ($response->failed()) {
            if ($this->isApiRequest()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $response->json()['message'] ?? 'Error creating webhook',
                ], $response->status());
            }

            // Non-API: redirect with error
            return redirect()->back()->with('error', 'Error creating webhook');
        }

        $data = $response->json();

        if ($this->isApiRequest()) {
            return response()->json($data, $response->status());
        }

        return redirect()->route('webhooks.index')
            ->with('success', 'Webhook created successfully');
    }

    public function show($id)
    {
        $this->checkPermission('read');

        $response = $this->webhookService->find($id);

        if ($response->failed()) {
            if ($this->isApiRequest()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error fetching webhook',
                ], $response->status());
            }

            return redirect()->back()->with('error', 'Error fetching webhook');
        }

        $data = $response->json();

        if ($this->isApiRequest()) {
            return response()->json($data, 200);
        }

        // For a browser request, you could show a detail page
        return view('webhooks.show', [
            'webhook' => $data['webhook'] ?? null,
        ]);
    }

    /**
     * @throws ConnectionException
     */
    public function edit($id)
    {
        $this->checkPermission('update');

        if ($this->isApiRequest()) {
            return response()->json([
                'message' => 'Not available via API',
            ], 405);
        }

        $response = $this->webhookService->find($id);
        if ($response->failed()) {
            return redirect()->back()->with('error', 'Error fetching webhook');
        }

        $data = $response->json();

        return view('webhooks.edit', [
            'webhook' => $data['webhook'] ?? null,
        ]);
    }

    /**
     * @throws ConnectionException
     */
    public function update(Request $request, $id)
    {
        $this->checkPermission('update');

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'url' => 'sometimes|url',
            'method' => 'sometimes|in:POST,GET,PUT,DELETE',
            'topic_filter' => 'sometimes|string',
            'enabled' => 'boolean',
            'headers' => 'array',
            'timeout' => 'integer',
            'retry_count' => 'integer',
            'retry_delay' => 'integer',
        ]);

        $response = $this->webhookService->update($id, $validated);

        if ($response->failed()) {
            if ($this->isApiRequest()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error updating webhook',
                ], $response->status());
            }

            return redirect()->back()->with('error', 'Error updating webhook');
        }

        $data = $response->json();

        if ($this->isApiRequest()) {
            return response()->json($data, $response->status());
        }

        return redirect()->route('webhooks.index')
            ->with('success', 'Webhook updated successfully');
    }

    /**
     * DELETE /webhooks/{id}
     *
     * @throws ConnectionException
     */
    public function destroy($id)
    {
        $this->checkPermission('delete');

        $response = $this->webhookService->delete($id);

        if ($response->failed()) {
            if ($this->isApiRequest()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error deleting webhook',
                ], $response->status());
            }

            return redirect()->back()->with('error', 'Error deleting webhook');
        }

        $data = $response->json();

        if ($this->isApiRequest()) {
            return response()->json($data, $response->status());
        }

        return redirect()->route('webhooks.index')
            ->with('success', 'Webhook deleted successfully');
    }
}
