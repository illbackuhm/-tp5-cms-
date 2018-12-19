<?php

namespace app\admin\model;

use think\Model;

class Group extends Model{

    /**
     * @author ty
     * @date 2018-8-22
     * 角色列表
     */
    public function index ()
    {
        $field='id,title,status';
        $data=db('auth_group')->where('1=1')->field($field)->paginate(20)->each(function($item,$key){
            $item['status_zh']=$item['status']=='1'?'正常':'禁用';
            return $item;
        });
        return $data;
    }

    /**
     * @author ty
     * @date 2018-8-22
     * @parm $type 查询方式，默认查询按分页查询。all 查询所有数据，
     * 获取所有权限
     */
    public function get_rule_list ()
    {
        $field='id,name,title,menu,pid';
        $where='1=1';
        if(session('user_info.group_id')>0){
            $rules=session('user_info.rules');
            if(!strlen($rules))  return [];
            $where.=" and id in ({$rules})";
        }
        $data=db('auth_rule')->where($where)->field($field)->select();
        return $data;
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 角色添加
     */
    public function role_add ()
    {
        $check=check('title,rules'); if($check) return $check;
        $data=input('post.');
        $m=db('auth_group');
        $check=$m->where(['title'=>$data['title']])->find();
        if($check) return show_msg('','已存在同名角色',1);
        $add=$m->insert($data);
        if($add) return show_msg('','添加成功',0);
        return show_msg('','添加失败',1);
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 角色修改
     */
    public function role_edit ()
    {
        $check=check('title,rules,id'); if($check) return $check;
        $data=input('post.');
        $up=db('auth_group')->update($data);
        if($up) return show_msg('','修改成功',0);
        return show_msg('','修改失败',1);
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 角色删除
     */
    public function role_del ()
    {
        $check=check('id'); if($check) return $check;
        $id=input('id');
        //检查是否有管理员持有该角色
        $m=db();
        $find=$m->table('ty_admin')->where(['group_id'=>$id])->find();
        if($find) return show_msg('','有管理员持有该角色，暂不能删除',1);
        $del=$m->table('ty_auth_group')->where(['id'=>$id])->delete();
        if($del) return show_msg('','删除成功',0);
        return show_msg('','删除失败',1);
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 获取单条角色信息
     */
    public function get_rule_info (){
        $check=check('id'); if($check) return $check;
        $id=input('id');
        $field='id,title,rules,remarks';
        $data=db('auth_group')->where(['id'=>$id])->field($field)->find();
        return $data;
    }
}