<?php
/**
 * Created by PhpStorm.
 * User: wenson
 * Date: 2018/2/6
 * Time: 10:54
 */

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use App\Models\Permissions;
use Closure;
use Illuminate\Support\Facades\Log;
use Session;

class Permission
{
    /**
     * @var array
     */
    private $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $permission = $request->route()->getName();
        $permissionArr = Permissions::permission();
        if (in_array($permission, array_merge($this->except, $permissionArr, config('app.except')))) {
            return $next($request);
        }
        return $next($request);
        Log::debug($permission);
        throw  UnauthorizedException::unauthorized($request);
    }
}
