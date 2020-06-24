<?php

declare(strict_types=1);

namespace Andali\Companies\Http\Middleware;

class VerifyUserHasOwnership
{
    public function handle($request, $next)
    {
        $user = $request->user();

        abort_unless($user && $user->ownsCurrentCompany(), 403);

        return $next($request);
    }
}
