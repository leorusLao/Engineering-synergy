<?php
/**
 * Created by PhpStorm.
 * User: wenson
 * Date: 2018/3/7
 * Time: 13:36
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProjectCircle extends Model {
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'project_circle';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'trigger_uid',
        'iid',
        'company_id',
        'content',
        'text',
        'img',
        'source',
        'type',
        'style',
        'position',
        'seeing',
        'status',
    ];

    // 创建朋友圈消息
    public static function createMsg(array $data)
    {
        $uid = $data['uid'];
        if(Redis::exists($uid.'_token')) {
            $data['token'] = Redis::get($uid.'_token');
        }else {
            $token = User::where('uid', $uid)->value('api_token');
            Redis::set($uid.'_token', $token);
            $data['token'] = $token;
        }

        $url = config('app.HTTPS').$_SERVER['HTTP_HOST'].'/api/project-circle/create';
        Log::debug($url);
        Log::debug($data);

        $res = User::httpsRequest($url, $data);

        return $res;
    }
}
