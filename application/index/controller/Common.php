<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\admin\model\Conf;

class Common extends Controller
{
    
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        //当前位置
        if(input('cate_id')){
            $this->getPosition(input('cate_id'));
        }
        if(input('artid')){
            $articles=db('article')->field('cate_id')->find(input('artid'));
            $cate_id=$articles['cate_id'];
            $this->getPosition($cate_id);
        }
       

        //获取网站配置信息
        $_config = $this -> getConf() ;

            $config=array();
            foreach ($_config as $k => $v) {
                $config[$v['enname']]=$v['value'];
            }

        $this->assign('config',$config);    
        //dump($config);die;
         //获取当前请求对象
        $request = Request::instance();
        //查询一下当前网站开关状态
        $this -> getStatus($request, $config);
        $this->getNavCates();
         //底部导航
        $cateMolde= new \app\index\model\Cate();
        $recBottom=$cateMolde->getRecBottom();
        $this->assign('recBottom',$recBottom);
       


    }

    public function getNavCates(){
        $cateres=db('cate')->where(['pid'=>0])->order('cate_order desc')->select();

        foreach ($cateres as $k => $v){
            $childres=db('cate')->where(array('pid'=>$v['cate_id']))->order('cate_order asc')->select();
            //dump($childres);die;
            if ($childres) {
                $cateres[$k]['childres']=$childres;
            }else{
                $cateres[$k]['childres']=0;
            }
        }
        //dump($cateress);die;
        $this->assign(['cateres'=>$cateres]);
    }



    public function getPosition($cate_id){
        $cate= new \app\index\model\Cate();
        $posArr=$cate->getparents($cate_id);
        $this->assign('posArr',$posArr);
    }












    //获取配置信息
    public function getConf()
    {
        return Conf::all();

    }

    //判断当前网站前台的开启状态
    //$request请求对象,$config当前配置信息
    public function getStatus($request, $config)
    {
        //当前模板是不是admin
        if ($request -> module() !== 'admin') {

            //根据当前配置表中的is_close值来进行判断,如果为1关闭,0则开启,0是默认,我们什么都不做，就是开启
            if ($config ['close'] == '是') {
                $this -> error('网站前台已关闭');
                exit();
            }

        }
    }













    

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
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
