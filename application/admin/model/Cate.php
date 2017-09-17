<?php

namespace app\admin\model;

use think\Collection;

use think\Model;
use traits\model\SoftDelete;

class Cate extends Model
{
    //引用软删除方法集
    use SoftDelete;

    
    protected $insert = [
        'cate_order' => 0
    ];

    //定义表中的删除时间字段,来记录删除时间
    protected $deleteTime = 'delete_time';

    public function article()
    {
        return $this->hasMany('article');
    }



















    public static function getCate($pid=0, &$result=[], $blank=-1)
    {
        //1.分类表查询:$pid
        $res = self::all(['pid'=>$pid]);
//dump($res);die;
        //2.自定义分类名称前面的提示信息
        $blank += 1;

        //3.遍历分类表
        foreach ($res as $key => $value) {

            //3-1自定义分类名称的显示格式
            $cate_name = $value->cate_name;
            $value->cate_name = str_repeat('—|',$blank).$cate_name;

            //3-2将查询到的当前记录保存到结果$result中
            $result[] = $value;
           

            //3-3关键:将当前记录的id，做为下一级分类的父id,$pid，继续递归调用本方法
            self::getCate($value->cate_id, $result, $blank);
        }
//dump($result);die;
        //4.全部分类 含自己 返回查询结果,调用结果集类make方法打包当前结果,转为二维数组返回
        return Collection::make($result)->toArray();
        //$arr = Collection::make($result)->toArray();//
//dump($arr);die;
       
        
    }
////////////////////////////////////////////////////////////////////////////////////////////////     
    
    public function catetree(){
        $cateres=$this->order('sort desc')->select();  //->paginate(10)
        //dump($cateres);die;
        return $this->sort($cateres);

    }
   
//********* 
    public function sort($data,$pid=0,$level=0){
        static $arr=array();
        foreach($data as $k => $v){
            if($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                $this->sort($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }

//****************************************************************
    
    public function getchildrenid($cate_id){
        $cateres=$this->select();
//dump($cateres);die;       
        return $this->_getchildrenid($cateres,$cate_id);
    }

//***********
    public function _getchildrenid($cateres,$cate_id){
        static $sonids=array();
        foreach($cateres as $k => $v){
            if($v['pid'] == $cate_id){
                $sonids[]=$v['cate_id'];
                $this->_getchildrenid($cateres,$v['cate_id']);
            }
        }
       return $sonids;
    }

//////////////////////////////////////////////////////////////////////////




    public static function getSonCate($cate_id,&$result=[])
    {
        if($res = self::all(['pid'=>$cate_id,'is_delete'=>0])){
             foreach ($res as $key => $value) {
                 $result[] = $value;
             }
            self::getSonCate($value->cate_id, $result);

            return Collection::make($result)->toArray();
        }else{
            return false;
        }
    }

    // public function getTypeAttr($value)
    // {
    //     $type = [
    //        1=>'文章列表',
    //        2=>'单页',
    //        3=>'图片图文'
    //     ];
    //     return $type[$value];
    // }

    // public function setTypeAttr($value)
    // {
    //     $type = [
    //       '列表'=>1,
    //       '单页'=>2,
    //       '图文'=>3
    //     ];
    //     return $type[$value];
    // }








}
