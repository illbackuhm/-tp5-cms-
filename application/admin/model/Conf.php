<?php

namespace app\admin\model;

use think\Model;

class Conf extends Model{


    /**
     * @author ty
     * @date 2018-8-22
     * 友情链接列表
     */
    public function link ()
    {
        $data=db('link')->paginate(20);
        return $data;
    }


    /**
     * @author ty
     * @date 2018-9-7
     * 新增友情链接
     */
    public function link_add ()
    {
        $check=check('name,url'); if($check) return $check;
        $data=input('post.');
        $add=db('link')->insert($data);
        if($add) return show_msg('','添加成功',0);
        return show_msg('','添加失败');
    }

    /**
     * @author ty
     * @date 2018-9-7
     * 修改友情链接
     */
    public function link_edit ()
    {
        $check=check('name,url,id'); if($check) return $check;
        $data=input('post.');
        $up=db('link')->update($data);
        if($up) return show_msg('','修改成功',0);
        return show_msg('','修改失败');
    }

    /**
     * @author ty
     * @date 2018-9-7
     * 获取一条友情链接
     */
    public function get_one_link ()
    {
        $id=input('id');
        $data=db('link')->where(['id'=>$id])->find();
        return $data;
    }

    /**
     * @author ty
     * @date 2018-9-7
     * 获取一条友情链接
     */
    public function get_one_dynamic ()
    {
        $id=input('id');
        $data=db('dynamic')->where(['id'=>$id])->find();
        return $data;
    }


    /**
     * @author ty
     * @date 2018-9-7
     * 友情链接删除
     */
    public function link_del ()
    {
        $check=check('id'); if($check) return $check;
        $id=input('id');
        $del=db('link')->where(['id'=>$id])->delete();
        if($del) return show_msg('','删除成功',0);
        return show_msg('','删除失败');
    }

    /**
     * @author ty
     * @date 2018-9-13
     * 动态列表
     */

    public function dynamic(){
        $data=db('dynamic')->paginate(10);
        return $data;
    }

    /**
     * @author ty
     * @date 2018-9-13
     * 修改动态
     */
    public function dynamic_edit ()
    {
        $check=check('editorValue,id'); if($check) return $check;
        $data=input('post.');
        $data['content']=$data['editorValue'];
        unset($data['editorValue']);
        $up=db('dynamic')->update($data);
        if($up) return show_msg('','修改成功',0);
        return show_msg('','修改失败');
    }

    /**
     * @author ty
     * @date 2018-9-13
     * 添加动态
     */
    public function dynamic_add ()
    {
        $check=check('editorValue'); if($check) return $check;
        $data=input('post.');
        $data['content']=$data['editorValue'];
        unset($data['editorValue']);
        $add=db('dynamic')->insert($data);
        if($add) return show_msg('','添加成功',0);
        return show_msg('','添加失败');
    }

    /**
     * @author ty
     * @date 2018-9-13
     * 动态删除
     */
    public function dynamic_del ()
    {
        $check=check('id'); if($check) return $check;
        $id=input('id');
        $del=db('dynamic')->where(['id'=>$id])->delete();
        if($del) return show_msg('','删除成功',0);
        return show_msg('','删除失败');
    }

}