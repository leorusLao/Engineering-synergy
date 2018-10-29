<?php
/**
 * Created by PhpStorm.
 * User: wenson
 * Date: 2018/3/9
 * Time: 11:08
 */
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model {
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'comments';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'pc_id',
        'from_uid',
        'from_name',
        'text',
        'status',
        'type',
    ];
}