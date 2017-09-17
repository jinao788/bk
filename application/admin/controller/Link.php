<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Link as LinkModel;

class Link extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
         
        if(request()->isPost()){
            $orders=input('post.');
            foreach ($orders as $k => $v) {
                LinkModel::update(['id'=>$k,'order'=>$v]);
            }
            $this->success('更新栏目排序成功','lst');
    //print_r(input('post.'));die;
            return;
        }
        $links =LinkModel::paginate(2);
        $count = LinkModel::count();

        
        $this->assign(['links'=>$links,'count'=>$count]);
        return view('link_list');
        




      
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        // if($this->request->isPost()){
        //     //获取提交数据,含上传文件
        //     $data = $this->request->param();
            if(request()->isPost()){
                $data=input('post.');

              
                $validate = \think\Loader::validate('Link');
                if(!$validate->check($data)){
                return ['status'=> 0, 'message'=> $validate->getError()];
                die;
                }

          
                
                $res = LinkModel::create($data);
                if(is_null($res)){
                    $status = 0;
                    $message = '添加失败';
                }
                    $status = 1;
                    $message = '添加成功';
                return ['status'=> $status, 'message'=> $message];       

        }
            

        return view('link_add');
        
   

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
        $link_id = $request -> param('id');
        $links = LinkModel::get($link_id);
        $this->assign('links',$links);
        return view('link_edit');
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
        if(request()->isPost()){
            $data=input('post.');
            $data = array_filter($request->param());

          
            $validate = \think\Loader::validate('Link');
            if(!$validate->check($data)){
            return ['status'=> 0, 'message'=> $validate->getError()];
            die;
            }

      
            
            $res = LinkModel::update($data,$data['id']);
            if(is_null($res)){
                $status = 0;
                $message = '修改失败';
            }
                $status = 1;
                $message = '修改成功'; 
            return ['status'=> $status, 'message'=> $message];     

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
        $link_id = $request -> param('id');
        LinkModel::destroy($link_id);
    }
}
