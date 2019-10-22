<?php

namespace App\Http\Middleware;

use Closure;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $IlluminateResponse = 'Illuminate\Http\Response';
        $SymfonyResopnse = 'Symfony\Component\HttpFoundation\Response';
        $headers = [
            'Cache-Control'=>'nocache, no-store, max-age=0, must-revalidate',
            'Pragma'=>'no-cache',
            'Expires'=>'Sun, 02 Jan 1990 00:00:00 GMT',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, PATCH, DELETE',
            'Access-Control-Allow-Headers' => 'Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Authorization , Access-Control-Request-Headers',
        ];

        if($response instanceof $IlluminateResponse) {
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
            return $response;
        }

        if($response instanceof $SymfonyResopnse) {
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
            return $response;
        }

        return $response;
            
    }
}

