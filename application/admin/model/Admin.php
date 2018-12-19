<?php
namespace app\admin\model;

use think\Db;
use think\Model;

class Admin extends Model{

    /**
     * @author ty
     * @date 2018-8-21
     * 管理员列表  4
     */
    public function admin_list()
    {
       $field='u.name,u.type,u.user_id,g.title';
       $where='u.user_id!='.session('user_info.user_id');
       $data=db('admin u')->where($where)->join('ty_auth_group g','u.group_id=g.id','LEFT')->field($field)->paginate(20)->each(function($item, $key){
           $item['type_zh']=$item['type']=='1'?'正常':'禁止';
           return $item;
       });
       return $data;
    }

    /**
     * @author ty
     * @date 2018-8-21
     * 修改个人资料
     */
    public function up_own ()
    {
        $check=check('name'); if($check) return $check;
        $data=action_data(input('post.'));
        if(isset($data['pwd'])){
            $pwd_data=mk_pwd($data['pwd']);
            $data['pwd']=$pwd_data['pwd'];
            $data['encry_key']=$pwd_data['encry_key'];
        }
        $up=db('admin')->where(['user_id'=>session('user_info.user_id')])->update($data);
        if($up){
            session('user_info.name',$data['name']);
            return show_msg('','修改成功',0);
        }else{
            return show_msg('','修改失败',1);
        }
    }

    /**
     * @author ty
     * @date 2018-8-21
     * 修改管理员资料
     */
    public function admin_edit ()
    {
        $check=check('name,user_id,type'); if($check) return $check;
        $data=action_data(input('post.'));
        if(isset($data['pwd'])){
            if(input('pwd')!=input('r_pwd')) return show_msg('','密码不一致',1);
            $pwd_data=mk_pwd($data['pwd']);
            $data['pwd']=$pwd_data['pwd'];
            $data['encry_key']=$pwd_data['encry_key'];
        }
        unset($data['r_pwd']);
        $up=db('admin')->update($data);
        if($up){
            return show_msg('','修改成功',0);
        }else{
            return show_msg('','修改失败',1);
        }
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 添加管理员
     */
    public function admin_add ()
    {
        $check=check('name,pwd,r_pwd,group_id'); if($check) return $check;
        $data=input('post.');
        $m=db('admin');
        //检查账号是否存在
        $find=$m->where(['name'=>$data['name']])->find();
        if(!empty($find)) return show_msg('','账号已存在');
        if($data['pwd']!=$data['r_pwd']) return show_msg('','密码不一致',1);
        $pwd_info=mk_pwd($data['pwd']);
        $data['pwd']=$pwd_info['pwd'];
        $data['encry_key']=$pwd_info['encry_key'];
        unset($data['r_pwd']);
        $add=db('admin')->insert($data);
        if($add) return show_msg('','添加成功',0);
        return show_msg('','添加失败');
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 获取所有角色信息
     */
    public function group_list ()
    {
        $field='title,id';
        $data=db('auth_group')->where('1=1')->field($field)->select();
        return $data;
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 获取一个管理员信息
     */
    public function admin_info ()
    {
        $field='user_id,name,phone,email,group_id,type';
        $user_id=input('user_id','');
        if(!strlen($user_id)) return [];
        $data=db('admin')->where(['user_id'=>$user_id])->field($field)->find();
        return $data;
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 管理员禁用/启用
     */
    public function chan_type ()
    {
        $check=check('type,uid'); if($check) return $check;
        $type=input('type');
        $uid=input('uid');
        $up=db('admin')->where(['user_id'=>$uid])->update(['type'=>$type]);
        if($up) return show_msg('','操作成功',0);
        return show_msg('','操作失败',1);
    }
}