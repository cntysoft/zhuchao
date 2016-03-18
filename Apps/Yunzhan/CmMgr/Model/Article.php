<?php

namespace App\Yunzhan\CmMgr\Model;

use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class Article extends BaseModel
{

    private $id = null;

    private $content = null;

    private $imgRefMap = null;

    private $fileRefs = null;

    public function getSource()
    {
        return "app_site_cmmgr_u_article";
    }

    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @return \App\Yunzhan\CmMgr\Model\Article
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
     * @return \App\Yunzhan\CmMgr\Model\Article
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getImgRefMap()
    {
        return $this->imgRefMap;
    }

    /**
     * @return \App\Yunzhan\CmMgr\Model\Article
     */
    public function setImgRefMap($imgRefMap)
    {
        $this->imgRefMap = $imgRefMap;
        return $this;
    }

    public function getFileRefs()
    {
        return $this->fileRefs;
    }

    /**
     * @return \App\Yunzhan\CmMgr\Model\Article
     */
    public function setFileRefs($fileRefs)
    {
        $this->fileRefs = $fileRefs;
        return $this;
    }


}

