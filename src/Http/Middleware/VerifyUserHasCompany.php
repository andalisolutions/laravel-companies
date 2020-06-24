<?php

declare(strict_types=1);

namespace Andali\Companies\Http\Middleware;

class VerifyUserHasCompany
{
    public function handle($request, $next)
    {
        $user = $request->user();

        if ($user && ! $user->hasCompanies()) {
            return back();
        }

        return $next($request);
    }
}
