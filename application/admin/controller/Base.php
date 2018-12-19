<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{

    private $pass_path=[
        'admin/index/index'
    ];  //免权限检测页面

    /**
     * 初始化操作
     * @access protected
     */
    public function __construct()
    {
        parent::__construct(null);
        //检测登录信息
        if(!session('user_info')) $this->redirect(url('Login/index'));
        //获取用户权限
        if(!session('rule'))$this->get_menu();
        $menu_data=session('rule');
        //当前访问路径
        $now_path=strtolower(request()->module().'/'.request()->controller().'/'.request()->action());
        $rule_path=[];
        foreach(array_column($menu_data,'name') as $v){
           $rule_path[]=strtolower($v);
        }
        //当前用户获得的权限
        $rule_path=array_merge($rule_path,$this->pass_path);
        if(
            !in_array($now_path,$rule_path) &&
            session('user_info.group_id')!='0' &&
            !preg_match("/^public_{1}\w+/i",request()->action() )
        )
        {//分组为0的为超级管理员，拥有所有权限
            $this->error('无权限');
        }
        $menu_tree=$this->mk_tree(session('menu'));
        $this->assign([
            'menu_tree'=>$menu_tree,
            'now_path'=>$now_path,
            'now_menu'=>isset($menu_data[$now_path]['title'])?"<li class='active'>{$menu_data[$now_path]['title']}</li>":'', //面包导航（当前目录）
            'title'=>isset($menu_data[$now_path]['title'])?$menu_data[$now_path]['title']:'系统首页' // 头部标题
        ]);
    }

    /**
     * @author ty
     * @date 2018-8-21
     * 构造树形结构菜单
     */

    public function mk_tree($list,$pk='id',$pid='pid',$child='_child',$root=0){
        $tree=array();
        foreach($list as $key=> $val){
            if($val[$pid]==$root){
                //获取当前$pid所有子类
                unset($list[$key]);
                if(! empty($list)){
                    $child=$this->mk_tree($list,$pk,$pid,$child,$val[$pk]);
                    $val['_child']=$child;
                   /* if(!empty($child)){
                        $val['_child']=$child;
                    }*/
                }
                $tree[]=$val;
            }
        }
        return $tree;
    }
    /**
     * @author ty
     * @date 2018-8-21
     * 获取菜单
     */

    private function get_menu(){
        $user_id=session('user_info.user_id');
        $group_id=session('user_info.group_id');
        $m=db('');
        $rules=$m->table('ty_admin a')
            ->join('ty_auth_group g','g.id=a.group_id')
            ->where(['a.user_id'=>$user_id])
            ->field('g.rules')->find();
        $where="id in({$rules['rules']})";
        if($group_id=='0') $where='1=1'; //分组为0的为系统管理员，拥有所有权限
        if(!$rules && $group_id!='0'){
            $rule=[];
        }else{
            $rule=$m->table('ty_auth_rule')
                ->where($where)
                ->field('id,name,title,pid,menu')->select();
        }
        //权限
        $data=[];
        //菜单
        $menu=[];
        if($rule){
            foreach($rule as $k=>$v){
                $data[strtolower($v['name'])]=$v;
                if($v['name']=='/'){
                    $data[strtolower($v['name'].$k)]=$v;
                }
                if($v['menu']=='1'){
                   $menu[]=$v;
                }
            }
        }
        unset($rules,$rule);
        session('rule',$data);
        session('menu',$menu);
    }
    /**
     * @author ty
     * @date 2018-8-21
     * 服务器信息
     */
    public function server_info(){
        $sys_info_array = array ();
        $sys_info_array ['gmt_time'] = date ( "Y年m月d日 h:i:s", time ()- 8 * 3600 );//格林威治标准时间
        $sys_info_array ['bj_time'] = date ( "Y年m月d日 h:i:s", time ());//北京时间
        $sys_info_array ['server_ip'] = gethostbyname ( $_SERVER ["SERVER_NAME"] );//服务器ip地址
        $sys_info_array ['software'] = $_SERVER ["SERVER_SOFTWARE"];  //服务器解译引擎
        $sys_info_array ['port'] = $_SERVER ["SERVER_PORT"]; //web服务端口
        $sys_info_array ['admin'] = $_SERVER ["SERVER_ADMIN"];
        $sys_info_array ['url_path']=$_SERVER['HTTP_HOST'];
        $sys_info_array ['diskfree'] = intval ( diskfreespace ( "." ) / (1024 * 1024) ) . 'Mb';
        $sys_info_array ['current_user'] = @get_current_user ();
        $sys_info_array ['timezone'] = date_default_timezone_get();
        $mysql_version = db('')->query("select version()");
        $sys_info_array ['mysql_version'] = $mysql_version[0]['version()'];
        return $sys_info_array;
    }
}
