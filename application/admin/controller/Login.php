<?php
namespace app\admin\controller;
use think\captcha\Captcha;
use think\Controller;


class Login extends Controller
{
    public $model='';

    public function __construct()
    {
        parent::__construct();
        $this->model=new \app\admin\model\Login();
    }


    /**
     * @author ty
     * @date 2018-8-21
     * 后台登录
     */
    public function index ()
    {
        if(request()->isPost()){
            $re=$this->model->do_login();
            return json($re);
        }else{
            if(session('user_info')) $this->redirect(url('admin/index/index'));
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-8-21
     * 退出登录
     */

    public function login_out(){
        session(null);
        $this->success('退出成功',url('index'));
    }

    /**
     * @author ty
     * @date 2018-8-21
     * 验证码
     */

    public function verify(){
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,
            // 验证码位数
            'length'      =>    3,
            //验证码字符集合
            'codeSet'=>'1234567890'
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }
}
