<?php

namespace App\Http\Middleware;

use App\Model\DeliveryMan;
use Closure;
use function App\CPU\translate;

class DeliveryManAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = explode(' ', $request->header('authorization'));
        if (count($token) > 1 && strlen($token[1]) > 30) {
            $d_man = DeliveryMan::where(['auth_token' => $token['1']])->first();
            if (isset($d_man)) {
                $request['delivery_man'] = $d_man;
                return $next($request);
            }
        }

        return response()->json([
            'auth-001' => translate('Your existing session token does not authorize you any more')
        ], 401);
    }
}
