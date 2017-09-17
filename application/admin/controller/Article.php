<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Validate;
use app\admin\model\Article as ArticleModel;
use app\admin\model\Cate as CateModel;
//use app\admin\controller\Base;

class Article extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $articles =ArticleModel::order('id desc')->paginate(12);
        $count = ArticleModel::count();

        foreach ($articles as $value) {
            $value -> cate =isset( $value -> cate -> cate_name)? $value -> cate -> cate_name : '<span style="color:red;">暂无分类</span>';
        }
        $this->assign(['articles'=>$articles,'count'=>$count]);
        return view('article_list');
    }

   






    public function create()
    {
       
        if($this->request->isPost()){
            //获取提交数据,含上传文件
            $data = $this->request->param(true);
            //获取上传文件的对象
            $file = $this->request->file('thumb');
            //dump($data);die;
                $validate = \think\Loader::validate('Article');
                if(!$validate->check($data)){
                   $this->error($validate->getError()); die;
                }

            if(!empty($file)){

                    $map =[
                        'ext'=>'jpg,png,gif',
                        'size'=>3000000
                     ];
                $info= $file->validate($map)->move(ROOT_PATH.'public/uploads/');
                //dump($info);die;
                if($info==false){
                    $this->error($file->getError());
                    //$this->error('格式不为jpg或png或大小超过3M');
                    //return ['status'=>0 , 'message'=>$validate->getError()]; die;
                }
                    $data['thumb'] = $info->getSaveName();
            }else{

                   // $data['thumb']='\static\admin\images\error.png';
            }
                
                $res = ArticleModel::create($data);
                if(is_null($res)){
                    $this->error('新增失败');
                }
                    $this->success('新增成功');    


        }else{

            $cates = CateModel::getCate();
            //$cates = CateModel::paginate(5);
            $this -> view -> assign(['cates'=> $cates]);

            return view('article_add');
        }
   

       
}

    public function setStatus(Request $request)
    {
        $art_id = $request -> param('id');
        $result = ArticleModel::get($art_id);
//dump($result->getData('status'));die;
        if($result->getData('status') == 1) {
            ArticleModel::update(['status'=>0],['id'=>$art_id]);
           
        } else {
            ArticleModel::update(['status'=>1],['id'=>$art_id]);
            
        }
    }



    

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(Request $request)
    {
          $art_id = $request -> param('id');
          $arts = ArticleModel::get($art_id);

         //3.递归查询所有的分类信息(应该是除自己分类以外的)
          $cate_level = CateModel::getCate();

          $this->assign(['cate_level'=>$cate_level,'arts'=>$arts]);
          return view('article_edit');
          
           
           
        
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        if($this->request->isPost()){
            //获取提交数据,含上传文件
            $data = $this->request->param(true);
            //dump($data);die;
            //$data = array_filter($request->param());
            //获取上传文件的对象
            $file = $this->request->file('thumb');
            //dump($data);die;
            
            $validate = \think\Loader::validate('Article');
            if(!$validate->check($data)){
               $this->error($validate->getError()); die;
            }


            if(!empty($file)){

                $map =[
                        'ext'=>'jpg,png,jif',
                        'size'=>3000000
                     ];
                $info= $file->validate($map)->move(ROOT_PATH.'public/uploads/');
                //dump($info);die;
                if($info==false){
                    $this->error($file->getError());
                  
                    //return ['status'=>0 , 'message'=>$validate->getError()]; die;
                }
                    $data['thumb'] = $info->getSaveName();
            }else{

                   // $data['thumb']='\static\admin\images\error.png';
            }
                
                $res = ArticleModel::update($data,['id'=>$data['id']]); //返回是0和1
                if(is_null($res)){
                    $this->error('修改失败');
                }
                    $this->success('修改成功');    
        }            

        




    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        $art_id = $request -> param('id');
        ArticleModel::destroy($art_id);
    }
}
