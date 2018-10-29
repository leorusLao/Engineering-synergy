<?php
/**
 * Created by PhpStorm.
 * User: webson
 * Date: 2018/2/6
 * Time: 10:56
 */

namespace App\Exceptions;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    /**
     * @param Request $request
     * @return UnauthorizedException
     */
    public static function unauthorized(Request $request): self
    {
        return new static(403, trans('mowork.unauthorized_action'), null, []);
    }
}