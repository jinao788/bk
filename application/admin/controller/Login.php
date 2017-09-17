<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\Admin as AdminModel;
use app\admin\controller\Base;

class Login extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this -> alreadyLogin();
        return $this -> view -> fetch('login');
    }

    

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function check(Request $request)
    {
        //设置status
        $status = 0;

        //获取一下表单提交的数据,并保存在变量中
        $data = $request -> param();
        $userName = $data['username'];
        
//dump($data);die;

        //在admin表中进行查询:以用户为条件
        
        $admin = AdminModel::get(['username'=> $userName]);  //此处不能传$userName 得是数组

        //将用户名与密码分开验证

        //如果没有查询到该用户
        if (is_null($admin)){

            //设置返回信息
            $message = '用户名不正确';
        } elseif ($admin -> pass != md5($data['pass'])) {

            //设置一下密码不正确的提示信息
            $message = '密码不正确';
        } else {

            //如果用户名和密码都通过了验证,表明是合法用户
            //修改一下返回的信息
            $status = 1;
            $message = '验证通过,请点击确定进入后台';

            //更新表中登录次数与最后登录时间
            $admin -> setInc('login_count');
            
            $admin -> save(['login_ip'=> $request->ip(),'last_login'=> time()]);

            //将用户登录的信息保存到session中,供其它控制器进行登录判断
            Session::set('user_id', $userName);
            //这条语句只能存储username和password,其它字段不能存储
//            Session::set('user_info',$data);

//            应该修改成:
            Session::set('user_info',$admin->toArray()); //尽量用到什么保存什么.不要保存带密码的
        }

        return ['status'=> $status, 'message'=> $message];
    }


     public function logout()
    {

        AdminModel::update(['last_logout'=>time()],['id'=> Session::get('user_info.id')]); //这里最好是用id
        //删除当前用户session值
        Session::delete('user_id');
        Session::delete('user_info');
        //执行成功,并返回登录界面
        $this -> success('注销成功,正在返回...','login/index');
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
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
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
