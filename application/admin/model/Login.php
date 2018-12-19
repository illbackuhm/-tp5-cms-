<?php
namespace app\admin\model;

use think\Db;
use think\Model;
use think\Validate;

class Login extends Model{

    /**
     * @author ty
     * @date 2018-8-21
     * 处理登录
     */
    public function do_login(){
        $verify=input('verify','');
        if(!captcha_check($verify)) return show_msg('','验证码错误',1);
        $rules = [
            'name'  => ['require','/^[a-zA-z0-9]+$/'],
            'pwd'   => '/^\w{6,}$/',
        ];
        $validate = new Validate($rules);
        $data=[
            'name'=>input('name'),
            'pwd'=>input('pwd')
        ];
        if(!$validate->check($data)){
            return show_msg('',$validate->getError(),1);
        }
        $m=db('admin u');
        $field='u.encry_key,u.pwd,u.name,u.type,u.group_id,u.user_id,g.rules';
        $user_info=$m->where(['name'=>$data['name']])->join('ty_auth_group g','u.group_id=g.id','LEFT')->field($field)->find();
        if($user_info['type']=='2') return show_msg('','你已被禁用，请联系网站管理员！',1);
        $pwd_info=mk_pwd($data['pwd'],$user_info['encry_key']);
        if($pwd_info['pwd']!==$user_info['pwd']) return show_msg('','账号或密码错误');
        session('user_info',$user_info);
        return show_msg('','登录成功！',0);
    }
}