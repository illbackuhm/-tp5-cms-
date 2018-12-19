<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Base
{
    /**
     * @author ty
     * @date 2018-8-21
     * 后台系统首页
     */
    public function index ()
    {
        $info=$this->server_info();
        $this->assign('server',$info);
        return $this->fetch();
    }

    /**
     * @author ty
     * @date 2018-9-29
     * 清理前台的一些缓存，主要是左侧菜单的一些数据
     */
    public function clean(){
        $clean=cache(null);
        if($clean) return json(show_msg('','成功清理缓存',0));
        return json(show_msg('','清理失败'));
    }
}
