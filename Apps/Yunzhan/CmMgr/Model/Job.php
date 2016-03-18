<?php

namespace App\Yunzhan\CmMgr\Model;

use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class Job extends BaseModel
{

    private $id = null;

    private $content = null;

    private $department = null;

    private $number = null;

    private $tel = null;

    private $endTime = null;

    public function getSource()
    {
        return "app_site_cmmgr_u_job";
    }

    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @return \App\Yunzhan\CmMgr\Model\Job
     */
    public function setId($id)
    {
        $this->id = (int)$id;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return \App\Yunzhan\CmMgr\Model\Job
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @return \App\Yunzhan\CmMgr\Model\Job
     */
    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }

    public function getNumber()
    {
        return (int)$this->number;
    }

    /**
     * @return \App\Yunzhan\CmMgr\Model\Job
     */
    public function setNumber($number)
    {
        $this->number = (int)$number;
        return $this;
    }

    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @return \App\Yunzhan\CmMgr\Model\Job
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
        return $this;
    }

    public function getEndTime()
    {
        return (int)$this->endTime;
    }

    /**
     * @return \App\Yunzhan\CmMgr\Model\Job
     */
    public function setEndTime($endTime)
    {
        $this->endTime = (int)$endTime;
        return $this;
    }


}

