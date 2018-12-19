<?php
namespace app\admin\controller;

use think\Controller;

class Conf extends Base
{
    private $model='';

    public function __construct()
    {
        parent::__construct();
        $this->model=new \app\admin\model\Conf();
    }

    public function index(){
        return $this->fetch();
    }

    /**
     * @author ty
     * @date 2018-9-7
     * 友情链接列表
     */
    public function link ()
    {
        $data=$this->model->link();
        $this->assign('data',$data);
        return $this->fetch();
    }


    /**
     * @author ty
     * @date 2018-9-7
     * 新增友情链接
     */
    public function link_add ()
    {
       if(request()->isPost()){
           $data=$this->model->link_add();
           return json($data);
       }else{
           return $this->fetch();
       }
    }

    /**
     * @author ty
     * @date 2018-9-7
     * 修改友情链接
     */
    public function link_edit ()
    {
        if(request()->isPost()){
            $data=$this->model->link_edit();
            return json($data);
        }else{
            $data=$this->model->get_one_link();
            $this->assign('data',$data);
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-9-7
     * 友情链接删除
     */
    public function link_del ()
    {
        $data=$this->model->link_del();
        return json($data);
    }

    /**
     * @author ty
     * @date 2018-9-13
     * 动态列表
     */

    public function dynamic(){
        $data=$this->model->dynamic();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * @author ty
     * @date 2018-9-13
     * 动态-修改
     */

    public function dynamic_edit(){
        if(request()->isPost()){
            $data=$this->model->dynamic_edit();
            return json($data);
        }else{
            $data=$this->model->get_one_dynamic();
            $this->assign('data',$data);
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-9-13
     * 动态-添加
     */

    public function dynamic_add(){
        if(request()->isPost()){
            $data=$this->model->dynamic_add();
            return json($data);
        }else{
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-9-13
     * 动态删除
     */
    public function dynamic_del ()
    {
        $data=$this->model->dynamic_del();
        return json($data);
    }

}
