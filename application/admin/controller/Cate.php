<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Cate as CateModel;

//use app\admin\controller\Base;

class Cate extends Controller
   {

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        //$cate_id = $request -> param('cate_id');
        //dump($cate_id);die;
        //
        //$cate = new CateModel();
        if(request()->isPost()){
           
            $orders = array_filter(input('post.'));
            //dump($orders);die;
            foreach ($orders as $k => $v) {
                CateModel::update(['cate_id'=>$k,'cate_order'=>$v]);
            }
            $this->success('更新栏目排序成功');
    //print_r(input('post.'));die;
            return;
        }



        $cates = CateModel::getCate();
        $count = CateModel::count();
        $cate_list=CateModel::order(['cate_order'])-> select();
        
        $this -> view -> assign(['cates'=> $cates,'cate_list'=> $cate_list,'count'=> $count]);
        return view('category');
    }






    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
       if(request()->isPost()){
            $data=input('post.');
//dump($data);die;
            $validate = \think\Loader::validate('cate');
            if(!$validate->check($data)){
            // $damin=new AdminModel;
            // $damin->alloeField(true)->\think\Loader::validate(true);
            // $damin->getError();
            // return ['status'=> 0, 'message'=> $damin->getError()];
            return ['status'=> 0, 'message'=> $validate->getError()];
            die;
            }

        
            $res = CateModel::create($data);
            
            if (is_null($res)) {
                $status = 0;
                $message = '添加失败';
            }

            $status = 1;
            $message = '添加成功';
            return ['status'=> $status, 'message'=> $message, 'res'=> $res];  //->toJson()
        }
    
        return $this->view->fetch('admin_add');

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
        //1.获取一下分类id
        $cate_id = $request -> param('id');
//dump($cate_id);die;
        //2.查询要更新的数据
        $cate_now = CateModel::get($cate_id);

        //3.递归查询所有的分类信息(应该是除自己分类以外的)
        $cate_level = CateModel::getCate();

        $this -> view -> assign(['cate_now'=>$cate_now,'cate_level'=>$cate_level]);
        return view('cate_edit');
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
        //1.获取一下提交的数据
        $data = $request -> param();
        $data = array_filter($request->param());
        //dump($data);die;
        $validate = \think\Loader::validate('cate');
            if(!$validate->check($data)){
            return ['status'=> 0, 'message'=> $validate->getError()];
            die;
            }

       
        $res = CateModel::update($data,['cate_id'=> $data['cate_id']]);

        if (is_null($res)) {
            $status = 0;
            $message = '更新失败';
        }

        $status = 1;
        $message = '更新成功';
        
        return ['status'=>$status, 'message'=> $message];
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
     public function delete(Request $request)
    {

        // $cate_id = $request -> param('cate_id');

        // //CateModel::update(['is_delete'=>1],['cate_id'=> $cate_id]);
        // CateModel::destroy($cate_id);

    }


    protected $beforeActionList = [
        'delAllCate' =>['only' =>'delete']
        ];


    public function delAllCate()
    {
       
           $request = Request::instance();
           $cate_id = $request -> param('cate_id');
//dump($cate_id);die;
            
            $catemodel = new CateModel();
            $sonids=$catemodel->getchildrenid($cate_id);
//dump($sonids);die; //纯子集
            $sonids[]=$cate_id; //含自己

            foreach ($sonids as $k => $v) {
            	CateModel::destroy($v);
            }
            
        }

      
    //恢复删除操作
    public function Reback()
    {
        StudentModel::update(['delete_time'=>NULL],['is_delete'=>1]);
    }





}
