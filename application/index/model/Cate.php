<?php

namespace app\index\model;

use think\Model;

class Cate extends Model
{
    public function getchildrenid($cate_id){
        $cateres=$this->select();
        $arr=$this->_getchildrenid($cateres,$cate_id);
        $arr[]=$cate_id;
        $strId=implode(',',$arr);
        return $strId;
    }


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



     public function getparents($cate_id){
        $cateres=$this->field('cate_id,pid,cate_name')->select();
        $cates=db('cate')->field('cate_id,pid,cate_name')->find($cate_id);
        $pid=$cates['pid'];
        if($pid){
            $arr=$this->_getparentsid($cateres,$pid);
        }
        $arr[]=$cates;
        return $arr;
    }

    public function _getparentsid($cateres,$pid){
        static $arr=array();
        foreach ($cateres as $k => $v) {
            if($v['cate_id'] == $pid){
                $arr[]=$v;
                $this->_getparentsid($cateres,$v['pid']);
            }
        }
        return $arr;
    }

    public function getRecIndex(){
        $recIndex=$this->order('cate_id asc')->where('rec_index','=','1')->select();
        return $recIndex;
    }
    
    public function getRecBottom(){
        $recBottom=$this->order('cate_id asc')->where('rec_bottom','=','1')->select();
        return $recBottom;
    }

    public function getCateInfo($cate_id){
        $cateInfo=$this->field('cate_name,keywords,desc')->find($cate_id);
        return $cateInfo;
    }








}
