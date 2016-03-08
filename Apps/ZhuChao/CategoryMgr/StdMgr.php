<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\CategoryMgr;
use Cntysoft\Kernel\App\AbstractLib;
use App\ZhuChao\CategoryMgr\Model\StdCategory as StdCategoryModel;
/**
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class StdMgr extends AbstractLib
{
   const STD_CATE_MODEL_CLS = 'App\ZhuChao\CategoryMgr\Model\StdCategory';

   public function addNode($pid, $name)
   {
      $node = new StdCategoryModel();
      $node->setName($name);
      $node->setPid($pid);

      $node->create();
      return $node->getId();
   }

}