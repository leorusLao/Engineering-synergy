<?php

namespace App\Http\Controllers;

class CheckFile extends Controller
{
    private $image;
    private $imgsuffix;
    private $suffix;
    private $img_array;
    private $result;
    private $imagesize;
    private $name_isok;

    //解开base64编码
    public function __construct($image)
    {
        preg_match('/^data:\s*(image\/(\w+));base64,/', $image, $this->result);
        $this->img_array = array('image/jpeg','image/jpg','image/png','image/gif');
        $this->image = $image;
        if(!empty($this->result[1])){
            $this->imgsuffix = $this->result[1];
        }else{ 
            $this->imgsuffix = '';
        }
    }

    //判断图片格式是否符合要求
    public function check_imgformat_base64()
    { 
        $bool = false;
        foreach ($this->img_array as $key => $value) {
            if($this->imgsuffix == $value){
                $this->suffix = str_replace('image/','',$value);
                $bool = true;
            }
        }
        return $bool;
    } 

    //判断图片大小是否符合要求
    public function check_size($size='4M')
    {
        $this->imagesize = strlen($this->image);
        $size = $this->change_mtob($size);
        if($this->imagesize >= $size){ 
            $bool = false;
        }else{ 
            $bool = true;
        }
        return $bool;
    }

    //判断当前内存
    public function check_memory()
    { 
        $limit_memory = ini_get('memory_limit');
        $memory = memory_get_usage();
        if($limit_memory > $memory + $this->imagesize){ 
            $bool = false;
        }else{ 
            $bool = true;
        }
        return $bool;
    }

    //上传base64文件
    public function upload_file_base64($addr = '/uploads/common/')
    { 
        $cont_img = str_replace($this->result[0],'',$this->image);
        $cont_img = base64_decode($cont_img);
        $path = public_path().$addr;
        $name = date('Ymd').time().rand(001,999);
        $suffix = $this->suffix;
        //文件名已经存在
        $this->check_name($path,$name,$suffix);
        $add = $addr.$this->name_isok;
        //文件夹不存在
        if(!is_dir(public_path().$addr)){ 
        	mkdir(public_path().$addr,0777);
        }
        $file_size = file_put_contents(public_path().$add, $cont_img);
        if($file_size){ 
            $imgurl = $add;
        }else{ 
            $imgurl = null;
        }
        return $imgurl;
    }

    //文件名存在重新命名
    public function check_name($path,$name,$suffix)
    { 
    	$path_name = $path.$name.$suffix;
    	if(file_exists($path_name)){
    		$new_name = date('Ymd').time().rand(001,999).'.'.$this->suffix;
    		$this->check_name($path,$new_name,$suffix);
    		//return false;
    	}else{ 
    		$this->name_isok = $name.'.'.$suffix;
    	}
    }


    //兆(M)转成字节(b)
    public function change_mtob($m='1M')
    {
        $m = trim($m);
        $num = '';
        $ary = str_split($m);
        for ($i=0; $i < count($ary); $i++) { 
            if(is_numeric($ary[$i])){ 
                $num .= $ary[$i];
            }
        }
        $b = $num * 1024 * 1024;
        return $b;
    }



}
