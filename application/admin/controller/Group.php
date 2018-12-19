<?php
namespace app\admin\controller;

use think\Controller;

class Group extends Base
{
    private $model='';

    public function __construct()
    {
        parent::__construct();
        $this->model=new \app\admin\model\Group();
    }
    /**
     * @author ty
     * @date 2018-8-22
     * 角色列表
     */
    public function index ()
    {
        $data=$this->model->index();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 角色添加
     */
    public function role_add ()
    {
        if(request()->isPost()){
            $data=$this->model->role_add();
            return json($data);
        }else{
            $data=$this->model->get_rule_list();
            $tree=$this->mk_tree($data);
            $this->assign('list',$tree);
            return $this->fetch();
        }
    }


    /**
     * @author ty
     * @date 2018-8-22
     * 角色修改
     */
    public function role_edit ()
    {
        if(request()->isPost()){
            $data=$this->model->role_edit();
            return json($data);
        }else{
            $data=$this->model->get_rule_list('all');
            $rule_info=$this->model->get_rule_info();
            $rule_data=explode(',',$rule_info['rules']);
            $tree=$this->mk_tree($data);
            $this->assign([
                'list'=>$tree,
                'rule_data'=>$rule_data,
                'rule_info'=>$rule_info
            ]);
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 角色删除
     */
    public function role_del ()
    {
        $data=$this->model->role_del();
        return json($data);
    }
}
