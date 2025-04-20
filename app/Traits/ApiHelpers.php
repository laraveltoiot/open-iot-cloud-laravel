<?php declare(strict_types=1);

namespace App\Traits;

trait ApiHelpers
{
    protected function isApiRequest(): bool
    {
        return request()->wantsJson() || request()->is('api/*');
    }

    protected function checkPermission(string $ability): void
    {
        if ($this->isApiRequest() && auth('sanctum')->check()) {
            if (! auth('sanctum')->user()?->tokenCan($ability)) {
                abort(response()->json([
                    'message' => "Unauthorized - Missing permission: $ability",
                ], 403));
            }
        }
    }
}
