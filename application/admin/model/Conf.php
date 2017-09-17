<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Conf extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
   

    // public function getTypeAttr($value)
    // {
    //     $type = [
    //         1=>'单行文本',
    //         2=> '多行文本域',
    //         3=> '单选按钮',
    //         4=> '复选框',
    //         5=> '下拉列表',
    //     ];
    //     return $type[$value];
    // }
}
