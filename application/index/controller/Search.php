<?php

namespace app\index\controller;
use app\index\model\Article;

use think\Request;

class Search extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $article=new Article();
        $serHot=$article->getHotSer();
       
        $keywords=input('keywords');
        $serRes=db('article')->order('id desc')->where('title','like','%'.$keywords.'%')->paginate(2,false,$config=['query'=>array('keywords'=>$keywords)]);

        //dump($serRes);die;
        $this->assign(array(
            'serRes'=>$serRes,
            'keywords'=>$keywords,
            'serHot'=>$serHot
            ));
        return view('search');
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
