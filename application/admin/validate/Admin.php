<?php
namespace app\admin\validate;
use think\Validate;
class Admin extends Validate
{
     protected $rule = [
       ['username'  , 'require|unique:admin','管理员名称不能为空|名称已经存在'],
       ['pass' , 'require|min:6','密码不能为空|密码不能少于六位'],
       ['phone','require','手机不能为空'],
       ['email',  'require','邮箱不能为空'],
       ['role',  'require','角色不能为空'],
       
    //     protected $rule = [
    //      'username'  =>  'require|unique:admin',
    //      'pass' =>  'require|min:6',
    //      'phone'=>  'require',
    //      'email'=>  'require',
    //      'role'=>  'require',
        

    // ];

    //  protected $message  =   [
    //     'username.require' => '管理员名称不能为空',
    //     'username.unique' => '名称已经存在',
    //     'pass.min' => '密码不能少于六位',
    //     'pass.require' => '密码不能为空',
    //     'phone.require' => '手机不能为空',
    //     'email.require' => '邮箱不能为空',
    //     'role.require' => '角色不能为空',
      

        
        
    ];

     protected $scene = [
        'edit'  =>  ['phone','email'],
    ];
    



        
    

 




}
