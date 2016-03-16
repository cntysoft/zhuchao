/*
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 采购商管理语言包
 */
Ext.define('App.ZhuChao.Product.Lang.zh_CN', {
   extend : 'Cntysoft.Kernel.AbstractLangHelper',
   data : {
      PM_TEXT : {
         DISPLAY_TEXT : '产品管理',
         ENTRY : {
            WIDGET_TITLE : '欢迎使用产品管理程序',
            TASK_BTN_TEXT : '产品管理'
         }
      },
      ENTRY : {
      },
      UI : {
         PRODUCT : {
            LIST_VIEW : {
               TITLE : '产品列表',
               COLS : {
                  ID : 'ID',
                  NAME : '用户名称',
                  NUMBER : '产品编号',
                  PRICE : '产品价格',
                  GRADE : '评分',
                  INPUT_TIME : '添加时间',
                  STATUS : '状态'
               },
               STATUS : {
                  DRAFT : '草稿',
                  PEEDING : '审核中',
                  VERIFY : '已审核',
                  REJECTION : '已拒绝',
                  SHELF : '已下架',
                  DELETE : '已删除'
               },
               SEX : {
                  MAN : '男',
                  WOMAN : '女',
                  SECRET : '保密'
               },
               TBAR : {
                  ADD : '添加产品',
                  QUERY : '搜索',
                  TIP : '输入关键词搜索'
               },
               MENU : {
                  LOCK : '锁定用户',
                  UNLOCK : '解锁用户',
                  MODIFY : '修改用户信息'
               },
               ERROR_MAP : {
                  "App/ZhuChao/Product/ProductMgr" : {
                     10001 : '选择的产品信息不存在！'
                  }
               }
            },
            EDITOR : {
               TITLE : '产品信息编辑器',
               FIELDS : {
                  DEFAULT_TITLE : '基本参数',
                  BASIC_FORM : {
                     TITLE : '标题',
                     ADVERT : '产品广告语',
                     KEYWORDS : '产品关键词',
                     CATEGORY : '所属分类',
                     STATUS : '产品状态',
                     NORMAL : '正常',
                     SHELF : '下架',
                     COMPANY : '所属企业'
                  },
                  OTHER_FORM : {
                     TITLE : '交易选项',
                     UNIT : '计量单位',
                     UNIT_LIST : {
                        UNIT1 : '件',
                        UNIT2 : '个',
                        UNIT3 : '台',
                        UNIT4 : '套',
                        UNIT5 : '箱',
                        UNIT6 : '吨',
                        UNIT7 : '公斤',
                        UNIT8 : '米',
                        UNIT9 : '平方米',
                        UNIT10 : '立方米',
                        UNIT11 : '千米',
                        UNIT12 : '克',
                        UNIT13 : '千克',
                        UNIT14 : '升',
                        UNIT15 : '毫升',
                        UNIT16 : '张',
                        UNIT17 : '提',
                        UNIT18 : '筐',
                        UNIT19 : '桶',
                        UNIT20 : '片',
                        UNIT21 : '斤',
                        UNIT22 : '根',
                        UNIT23 : '头',
                        UNIT24 : '只',
                        UNIT25 : '毫克',
                        UNIT26 : '微克',
                        UNIT27 : '双',
                        UNIT28 : '支',
                        UNIT29 : '瓶',
                        UNIT30 : '卷',
                        UNIT31 : '份',
                        UNIT32 : '包',
                        UNIT33 : '袋',
                        UNIT34 : '盆',
                        UNIT35 : '棵',
                        UNIT36 : '条',
                        UNIT37 : '瓦',
                        unit38 : '批',
                        UNIT39 : '盒'
                     },
                     MINIMUM : '最小起订量',
                     STOCK : '可售数量',
                     PRICE : '价格',
                     BATCH : '是否批发',
                     BATCH_YES : '是',
                     BATCH_NO : '否'
                  },
                  DETAIL_FORM : {
                     TITLE : '商品图片<span style="color:red">*</span>（最多5张）',
                     UPLOAD_DETAIL_BTN : '上传商品的图片'
                  },
                  DESCRIPTION_FORM : {
                     TITLE : '商品详情<span style="color:red">*</span>'
                  }
               },
               NORMAL_ATTR_PANEL : {
                  TITLE : '产品属性',
                  BTN : {
                     ADD_GROUP : '添加属性分组',
                     ADD_ATTR : '添加属性',
                     DELETE_ATTR : '删除此项属性'
                  }
               },
               MENU : {
                  DETAIL_FORM : {
                     DELETE_IMAGE : '删除选中图片'
                  }
               },
               MSG : {
                  CUSTOM_ATTR_OVER : '最多添加3条自定义属性！',
                  EMPTY_BRAND : '请填写产品品牌，最多10个字',
                  EMPTY_TITLE : '请填写产品名称+型号，最多15个字',
                  EMPTY_DESCRIPTION : '请填写产品特征与具体描述，最多20个字',
                  EMPTY_ADVERT : '请填写产品宣传广告语及促销优惠活动等信息',
                  EMPTY_KEYWORDS : '最多10个字',
                  EMPTY_PRICE : '不填代表面议',
                  IMAGES_OVER : '最多只能上传5张图片！',
                  LOAD_ATTRS : '正在加载属性数据 ... '
               },
               ERROR_MAP : {
                  "App/ZhuChao/Provider/Mgr" : {
                     10012 : '选择的企业信息不存在！'
                  }
               }
            }
         }
      },
      COMP : {
         G_CATEGORY_TREE : {
            TITLE : '产品分类面板',
            ROOT_NODE : '产品分类树'
         },
         CATEGORY_COMBO : {
            SELECT_NODE : '请选择产品分类'
         },
         NORMAL_ATTR_GROUP_WIN : {
            TITLE : '普通属性添加窗口',
            FIELDS : {
               GROUP_NAME : '分组名称'
            }
         },
         NORMAL_ATTR_WINDOW : {
            TITLE : '普通属性添加/修改窗口',
            FIELDS : {
               NAME : '属性名称',
               REQUIRED : '是否必须'
            }
         }
      }
   }
});
