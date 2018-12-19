<?php
namespace app\admin\controller;

use think\Controller;

class Article extends Base
{

    private $model='';

    public function __construct()
    {
        parent::__construct();
        $this->model=new \app\admin\model\Article();
    }

    /**
     * @author ty
     * @date 2018-8-23
     * 文章列表
     */

    public function index(){
        $data=$this->model->article_list();

        //分类
        $type=$this->model->article_type();
        $this->assign([
            'data'=>$data['data'],
            'search'=>$data['search'],
            'type'=>$type
        ]);
        return $this->fetch();
    }

    /**
     * @author ty
     * @date 2018-8-24
     * 文章分类列表
     */

    public function article_type(){
        $data=$this->model->article_type();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 文章分类添加
     */

    public function type_add()
    {
        if(request()->isPost()){
            $data=$this->model->type_add();
            return json($data);
        }else{
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 文章分类修改
     */

    public function type_edit()
    {
        if(request()->isPost()){
            $data=$this->model->type_edit();
            return json($data);
        }else{
            $data=$this->model->get_one_type();
            $this->assign('data',$data);
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-8-27
     * 文章分类删除
     */

    public function type_del()
    {
        $data=$this->model->type_del();
        return json($data);
    }

    /**
     * @author ty
     * @date 2018-8-23
     * 文章添加
     */

    public function article_add()
    {
        if (request()->isPost()) {
            $data=$this->model->article_add();
            return json($data);
        } else {
            $this->ys('./uploads/article/20181109/main.png','./uploads/article/20181109/ys.jpg');
            $type_list=$this->model->article_type();
            $this->assign('type_list',$type_list);
            return $this->fetch();
        }
    }

    public function ys($imgsrc, $imgdst) {
        list($width, $height, $type) = getimagesize($imgsrc);

        $new_width = $width;//压缩后的图片宽
        $new_height = $height;//压缩后的图片高

        if($width >= 600){
            $per = 600 / $width;//计算比例
            $new_width = $width * $per;
            $new_height = $height * $per;
        }

        switch ($type) {
            case 1:
                header('Content-Type:image/gif');
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromgif($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                //90代表的是质量、压缩图片容量大小
                imagejpeg($image_wp, $imgdst, 90);
                imagedestroy($image_wp);
                imagedestroy($image);
                break;
            case 2:
                header('Content-Type:image/jpeg');
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromjpeg($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                //90代表的是质量、压缩图片容量大小
                imagejpeg($image_wp, $imgdst, 90);
                imagedestroy($image_wp);
                imagedestroy($image);
                break;
            case 3:
                header('Content-Type:image/png');
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefrompng($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                //90代表的是质量、压缩图片容量大小
                imagejpeg($image_wp, $imgdst, 90);
                imagedestroy($image_wp);
                imagedestroy($image);
                break;
        }
    }

    /**
     * @author ty
     * @date 2018-8-24
     * 文章修改
     */

    public function article_edit()
    {
        if (request()->isPost()) {
            $data=$this->model->article_edit();
            return json($data);
        } else {
            $type_list=$this->model->article_type();
            $article=$this->model->get_one_article();
            $this->assign([
                'type_list'=>$type_list,
                'data'=>$article
            ]);
            return $this->fetch();
        }
    }

    /**
     * @author ty
     * @date 2018-8-24
     * 文章删除
     */

    public function article_del(){
        $data=$this->model->article_del();
        return json($data);
    }

    /**
     * @author ty
     * @date 2018-8-23
     * 图片处理
     */

    public function public_upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $path='./uploads/article/';
            if(!file_exists($path)) mkdir($path,0777,true);
            $info = $file->move($path);
            if($info){
                // 成功上传后 获取上传信息
                return json(show_msg([
                    'pic'=>substr($path,1).$info->getSaveName()
                ],'上传成功',0));
            }else{
                return json(show_msg('',$info->getError(),1));
            }
        }
    }

    /**
     * 评论列表
     * @return mixed
     */
    public function comment(){
        $data=$this->model->comment();
        $this->assign([
            'data'=>$data
        ]);
        return $this->fetch();
    }

    /**
     * 评论详情
     * @return mixed
     */
    public function comment_info(){
        $data=$this->model->comment_info();
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function chan_comment(){
        $chan=$this->model->chan_comment();
        if($chan) return json(show_msg('','修改成功',0));
        return json(show_msg('','修改失败'));
    }

    /**
     * 删除评论
     * @return \think\response\Json
     * @throws \think\Exception
     */
    public function comment_del(){
        $id=input('id','');
        if(!strlen($id)) return json(show_msg('','参数错误'));
        $del=db('comment')->where(['id'=>$id])->delete();
        if($del) return json(show_msg('','删除成功',0));
        return json(show_msg('','删除失败'));
    }
}