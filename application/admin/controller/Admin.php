<?php
namespace app\admin\controller;

use think\Controller;

class Admin extends Base
{
    private $model='';
    public function __construct()
    {
        parent::__construct();
        $this->model=new \app\admin\model\Admin();
    }

    /**
     * @author ty
     * @date 2018-8-21
     * 管理员列表  4
     */
    public function lists ()
    {
        $data=$this->model->admin_list();
        $this->assign('data',$data);
        return $this->fetch('index');
    }

    /**
     * @author ty
     * @date 2018-8-21
     * 修改个人资料
     */
    public function up_own ()
    {
        $data=$this->model->up_own();
        return json($data);
    }

    /**
     * @author ty
     * @date 2018-8-21
     * 修改管理员资料
     */
    public function admin_edit ()
    {
        if(request()->isPost()){
            $data=$this->model->admin_edit();
            return json($data);
        }else{
            //角色
            $group_list=$this->model->group_list();

            //管理员信息
            $admin_info=$this->model->admin_info();
            $this->assign([
                'group_list'=>$group_list,
                'admin_info'=>$admin_info
            ]);
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 添加管理员
     */
    public function admin_add ()
    {
        if(request()->isPost()){
            $data=$this->model->admin_add();
            return json($data);
        }else{
            //角色
            $group_list=$this->model->group_list();

            $this->assign('group_list',$group_list);
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-8-21
     * 角色管理
     */
    public function role ()
    {
        $data=$this->model->admin_list();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 管理员禁用/启用
     */
    public function chan_type ()
    {
        $data=$this->model->chan_type();
        return json($data);
    }
}
