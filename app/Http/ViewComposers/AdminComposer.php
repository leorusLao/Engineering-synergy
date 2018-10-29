<?php
/**
 * Created by PhpStorm.
 * User: Wenson
 * Date: 2018/2/28
 * Time: 9:14
 */
namespace App\Http\ViewComposers;

use App\Libs\CommonUtils;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminComposer {

    private $data = null;//CommonUtils对象

    public function __construct(Request $request) {
        $this->data = new CommonUtils($request);//新建一个CommonUtils对象
    }

    public function compose(View $view) {
        $view->with([
            'siderBarData' => $this->data->siderBarData,
        ]);//填充数据
    }
}