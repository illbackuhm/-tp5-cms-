<?php

namespace app\admin\model;

use think\Model;

class Article extends Model{

    /**
     * @author ty
     * @date 2018-8-22
     * 文章列表
     */

    public function article_list(){
        $field='a.id,a.title,a.column_id,a.createdate,a.is_show,a.is_rec,p.title as p_title';
        $where='1=1';
        $bind=[];

        //标题搜索
        $title=input('title','');
        if(strlen($title)){
            $where.=" and instr(a.title,:title)";
            $bind['title']=$title;
        }

        //文章类型
        $type=input('type','');
        if(strlen($type)){
            $where.=" and p.id={$type}";
        }

        $search=[
            'type'=>$type,
            'title'=>$title
        ];
        $data=db('article a')
            ->join('article_type p','a.column_id=p.id','LEFT')
            ->where($where)
            ->bind($bind)
            ->field($field)->paginate(15,false,['query'=>$search])
            ->each(function($item){
            $item['is_show_zh']=$item['is_show']=='1'?'显示':'不显示';
            $item['is_rec_zh']=$item['is_rec']=='1'?'是':'否';
            return $item;
        });
        return [
            'data'=>$data,
            'search'=>$search
        ];
    }

    /**
     * @author ty
     * @date 2018-8-24
     * 文章分类列表
     */

    public function article_type(){
        $field='id,title,url';
        $data=db('article_type')->field($field)->select();
        return $data;
    }

    /**
     * @author ty
     * @date 2018-8-24
     * 文章添加
     * @parm editorValue  UEditor 编辑器默认将文本内容赋值到该字段
     */

    public function article_add()
    {
        $check=check('title,author,from,editorValue'); if($check) return $check;
        $data=input('post.');
        $data['pic']=isset($data['pic'][0])?$data['pic'][0]:'';
        $data['content']=$data['editorValue'];
        $data['createtime']=TIME;
        $data['createdate']=DATE;
        unset($data['editorValue']);
        $add=db('article')->insert($data);
        if($add) return show_msg('','添加成功',0);
        return show_msg('','添加失败',1);
    }

    /**
     * @author ty
     * @date 2018-8-24
     * 文章修改
     * @parm editorValue  UEditor 编辑器默认将文本内容赋值到该字段
     */

    public function article_edit()
    {
        $check=check('id,title,author,from,editorValue'); if($check) return $check;
        $data=input('post.');
        $data['content']=$data['editorValue'];
        unset($data['editorValue']);
        $up=db('article')->update($data);
        if($up) return show_msg('','修改成功',0);
        return show_msg('','修改失败',1);
    }

    /**
     * @author ty
     * @date 2018-8-24
     * 文章删除
     */

    public function article_del(){
        $id=input('id','');
        if(!strlen($id)) return show_msg('','参数错误',1);
        $del=db('article')->where(['id'=>$id])->delete();
        if($del) return show_msg('','删除成功',0);
        return show_msg('','删除失败',1);
     }

    /**
     * @author ty
     * @date 2018-8-24
     * 获取一篇文章详情
     */

    public function get_one_article(){
        $id=input('id','');
        if(!strlen($id)) return [];
        $field='id,pic,title,column_id,abstract,author,from,is_show,is_top,is_rec,keywords,sort,content';
        $data=db('article')->where(['id'=>$id])->field($field)->find();
        return $data;
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 获取一篇文章详情
     */

    public function get_one_type(){
        $id=input('id','');
        if(!strlen($id)) return [];
        $field='id,title,url';
        $data=db('article_type')->where(['id'=>$id])->field($field)->find();
        return $data;
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 文章分类添加
     */

    public function type_add()
    {
        $check=check('title'); if($check) return $check;
        $data=input('post.');
        $add=db('article_type')->insert($data);
        if($add) return show_msg('','添加成功',0);
        return show_msg('','添加失败');
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 文章分类修改
     */

    public function type_edit()
    {
        $check=check('id,title'); if($check) return $check;
        $data=input('post.');
        $up=db('article_type')->update($data);
        if($up) return show_msg('','修改成功',0);
        return show_msg('','修改失败');
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 文章分类删除
     */

    public function type_del()
    {
        $check=check('id'); if($check) return $check;
        $id=input('id');
        //检查该分类下是否有文章
        $find=db('article')->where(['column_id'=>$id])->find();
        if(!empty($find)) return show_msg('','该分类下有归属文章，暂不能删除');
        $del=db('article_type')->where(['id'=>$id])->delete();
        if($del) return show_msg('','删除成功',0);
        return show_msg('','删除失败');
    }


    /**
     * 评论列表
     * @return \think\Paginator
     */
    public function comment(){
        $field='id,replyName,time,address,is_show';
        $data=db('comment')->field($field)->order('is_show asc,id desc')->paginate(20);
        return $data;
    }

    /**
     * 评论详情
     * @return array|false|\PDOStatement|string|Model
     */
    public function comment_info(){
        $id=input('id');
        $field='a.id,a.replyName,a.beReplyName,a.time,a.address,a.is_show,a.email,a.web,a.to_web,a.content,a.is_show,b.title';
        $data=db('comment a')->join('ty_article b','a.s_id=b.id','LEFT')->where(['a.id'=>$id])->field($field)->find();
        return $data;
    }

    public function chan_comment(){
        $parm=input('post.');
        $id=$parm['id'];
        $is_show=$parm['is_show'];
        $chan=db('comment')->where(['id'=>$id])->update(['is_show'=>$is_show]);
        return $chan;
    }
}