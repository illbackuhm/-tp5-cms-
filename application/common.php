<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 *返回给客户端的数据包
 *@param data 数据集合
 *@param msg 提示信息
 *@param code 标识
 */
function show_msg($data=[],$msg='',$code='1'){
    return [
        'data'=>$data,
        'msg'=>$msg,
        'code'=>$code
    ];
}

/**
 * 后台管理员账号加密方法,检测密码
 * 检测密码需要传入$encry_key
 * 返回一个指定$encry_key加密结果用于对比是否吻合
 * 97~122是小写的英文字母
   65~90是大写的
 * $str 生成一个随机码
 */
function mk_pwd($pwd='',$encry_key='',$num='8'){
    if(!strlen($encry_key)){
        $str='';
        for ($i = 1; $i <= $num; $i++) {
            $str.=chr(rand(97, 122)).chr(rand(65, 90));
        }
        $encry_key=substr($str,0,-1);
    }
    return [
       'pwd'=> md5(md5($pwd.'_'.$encry_key)),
       'encry_key'=>$encry_key
    ];
}

/**
 * 将数组的空置去掉
 */

function action_data($data=[]){
    foreach($data as $k=>$v){
        if(!strlen($v)) unset($data[$k]);
    }
    return $data;
}

/**
 * 检查必传字段，并且不能为空
 * 多个字段用逗号隔开
 * check('ceshi1,ceshi2')
 */

function check($parm=''){
    if(!strlen($parm)) return;
    $parm=explode(',',$parm);
    $app_parm_data=request()->param();
    foreach($parm as $v){
        if(!isset($app_parm_data[$v])) return show_msg('',$v.'不存在',1);
        if(!strlen($app_parm_data[$v])) return show_msg('',$v.'不能为空',1);
    }
    return;
}

/**
 *分页函数
 * @param $total 数据总条数
 * @param $page 当前页
 * @param $rows 每页显示多少条
 * @param $next 下一页
 * @param $pre 上一页
 * @param $last_page 总页数，最后一页
 * @param  $is_more 是否有下一页
 * @param $limit 数据查询拼接limit
 */

function page($total=0){
    $parm=request()->param();
    $is_more=true;
    $rows=isset($parm['rows'])?$parm['rows']:10;
    $page=isset($parm['page'])?$parm['page']:1;

    $last_page=ceil($total/$rows);

    if($page>=$last_page) $is_more=false;

    if($page>$last_page) $page=$last_page;

    $first_limit=($page-1)*$rows;

    if($last_page==0){
        $rows=$first_limit=0;
    }

    $limit=$first_limit.','.$rows;

    $next=($page+1)>$last_page?$last_page:$page+1;

    $pre=($page-1)<1?1:$page-1;

    return [
        'page'=>$page,
        'rows'=>$rows,
        'next'=>$next,
        'pre'=>$pre,
        'last_page'=>$last_page,
        'is_more'=>$is_more,
        'limit'=>$limit,
        'total'=>$total
    ];
}

