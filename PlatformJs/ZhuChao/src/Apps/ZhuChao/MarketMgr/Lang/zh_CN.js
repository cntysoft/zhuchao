/*
 * Cntysoft Cloud Software Team
 *
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.ZhuChao.MarketMgr.Lang.zh_CN', {
   extend : 'Cntysoft.Kernel.AbstractLangHelper',
   data : {
      PM_TEXT : {
         DISPLAY_TEXT : '营销管理程序',
         ENTRY : {
            WIDGET_TITLE : '欢迎使用营销管理程序',
            TASK_BTN_TEXT : '营销管理'
         },
         BARGAIN : {
            WIDGET_TITLE : '欢迎使用特卖商品管理程序',
            TASK_BTN_TEXT : '特卖商品管理'
         },
         COUPON : {
            WIDGET_TITLE : '欢迎使用优惠券管理程序',
            TASK_BTN_TEXT : '优惠券管理'
         },
         DISCOUNT : {
            WIDGET_TITLE : '欢迎使用折扣管理程序',
            TASK_BTN_TEXT : '折扣管理'
         },
         ADS : {
            WIDGET_TITLE : '欢迎使用广告位管理程序',
            TASK_BTN_TEXT : '广告位管理'
         },
         COMMENT : {
            WIDGET_TITLE : '欢迎使用商品评论管理程序',
            TASK_BTN_TEXT : '商品评论管理'
         },
         USER_STATISTICS : {
            WIDGET_TITLE : '欢迎使用用户统计程序',
            TASK_BTN_TEXT : '用户统计'
         },
         ORDER_STATISTICS : {
            WIDGET_TITLE : '欢迎使用订单统计功能',
            TASK_BTN_TEXT : '订单统计'
         }
      },
      WIDGETNAME : {
         BARGAIN : '特卖商品管理',
         COUPON : '优惠券管理',
         DISCOUNT : '折扣管理',
         ADS : '广告位管理',
         COMMENT : '商品评论管理',
         USER_STATISTICS : '用户统计',
         ORDER_STATISTICS : '订单统计'
      },
      ADS : {
         DIRECTFORUSE : {
            TITLE : '使用说明',
            HTML : '<h1 style="text-align: center;">欢迎使用</h1><h2>配置项:</h2>' +
            '<p style="font-size: 20px;">广告名称：这个需要填写广告的名称。例如：“双11活动“。<font style="color: red">该项为必填项</font></p>' +
            '<p style="font-size: 20px;">广告网址：这个需要填写广告页面的网址，建议全部用”http://“开头填写绝对网址。<font style="color: red">该项为必填项</font></p>' +
            '<p style="font-size: 20px;">背景颜色：当广告的图片两边需要有颜色的时候在这里填写颜色的六位值即可在前台显示出来,可选可填。</p>' +
            '<p style="font-size: 20px;">开始时间：这个需要填写广告的开始时间，方便自己查看。可以不填写</p>' +
            '<p style="font-size: 20px;">结束时间：这个需要填写广告的结束时间，方便自己查看。可以不填写</p>' +
            '<p style="font-size: 20px;">广告排序：当一个位置有多个广告的时候，就按照这个值排列，1位第一张，2为第二张。<font style="color: red">建议填写</font></p>' +
            '<p style="font-size: 20px;">广告图片：这个需要上传广告的图片，点击上传即可。<font style="color: red">该项为必填项</font></p>'
         },
         ADSEDITOR : {
            TITLE : '广告编辑器',
            ADD : '提交',
            RESET : '取消',
            NAME : '广告名称',
            CONTENTURL : '广告网址',
            BGCOLOR : '背景颜色',
            STARTTIME : '开始时间',
            ENDTIME : '结束时间',
            SORT : '广告排序',
            IMAGE : '广告图片',
            UP : '上传图片',
            NOIMAGE : '您没有上传图片，不能添加。',
            MODIFYSUCCESS : '修改成功',
            ADDSUCCESS : '添加成功'
         },
         ADSLOCATIONTREE : {
            TITLE : '广告位列表',
            ADDADS : '为该广告位添加广告'
         },
         LISTVIEW : {
            TITLE : '广告列表',
            ID : '广告ID',
            NAME : '广告名称',
            URL : '广告网址',
            SORT : '广告排序',
            MODIFY : '修改该广告',
            DELETE : '删除该广告',
            DELETESUCCESS : '删除成功'
         }
      },
      BARGAIN : {
         SEARCH_GOODS : {
            TITLE : '搜索商品列表',
            PANEL_TITLE : '搜索商品列表',
            EMPTY_TEXT : '当前没有商品',
            FIELDS : {
               ID : '商品ID',
               TITLE : '商品名',
               CATEGORY : '商品分类',
               BARGAINED : '是否特卖'
            },
            MENU : {
               ADD_BARGAIN : '加入特卖',
               SEARCH : '搜索',
               BARGAIN : '特卖列表'
            }
         },
         BARGAINLIST : {
            TITLE : '特卖商品列表',
            PANEL_TITLE : '特卖商品列表',
            EMPTY_TEXT : '当前没有特卖商品',
            FIELDS : {
               ID : '编号',
               GOODSID : '商品id',
               NAME : '特卖名',
               STATUS : '状态',
               STARTTIME : '开始时间',
               ENDTIME : '结束时间',
               TOTALNUM : '库存'
            },
            MENU : {
               VIEW : '查看特卖',
               MODIFY : '修改特卖',
               DELETE : '删除特卖',
               ADD : '添加特卖'
            },
            MSG : {
               BEFORE : '未开始',
               ON : '<span style ="color: green">特卖中</span>',
               END : '<span style ="color: red">已结束</span>',
               DELETE_ASK : '您确定要删除特卖 <span style ="color: blue">{0}</span> ?'
            }
         },
         INFO : {
            TITLE : '特卖添加/修改',
            PANEL_TITLE : '特卖添加/修改',
            FIELDS : {
               ID : '编号',
               BARGAINNAME : '特卖名称',
               GOODS : '选择商品',
               BARGAIN_COVER : '特卖封面',
               BARGAIN_COVER_UP : '上传封面',
               STARTTIME : '开始时间',
               ENDTIME : '结束时间',
               VALUE_FORM : {
                  GOODSID : '商品id',
                  GOODSDETAIL : '规格组合',
                  OLDPRICE : '原价',
                  NEWPRICE : '特价',
                  LIMITNUM : '限购',
                  TOTALNUM : '库存'
               }
            },
            MENU : {
               MODIFY : '修改特卖',
               DELETE : '删除特卖'
            },
            MSG : {
               WARN1 : '开始时间必须早于结束时间！',
               WARN2 : '开始时间早于当前时间！',
               WARN3 : '最少选择一件商品！',
               EMPTY_ATTR : '请选择规格组合',
               DELETE_ASK : '您确定要删除特卖商品 <span style ="color: blue">{0}</span> ?',
               SAVE_OK : '添加成功',
               UPDATE_OK : '修改成功'
            }
         },
         SEARCH : {
            TITLE : '搜索商品',
            GOODSNAME : '商品名',
            BTN : {
               SEARCH : '搜索',
               BARGAINLIST : '特卖列表'
            }
         }
      },
      COUPON : {
         INFO : {
            TITLE : '添加/修改优惠券',
            ID : '序号',
            NAME : '优惠券名称',
            VALUE : '面值',
            MINICONSUME : '最低消费',
            STARTTIME : '领取时间',
            OUTTIME : '过期时间',
            NEEDEDLEVEL : '需要等级',
            TOTALNUM : '发放数量',
            LIMITGOODS : '限定商品',
            GOODS : '选择商品',
            SELECTED_GOODS : '已选商品',
            Y : '是',
            N : '否',
            MENU : {
               DELETE : '删除已选商品'
            },
            MSG : {
               WARN1 : '领取时间必须早于过期时间！',
               WARN2 : '至少选择一个能够使用该优惠券的商品！',
               ADD_ASK : '优惠券添加后，在有效期内<span style ="color: red">不可删除</span>，确定添加吗？',
               EMPTY_LEVEL : '请选择需要的等级 '
            }
         },
         LISTVIEW : {
            TITLE : '优惠券列表',
            EMPTYMSG : '暂无数据',
            ID : '序号',
            NAME : '优惠券名称',
            VALUE : '面值',
            MINICONSUME : '最低消费',
            OUTTIME : '过期时间',
            CREATETIME : '领取时间',
            NEEDEDLEVEL : '需要等级',
            TOTALNUM : '发放数量',
            RESTNUM : '剩余数量',
            STATUS : '状态',
            MENU : {
               MODIFY_COUPON : '修改优惠券',
               DELETE_COUPON : '删除优惠券',
               VIEW_COUPON : '查看优惠券',
               ADD_COUPON : '添加优惠券'
            },
            MSG : {
               DELETE_ASK : '是否删除优惠券<span style ="color: red">{0}</span> ?'
            },
            STATUS_WIN : {
               BEFOREGRANT : '未开始',
               GRANTING : '<span style ="color: green">可领取</span>',
               AFTERGRANT : '<span style ="color: red">已过期</span>'
            }
         },
         GOODSLISTVIEW : {
            TITLE : '优惠券商品列表',
            PANEL_TITLE : '优惠券商品列表',
            EMPTY_TEXT : '当前没有商品',
            FIELDS : {
               ID : '商品ID',
               TITLE : '商品名',
               CATEGORY : '商品分类',
               COUPONED : '是否可使用',
               NAME : '商品名',
               EMPTY : '请选择类型',
               IN : '已选',
               CIN : '<span style ="color: green">已选</span>',
               NOTIN : '未选'
            },
            MENU : {
               SEARCH : '搜索',
               INCOUPON : '选择',
               OUTCOUPON : '取消'
            }, MSG : {
               ADDCOUPON : '真的允许使用该优惠券吗？',
               CANCELCOUPON : '真的不允许使用该优惠券吗？'
            }
         }
      },
      DISCOUNT : {
         INFO : {
            TITLE : '添加/修改折扣',
            ID : '序号',
            NAME : '折扣名称',
            DISCOUNT : '折扣',
            GOODS : '选择商品',
            SELECTED_GOODS : '已选商品',
            MENU : {
               DELETE : '删除已选商品'
            }, MSG : {
               WARN : '至少选择一件商品！',
               CHECK_ASK : '该操作会将其他折扣中的商品取消，是否继续？'
            }
         },
         LISTVIEW : {
            TITLE : '折扣列表',
            EMPTYMSG : '暂无数据',
            ID : '序号',
            NAME : '折扣名称',
            DISCOUNT : '折扣',
            STATUS : '状态',
            STATUS_WIN : {
               ON : '<span style ="color: green">开启</span>',
               OFF : '<span style ="color: red">未开启</span>'
            },
            MENU : {
               MODIFY_DISCOUNT : '修改折扣',
               DELETE_DISCOUNT : '删除折扣',
               SETON : '开启活动',
               SETOFF : '结束活动',
               GOODS : '修改商品',
               ADD_DISCOUNT : '添加新折扣'
            }, MSG : {
               DELETE_ASK : '是否删除折扣<span style ="color: red">{0}</span> ?',
               SETON : '是否开启折扣活动？',
               SETOFF : '是否结束折扣活动？'
            }
         },
         GOODSLISTVIEW : {
            TITLE : '折扣商品列表',
            PANEL_TITLE : '折扣商品列表',
            EMPTY_TEXT : '当前没有商品',
            FIELDS : {
               SELECT : '选中',
               ID : '商品ID',
               TITLE : '商品名',
               CATEGORY : '商品分类',
               DISCOUNTED : '是否加入',
               NAME : '商品名',
               EMPTY : '请选择类型',
               IN : '已加入',
               CIN : '<span style ="color: green">已加入</span>',
               NOTIN : '未加入',
               INOTHER : '已加入其他折扣'
            },
            MENU : {
               SEARCH : '搜索',
               INDISCOUNT : '加入折扣',
               OUTDISCOUNT : '取消折扣'
            }, MSG : {
               ADDDISCOUNT1 : '该操作会导致其他折扣中的部分商品被取消，是否继续？',
               ADDDISCOUNT2 : '真的要加入折扣商品吗？',
               CANCELDISCOUNT : '真的要取消吗？'
            }
         }
      },
      COMP : {
         COUPONGOODSTREE : {
            ROOT_NAME : '商品'
         },
         DISCOUNTGOODSTREE : {
            ROOT_NAME : '商品'
         },
         BARGAINGOODSTREE : {
            ROOT_NAME : '商品'
         }
      },
      COMMENT : {
         LIST_VIEW : {
            TITLE : '商品评论列表',
            EMPTY_TEXT : '暂时没有评论～',
            STATUS : {
               COMMENT_ALL : '全部评论',
               COMMENT_NOT_VERIFY : '未审核',
               COMMENT_NORMAL : '已审核通过',
               COMMENT_DELETED : '已删除'
            },
            FIELDS : {
               ID : 'ID',
               USER_NAME : '用户名称',
               PRODUCT_NAME : '商品名称',
               CONTENT : '评论内容',
               TIME : '评论时间',
               STATUS : '评论状态'
            },
            BTNS : {
               VIEW : '查看评论',
               VERIFY : '审核评论',
               DELETE : '删除评论',
               REPLY : '回复评论'
            },
            MSG : {
               DELETE : '您确定要删除这条评论吗？',
               SAVING : '正在保存您的设置...'
            }
         },
         INFO : {
            TITLE : '商品评价查看窗口',
            BTNS : {
               CLOSE : '关闭窗口',
               VERIFY : '审核通过',
               DELETE : '删除评论',
               REPLY : '回复评论'
            },
            FIELDS : {
               USER_NAME : '评论用户名称',
               PRODUCT_NAME : '评论商品名称',
               STATUS : '评论状态',
               TIME : '评论时间',
               CONTENT : '评论内容',
               REPLY : '官方回复',
               STAR : '评论评分',
               STARS : '★★★★★☆☆☆☆☆',
               MODIFY : '修改回复',
               ACTION : '操作',
               DELETE : '删除回复'
            },
            STATUS : {
               COMMENT_NOT_VERIFY : '未审核',
               COMMENT_NORMAL : '已审核通过',
               COMMENT_DELETED : '已删除'
            },
            MSG : {
               DELETE : '您确定要删除这条评论吗？',
               SAVING : '正在保存您的设置...',
               DELETE_REPLY : '您确定要删除这条回复吗？'
            }
         },
         REPLY_WIN : {
            TITLE : '评论回复窗口',
            REPLY : '回复',
            BTNS : {
               SAVE : '保存',
               CANCEL : '取消'
            }
         }
      },
      USER_STATISTICS : {
         FIELDS : {
            TIME : '时间起止',
            SEARCH : '搜索',
            NEW_ADD : '新增用户'
         }
      },
      ORDER_STATISTICS : {
         FIELDS : {
            TIME : '时间起止',
            SEARCH : '搜索'
         }
      }
   }
});
