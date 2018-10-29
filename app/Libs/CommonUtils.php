<?php
/**
 * Created by PhpStorm.
 * User: Wenson
 * Date: 2018/2/28
 * Time: 9:16
 */

namespace App\Libs;

use App\Models\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonUtils {

    public $siderBarData = null;//侧边栏对象

    /**
     * 构造函数
     */
    public function __construct(Request $request) {
        $this->init($request);
    }

    /**
     * 初始化函数
     */
    private function init(Request $request) {
        $this->getSiderBarData($request);
    }

    /**
     * 获取侧边栏数据
     */
    private function getSiderBarData() {
        $permissions = new Permissions();
        $this->siderBarData = $permissions->getTreeData(['guard_name' => 'web', 'is_menu' => 1]);
    }
}