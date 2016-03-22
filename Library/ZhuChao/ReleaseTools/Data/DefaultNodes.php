<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
return array(
   array(
      'text'              => '首页',
      'identifier'        => 'index',
      'nodeType'          => 1,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'coverTemplateFile' => '首页.phtml'
   ),
   array(
      'text'              => '筑巢商学院',
      'identifier'        => 'zhuchaoschool',
      'nodeType'          => 3,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'listTemplateFile'  => '电商宝典/列表页.phtml',
      'coverTemplateFile' => '电商宝典/首页.phtml',
      'modelsTemplate'    => array(
         1 => '电商宝典/内容页.phtml'
      ),
      'children'          => array(
         array(
            'text'             => '经典案例',
            'identifier'       => 'jingdiananli',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '电商宝典/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '电商宝典/内容页.phtml'
            )
         ),
         array(
            'text'             => '店商资讯',
            'identifier'       => 'dianshangzixun',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '电商宝典/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '电商宝典/内容页.phtml'
            )
         ),
         array(
            'text'             => '课程中心',
            'identifier'       => 'kechengzhongxin',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '电商宝典/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '电商宝典/内容页.phtml'
            ),
            'children'         => array(
               array(
                  'text'             => '营销推广',
                  'identifier'       => 'yingxiaotuiguang',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '电商宝典/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '电商宝典/内容页.phtml'
                  ),
                  'children'         => array(
                     array(
                        'text'             => '社会化营销',
                        'identifier'       => 'shehuihuayingxiao',
                        'nodeType'         => 3,
                        'showOnMenu'       => 1,
                        'showOnListParent' => 0,
                        'listTemplateFile' => '电商宝典/列表页.phtml',
                        'modelsTemplate'   => array(
                           1 => '电商宝典/内容页.phtml'
                        )
                     ),
                     array(
                        'text'             => '活动营销',
                        'identifier'       => 'huodongyingxiao',
                        'nodeType'         => 3,
                        'showOnMenu'       => 1,
                        'showOnListParent' => 0,
                        'listTemplateFile' => '电商宝典/列表页.phtml',
                        'modelsTemplate'   => array(
                           1 => '电商宝典/内容页.phtml'
                        )
                     ),
                     array(
                        'text'             => '品牌管理',
                        'identifier'       => 'pinpaiguanli',
                        'nodeType'         => 3,
                        'showOnMenu'       => 1,
                        'showOnListParent' => 0,
                        'listTemplateFile' => '电商宝典/列表页.phtml',
                        'modelsTemplate'   => array(
                           1 => '电商宝典/内容页.phtml'
                        )
                     ),
                     array(
                        'text'             => 'SEO',
                        'identifier'       => 'yingxiaoseo',
                        'nodeType'         => 3,
                        'showOnMenu'       => 1,
                        'showOnListParent' => 0,
                        'listTemplateFile' => '电商宝典/列表页.phtml',
                        'modelsTemplate'   => array(
                           1 => '电商宝典/内容页.phtml'
                        )
                     )
                  )
               ),
               array(
                  'text'             => '日常管理',
                  'identifier'       => 'richangguanli',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '电商宝典/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '电商宝典/内容页.phtml'
                  ),
                  'children'         => array(
                     array(
                        'text'             => '下架产品',
                        'identifier'       => 'richangxiajiachanpin',
                        'nodeType'         => 3,
                        'showOnMenu'       => 1,
                        'showOnListParent' => 0,
                        'listTemplateFile' => '电商宝典/列表页.phtml',
                        'modelsTemplate'   => array(
                           1 => '电商宝典/内容页.phtml'
                        )
                     ),
                     array(
                        'text'             => '上架产品',
                        'identifier'       => 'richangshangjiachanpin',
                        'nodeType'         => 3,
                        'showOnMenu'       => 1,
                        'showOnListParent' => 0,
                        'listTemplateFile' => '电商宝典/列表页.phtml',
                        'modelsTemplate'   => array(
                           1 => '电商宝典/内容页.phtml'
                        )
                     ),
                     array(
                        'text'             => '店铺设置',
                        'identifier'       => 'dianpushezhi',
                        'nodeType'         => 3,
                        'showOnMenu'       => 1,
                        'showOnListParent' => 0,
                        'listTemplateFile' => '电商宝典/列表页.phtml',
                        'modelsTemplate'   => array(
                           1 => '电商宝典/内容页.phtml'
                        )
                     )
                  )
               ),
               array(
                  'text'             => '基础建设',
                  'identifier'       => 'shangpujichujianshe',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '电商宝典/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '电商宝典/内容页.phtml'
                  ),
                  'children'         => array(
                     array(
                        'text'             => '上架产品',
                        'identifier'       => 'shangjiachanpin',
                        'nodeType'         => 3,
                        'showOnMenu'       => 1,
                        'showOnListParent' => 0,
                        'listTemplateFile' => '电商宝典/列表页.phtml',
                        'modelsTemplate'   => array(
                           1 => '电商宝典/内容页.phtml'
                        )
                     ),
                     array(
                        'text'             => '开通商铺',
                        'identifier'       => 'kaitongdianpu',
                        'nodeType'         => 3,
                        'showOnMenu'       => 1,
                        'showOnListParent' => 0,
                        'listTemplateFile' => '电商宝典/列表页.phtml',
                        'modelsTemplate'   => array(
                           1 => '电商宝典/内容页.phtml'
                        )
                     ),
                     array(
                        'text'             => '注册店铺',
                        'identifier'       => 'zhuceshangpu',
                        'nodeType'         => 3,
                        'showOnMenu'       => 1,
                        'showOnListParent' => 0,
                        'listTemplateFile' => '电商宝典/列表页.phtml',
                        'modelsTemplate'   => array(
                           1 => '电商宝典/内容页.phtml'
                        )
                     )
                  )
               )
            )
         )
      )
   ),
   array(
      'text'              => '老板内参',
      'identifier'        => 'laobanneican',
      'nodeType'          => 3,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'listTemplateFile'  => '老板内参/列表页.phtml',
      'coverTemplateFile' => '老板内参/首页.phtml',
      'modelsTemplate'    => array(
         1 => '老板内参/内容页.phtml'
      ),
      'children'          => array(
         array(
            'text'             => '建材奇闻',
            'identifier'       => 'jiancaiqiwen',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '老板内参/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '老板内参/内容页.phtml'
            )
         ),
         array(
            'text'             => '深度解析',
            'identifier'       => 'shendujiexi',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '老板内参/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '老板内参/内容页.phtml'
            )
         ),
         array(
            'text'             => '重磅推荐',
            'identifier'       => 'zhongbangtuijian',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '老板内参/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '老板内参/内容页.phtml'
            )
         )
      )
   ),
   array(
      'text'              => '帮助中心',
      'identifier'        => 'help',
      'nodeType'          => 3,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'listTemplateFile'  => '帮助中心/列表页.phtml',
      'coverTemplateFile' => '帮助中心/首页.phtml',
      'modelsTemplate'    => array(
         1 => '帮助中心/内容页.phtml'
      ),
      'children'          => array(
         array(
            'text'             => '帐号管理',
            'identifier'       => 'zhanghaoguanli',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '帮助中心/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '帮助中心/内容页.phtml'
            ),
            'children'         => array(
               array(
                  'text'             => '注册',
                  'identifier'       => 'zhuce',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '登录',
                  'identifier'       => 'denglu',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '验证',
                  'identifier'       => 'yanzheng',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '密码',
                  'identifier'       => 'mima',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '会员资料',
                  'identifier'       => 'huiyuanziliao',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               )
            )
         ), array(
            'text'             => '采购中心',
            'identifier'       => 'caigouzhongxin',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '帮助中心/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '帮助中心/内容页.phtml'
            ),
            'children'         => array(
               array(
                  'text'             => '发布信息',
                  'identifier'       => 'fabuxinxi',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '采购报价',
                  'identifier'       => 'caigoubaojia',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '成交管理',
                  'identifier'       => 'chengjiaoguanli',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '评论管理',
                  'identifier'       => 'pinglunguanli',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '我的收藏关注',
                  'identifier'       => 'wodeshoucangguanzhu',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               )
            )
         ), array(
            'text'             => '供应商服务',
            'identifier'       => 'gongyingshangfuwu',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '帮助中心/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '帮助中心/内容页.phtml'
            ),
            'children'         => array(
               array(
                  'text'             => '找买家',
                  'identifier'       => 'zhaomaijia',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '发布信息',
                  'identifier'       => 'gongyingshangfabuxinxi',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '管理产品',
                  'identifier'       => 'guanlichanpiing',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               )
            )
         ), array(
            'text'             => '特色栏目',
            'identifier'       => 'teselanmu',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '帮助中心/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '帮助中心/内容页.phtml'
            ),
            'children'         => array(
               array(
                  'text'             => '产品库',
                  'identifier'       => 'chanpinku',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '企业库',
                  'identifier'       => 'qiyeku',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '老板内参',
                  'identifier'       => 'helplaobanneican',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               )
            )
         ), array(
            'text'             => '积分商城',
            'identifier'       => 'jifenshangcheng',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '帮助中心/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '帮助中心/内容页.phtml'
            ),
            'children'         => array(
               array(
                  'text'             => '热门问题',
                  'identifier'       => 'remenwenti',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '兑换问题',
                  'identifier'       => 'duihuanwenti',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               )
            )
         ), array(
            'text'             => '维权指南',
            'identifier'       => 'weiquanzhinan',
            'nodeType'         => 3,
            'showOnMenu'       => 1,
            'showOnListParent' => 0,
            'listTemplateFile' => '帮助中心/列表页.phtml',
            'modelsTemplate'   => array(
               1 => '帮助中心/内容页.phtml'
            ),
            'children'         => array(
               array(
                  'text'             => '交易诈骗',
                  'identifier'       => 'jiaoyizhapian',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '图片侵权',
                  'identifier'       => 'tupianqinquan',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '知识产权',
                  'identifier'       => 'zhishichanquan',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               ),
               array(
                  'text'             => '违规信息',
                  'identifier'       => 'weiguixinxi',
                  'nodeType'         => 3,
                  'showOnMenu'       => 1,
                  'showOnListParent' => 0,
                  'listTemplateFile' => '帮助中心/列表页.phtml',
                  'modelsTemplate'   => array(
                     1 => '帮助中心/内容页.phtml'
                  )
               )
            )
         )
      )
   )
);
