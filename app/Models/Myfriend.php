<?php
namespace App\Models;
use Eloquent;
use DB;

class Myfriend extends Eloquent
{
     
    /**
     * The attributes that are mass assignable.
     * 
     * a user may belong to mutiple companys
     * 
     * @var array
     */
    protected $primaryKey = 'id';
    protected $table = 'my_friend';

    protected $fillable = array('my_uid', 'my_name', 'fri_uid', 'fri_name', 'is_active', 'fri_avatar');
    protected $guarded = array('id');
    public $timestamps = true;
    
    public static function insertMyfriend($ary)
    { 
        $affect = self::create($ary);
        return $affect;
    }

    public static function infoMyfriend($where,$field='id')
    {
        $result = self::select($field)->where($where)->first();
        return $result;
    }

    //判断双方是否是朋友
    public static function isFriend($my_uid,$fri_uid,$field='id')
    { 
        $sql = "select id from my_friend where (my_uid = ? and fri_uid = ?) or (fri_uid = ? and my_uid = ?) and is_active = 1 and relation = 1";
        $result = DB::select($sql,[$my_uid,$fri_uid,$my_uid,$fri_uid]);
        return $result;
    }




}
