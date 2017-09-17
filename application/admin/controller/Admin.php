<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\Admin as AdminModel;

class Admin extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    

    public function  index()
    {
        //判断当前是不是admin用户
        //先通过session获取到用户登陆名
        $userName = Session::get('user_info.username');
        if ($userName == 'admin') {
            $admins = AdminModel::all();  //admin用户可以查看所有记录,数据要经过模型获取器处理
            $admins_list=AdminModel::order(['id'])->  paginate(5);
            $count = AdminModel::count();

            $this -> view -> assign(['admins'=> $admins,'count'=> $count,'admins_list'=>$admins_list]);
            return $this -> view -> fetch('admin_list');
         } else {
          
            $list_one = AdminModel::get(['username'=>$userName]);
            $this -> view -> assign('list_one', $list_one);
            return $this -> view -> fetch('admin_list_one');
        }
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function add()  //一般新增带密码的(管理员,会员等)会多一个确认密码字段,最好还是用create然后unset,其他情况基本都是create
    {
         
        if(request()->isPost()){
            $data=input('post.');
            //dump($data);die;
            $validate = \think\Loader::validate('admin');
            if(!$validate->check($data)){
            return ['status'=> 0, 'message'=> $validate->getError()];
            die;
            }

            $daminmodel = new AdminModel();
            $res = $daminmodel->allowField(true)->save($data);
            //$res = AdminModel::create($data); 需要过滤repass字段

            //3.添加失败的处理
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


  
    public function edit(Request $request)
    {
        
        $admin = AdminModel::get($request->param('id'));
        $this -> view -> assign('admin', $admin);
        return $this->view->fetch('admin_edit');
    }

   
    public function update(Request $request)
    {
        if ($request -> isAjax(true)){  //修改时候过滤空值常态化

            //获取提交的数据,自动过滤一下空值
            $data = array_filter($request->param());
            unset($data['repass']);

            $validate = \think\Loader::validate('admin');
            if(!$validate->scene('edit')->check($data)){
            return ['status'=> 0, 'message'=> $validate->getError()];
            die;
            }

         
            $res = AdminModel::update($data, ['id' => $data['id']]);

            $status = 1;
            $message = '更新成功';

            
            if (is_null($res)) {
                $status = 0;
                $message = '更新失败';
            }
        }
        return ['status'=>$status, 'message'=>$message];
    
    }

    
    public function updatepass(Request $request)
    {
            $admin = AdminModel::get($request->param('id'));
            $this -> view -> assign('admin', $admin);


            if ($request -> isAjax(true)){  //修改时候过滤空值常态化
                $data = $request->param();
                unset($data['repass']);
                if(md5($data['opass']) == AdminModel::get($data['id'])->pass){
                    unset($data['opass']);

                    if(strlen($data['pass']) > 5 && strlen($data['pass']) < 16 ){
                        
                        $res = AdminModel::update(['pass'=>md5($data['pass']),'id'=> $data['id']]);  
                            $status = 1;
                            $message = '密码修改成功';
                            if (is_null($res)) {
                                    $status = 0;
                                    $message = '密码修改失败';
                                }

                    }else{
                        return ['status'=> 0, 'message'=> '密码位数错误'];
                    }  

                }else{
                    return ['status'=> 0, 'message'=> '原密码错误'];
                }        

            return ['status'=>$status, 'message'=>$message];
            }
            return $this->view->fetch('admin_editpass');

}




     public function setStatus(Request $request)
    {
        $admin_id = $request -> param('id');
        $result = AdminModel::get($admin_id);

        if($result->getData('status') == 1) {
            AdminModel::update(['status'=>0],['id'=>$admin_id]);
           
        } else {
            AdminModel::update(['status'=>1],['id'=>$admin_id]);
            
        }
    }


    public function deleteAdmin(Request $request)
    {
        $admin_id = $request -> param('id');
        AdminModel::destroy($admin_id);

    }

    public function Reback()
    {
        AdminModel::update(['delete_time'=>NULL],['is_delete'=>1]);
        
    }



    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
