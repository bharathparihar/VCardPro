<?php

namespace App\Http\Middleware;

use App\Models\CustomDomain;
use App\Models\Vcard;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomDomainCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
         // remove fbclid
         if ($request->has('fbclid')) {
            $query = $request->query();
            unset($query['fbclid']); // remove fbclid
            $url = $request->url(); // base URL without query
            if (!empty($query)) {
                $url .= '?' . http_build_query($query); // append other query params if any
            }
            return redirect()->to($url); // redirect without fbclid
        }

        $domain = request()->getHost();
        $appUrl = config('app.url');
        $appDomain = config('app.domain');
        
        // Handle cases where APP_URL might not have a scheme in env
        $parsedAppHost = parse_url($appUrl, PHP_URL_HOST) ?: $appUrl;
        
        $requestAlias = request()->getRequestUri();
        $requestAlias = trim($requestAlias, '/');

        // If the current domain matches our configured app domain or host, it's NOT a custom domain
        if ($domain == $appDomain || $domain == $parsedAppHost || $domain == '127.0.0.1' || $domain == 'localhost' || str_ends_with($domain, '.onrender.com')) {
            return $next($request);
        }
        
        // If we got here, it's potentially a custom domain
        $customDomain = CustomDomain::where('domain', $domain)->where('is_active', 1)->first();
            if (!$customDomain) {
                abort(404);
            }

            $user = $customDomain->user;

            if (empty($requestAlias)) {
                $firstVcard = Vcard::where('tenant_id', $user->tenant_id)->firstOrFail();
                $vcardAlias = $firstVcard->url_alias;

                return redirect(url("/$vcardAlias"));
            }

            $aliasExistsForSameUser = Vcard::where('url_alias', $requestAlias)->where('tenant_id', $user->tenant_id)->exists();
            if (!$aliasExistsForSameUser) {
                abort(404);
            }

        return $next($request);
    }
}
