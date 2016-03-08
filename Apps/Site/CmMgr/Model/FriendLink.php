<?php

namespace App\Site\CmMgr\Model;

use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class FriendLink extends BaseModel
{

    private $id = null;

    private $linkType = null;

    private $isRecommend = null;

    private $linkUrl = null;
    
    private $fileRefs = null;
    public function getSource()
    {
        return "app_site_cmmgr_u_friendlink";
    }

    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @return \App\Site\CmMgr\Model\FriendLink
     */
    public function setId($id)
    {
        $this->id = (int)$id;
        return $this;
    }

    public function getLinkType()
    {
        return $this->linkType;
    }

    /**
     * @return \App\Site\CmMgr\Model\FriendLink
     */
    public function setLinkType($linkType)
    {
        $this->linkType = $linkType;
        return $this;
    }

    public function getIsRecommend()
    {
        return (int)$this->isRecommend;
    }

    /**
     * @return \App\Site\CmMgr\Model\FriendLink
     */
    public function setIsRecommend($isRecommend)
    {
        $this->isRecommend = (int)$isRecommend;
        return $this;
    }

    public function getLinkUrl()
    {
        return $this->linkUrl;
    }

    /**
     * @return \App\Site\CmMgr\Model\FriendLink
     */
    public function setLinkUrl($linkUrl)
    {
        $this->linkUrl = $linkUrl;
        return $this;
    }
    
    public function getFileRefs()
    {
        return $this->fileRefs;
    }

    public function setFileRefs($fileRefs)
    {
        $this->fileRefs = $fileRefs;
        return $this;
    }

}

