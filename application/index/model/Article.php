<?php

namespace app\index\model;

use think\Model;
use app\index\model\Cate;

class Article extends Model
{
    public function getAllArticles($cate_id){
        $cate=new Cate();
        $allCateId=$cate->getchildrenid($cate_id);
        $artRes=db('article')->where("cate_id IN($allCateId)")->order('id desc')->paginate(9);
      //  $artRes1=db('article')->where("cateid IN($allCateId)")->order('id desc')->select();
      //dump($artRes);die;
        return $artRes;
    }


    public function getHotArticles($cate_id){
        $cate=new Cate();
        $allCateId=$cate->getchildrenid($cate_id);
        $artRes=db('article')->where("cate_id IN($allCateId)")->order('click desc')->limit(5)->select();
        //dump($artRes);die;
        return $artRes;
    }


    public function getHotSer(){
        $artRes=db('article')->order('click desc')->limit(5)->select();
        return $artRes;
    }


    public function getNewArticle(){
        $newArticleRes=db('article')->field('a.id,a.title,a.desc,a.thumb,a.click,a.create_time,c.cate_name')->alias('a')->join('cate c','a.cate_id=c.cate_id')->order('a.id desc')->limit(10)->select();

        return $newArticleRes;
    }

    public function getSiteHot(){
        $siteHotArt=$this->field('id,title,thumb')->order('click desc')->limit(5)->select();
        return $siteHotArt;
    }

    public function getRecArt(){
        $recArt=$this->where('rec','=','1')->field('id,thumb,title')->order('id desc')->limit(4)->select();
        return $recArt;
    }

    public function frontImgR(){
        $frontImgR=$this->where('id','=','1')->select();
        return $frontImgR;
    }





















    
}
