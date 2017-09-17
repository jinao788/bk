<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Conf as ConfModel;

class Conf extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $confs=ConfModel::paginate(10);
        $count = ConfModel::count();
        $this->assign(['confs'=>$confs,'count'=>$count]);
        
        return view('conf_list');
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
            $validate = \think\Loader::validate('conf');
            if(!$validate->check($data)){
            return ['status'=> 0, 'message'=> $validate->getError()];
            die;
            }

        
            $res = ConfModel::create($data);
            
            if (is_null($res)) {
                $status = 0;
                $message = '添加失败';
            }

            $status = 1;
            $message = '添加成功';
            return ['status'=> $status, 'message'=> $message, 'res'=> $res];  //->toJson()
        }
    
        return $this->view->fetch('conf_add');
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
    public function conf(Request $request)
    {
        if (request()->isPost()){  //修改时候过滤空值常态化
            $data=input('post.');
            
            //获取提交的数据,自动过滤一下空值
            //$data = array_filter($request->param());
            //dump($data);die;
            foreach ($data as $k => $v) {
               ConfModel::update(['value'=>$v], ['enname' => $k ]);
            }

            $this->success('修改配置成功');


            
           
        }
        



        $confs=ConfModel::all();
        //dump($confres);die;
        $this->assign('confs',$confs);    
        return view('conf');
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(Request $request)
    {
        $conf = ConfModel::get($request->param('id'));
        $this -> view -> assign('conf', $conf);
        return $this->view->fetch('conf_edit');
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
        if ($request -> post()){  //修改时候过滤空值常态化
            $data=input('post.');
            //dump($data);die;
            //获取提交的数据,自动过滤一下空值
            $data = array_filter($request->param());
            

            $validate = \think\Loader::validate('conf');
            if(!$validate->check($data)){
            return ['status'=> 0, 'message'=> $validate->getError()];
            die;
            }

         
            $res = ConfModel::update($data, ['id' => $data['id']]);

            $status = 1;
            $message = '更新成功';

            
            if (is_null($res)) {
                $status = 0;
                $message = '更新失败';
            }
        }
        return ['status'=>$status, 'message'=>$message];
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
       ConfModel::destroy($request -> param('id'));
    }
}
