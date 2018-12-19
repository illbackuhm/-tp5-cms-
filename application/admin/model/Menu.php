<?php

namespace app\admin\model;

use think\Model;

class Menu extends Model{

    /**
     * @author ty
     * @date 2018-8-22
     * 节点列表
     */

    public function menu_list(){
        $field='id,name,title,pid,menu';
        $top_menu=$this->get_top_menu();
        $where='1=1';
        $bind=[];
        if(strlen(input('title',''))){
            $title=input('title');
            $where.=" and  instr(title,:title)>0";
            $bind['title']=$title;
        }
        //搜索
        $search=[
            'title'=>input('title','')
        ];
        $data=db('auth_rule')->where($where)->bind($bind)->field($field)->paginate(20,false,[
            'query'=>[
                'title'=>$search['title']
            ]
        ]);
        $row=$data->toArray();
        foreach($row['data'] as &$v){
            $v['menu_zh']=$v['menu']=='1'?'是':'否';
            //所属分组
            $v['group']=$v['pid']=='0'?'顶级菜单':$top_menu[$v['pid']]['title'];
        }
        return [
            'list'=>$row['data'],
            'page'=>$data->render(),
            'search'=>$search
        ];
    }

    /**
     * @author ty
     * @date 2018-8-23
     * 获取单条节点信息
     */

    public function get_one_menu(){
        $id=input('id','');
        if(!strlen($id)) return [];
        $field='id,name,title,pid,menu';
        $data=db('auth_rule')->where(['id'=>$id])->field($field)->find();
        return $data;
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 获取所有顶级菜单
     */

    public function get_top_menu(){
        $field='id,name,title,pid,menu';
        $data=db('auth_rule')->where(['pid'=>'0'])->field($field)->select();
        $row=[];
        foreach($data as $v){
            $row[$v['id']]=$v;
        }
        return $row;
    }

    /**
     * @author ty
     * @date 2018-8-23
     * 节点添加
     */

    public function menu_add(){
        $check=check('title,name'); if($check) return $check;
        $data=input('post.');
        $name=$data['name'];

        $m=db('auth_rule');
        $find=$m->where(['name'=>$name])->find();
        if($find) return show_msg('','节点已存在',1);

        $add=db('auth_rule')->insert($data);
        if($add) return show_msg('','添加成功',0);

        return show_msg('','添加失败',1);
    }

    /**
     * @author ty
     * @date 2018-8-23
     * 节点修改
     */

    public function menu_edit(){
        $check=check('id,title,name'); if($check) return $check;

        $data=input('post.');
        $up=db('auth_rule')->update($data);

        if($up) return show_msg('','修改成功',0);
        return show_msg('','修改失败',1);
    }

    /**
     * @author ty
     * @date 2018-8-22
     * 节点删除
     */

    public function menu_del(){
        $check=check('id'); if($check) return $check;
        //查询该节点信息
        $menu_info=$this->get_one_menu();
        if(empty($menu_info)) return show_msg('','节点不存在',1);

        $m=db('auth_rule');
        $id=input('id');
        if($menu_info['pid']=='0'){
            $del=$m->where("id=:id or pid=:pid",[
                'id'=>$id,
                'pid'=>$id
            ])->delete();
        }else{
            $del=$m->where(['id'=>$id])->delete();
        }
        if($del) return show_msg('','删除成功',0);
        return show_msg('','删除失败',1);
    }

}