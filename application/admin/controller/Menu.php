<?php
namespace app\admin\controller;

use think\Controller;

class Menu extends Base
{

    private $model='';

    public function __construct()
    {
        parent::__construct();
        $this->model=new \app\admin\model\Menu();
    }
    /**
     * @author ty
     * @date 2018-8-22
     * 节点列表
     */

    public function index(){
        $data=$this->model->menu_list();
        $this->assign([
            'data'=>$data['list'],
            'page'=>$data['page'],
            'search'=>$data['search']
        ]);
        return $this->fetch();
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 节点添加
     */

    public function menu_add(){
        if(request()->isPost()){
            $data=$this->model->menu_add();
            return json($data);
        }else{
            $top_menu=$this->model->get_top_menu();
            $this->assign('list',$top_menu);
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 节点修改
     */

    public function menu_edit(){
        if(request()->isPost()){
            $data=$this->model->menu_edit();
            return json($data);
        }else{
            $top_menu=$this->model->get_top_menu();
            //节点信息
            $data=$this->model->get_one_menu();
            $this->assign([
                'list'=>$top_menu,
                'data'=>$data
            ]);
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-8-23
     * 节点删除
     */

    public function menu_del(){
        $data=$this->model->menu_del();
        return json($data);
    }
}