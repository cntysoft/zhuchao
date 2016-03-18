<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Yunzhan\Content\Saver;
use App\Yunzhan\CmMgr\Model\General as GeneralModel;
use App\Yunzhan\CmMgr\Model\Content as CModel;

/**
 * 标准内容模型保存器接口
 */
interface SaverInterface
{
   /**
    * 读取子模型的信息
    *
    * @param \App\Yunzhan\CmMgr\Model\General $gmodel 基本模型数据模型对象
    * @param \App\Yunzhan\CmMgr\Model\Content $cmodel
    * @return \Phalcon\Mvc\Model
    */
   public function read(GeneralModel $gmodel, CModel $cmodel);
   /**
    * 保存内容模型自己的字段信息
    *
    * @param array $gData 基本模型数据
    * @param array $data 特定模型数据
    * @param \App\Yunzhan\CmMgr\Model\Content $cmodel
    */
   public function add(array $gData, array $data, CModel $cmodel);
   /**
    * 修改子模型数据
    *
    * @param \App\Yunzhan\CmMgr\Model\General $gmodel 基本模型数据模型对象
    * @param array $data 特定模型数据
    * @param \App\Yunzhan\CmMgr\Model\Content $cmodel
    */
   public function update(GeneralModel $gmodel, array $data, CModel $cmodel);
   /**
    * 删除子模型数据
    *
    * @param \App\Yunzhan\CmMgr\Model\General $gmodel 基本模型数据模型对象
    * @param \App\Yunzhan\CmMgr\Model\Content $cmodel
    */
   public function delete(GeneralModel $gmodel, CModel $cmodel);
}