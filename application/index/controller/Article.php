<?php

namespace app\index\controller;


use think\Request;
use app\index\controller\Common;

class Article extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
       
       $artid=input('artid');

        db('article')->where(array('id'=>$artid))->setInc('click');
        $articles=db('article')->find($artid);
        $article = new \app\index\model\Article();
        $hotRes=$article ->getHotArticles($articles['cate_id']);

        $cate=new \app\index\model\Cate();
        $cateInfo=$cate->getCateInfo($articles['cate_id']);
        //猜你喜欢
        $cate=db('cate')->find($articles['cate_id']);
        $guessArtRes=db('article')->where(array('cate_id'=>$cate['cate_id']))->limit(6)->select();
        
        $this->assign(array(
            'articles'=>$articles,
            'hotRes'=>$hotRes,
            'guessArtRes'=> $guessArtRes,
            'cateInfo'=>$cateInfo
            ));
        return view('article');
    
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
