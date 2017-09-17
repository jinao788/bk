<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Article extends Model
{

	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $auto = [
        'delete_time' => NULL,
			];

	protected $insert = [
       'status'=>1,
      ];

	protected $autoWriteTimestamp = true; //自动写入
    // 创建时间字段
    protected $createTime = 'create_time';
	protected $updateTime = 'update_time';
    protected $dateFormat = 'Y年m月d日';

    public function cate()
    {
        return $this->belongsTo('cate');
    }

    public function getRecAttr($value)
    {
        $rec = [
            0=>'未推荐',
            1=> '已推荐'
        ];
        return $rec[$value];
    }



}
