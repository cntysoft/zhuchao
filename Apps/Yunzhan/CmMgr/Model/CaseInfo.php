<?php

namespace App\Yunzhan\CmMgr\Model;

use ZhuChao\Phalcon\Mvc\Model as BaseModel;

class CaseInfo extends BaseModel
{

    private $id = null;

    private $content = null;

    private $fileRefs = null;
    
    public function getSource()
    {
        return "app_site_cmmgr_u_caseinfo";
    }

    public function getId()
    {
       return (int)$this->id;
    }

    public function getContent()
    {
       return unserialize($this->content);
    }

    public function getFileRefs()
    {
       return $this->fileRefs;
    }

    public function setId($id)
    {
       $this->id = (int)$id;
       return $this;
    }

    public function setContent($content)
    {
       $this->content = serialize($content);
       return $this;
    }

    public function setFileRefs($fileRefs)
    {
       $this->fileRefs = $fileRefs;
       return $this;
    }



}

