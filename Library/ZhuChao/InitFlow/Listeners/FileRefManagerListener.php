<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ZhuChao\InitFlow\Listeners;
use Cntysoft\Phalcon\Events\ListenerAggregateInterface;

/**
 * 主要是统计文件使用空间的情况
 */
class FileRefManagerListener implements ListenerAggregateInterface
{
    /**
     * @inheritdoc
     */
    public function attach(\Phalcon\Events\ManagerInterface $events)
    {
        
    }
}