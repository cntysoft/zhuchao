/*
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 前台用户中心用户登录相关的API
 */
define(['jquery', 'Core', 'layer'], function (){
    (function (window, undefined){
        var _emptyFn = function (){
        };
        var _FrontNs_ = Cntysoft.Front;
        $.extend(_FrontNs_, {
            FRONT_API_GATEWAY : "/front-api-entry",
            REQUEST_META_KEY : "REQUEST_META",
            REQUEST_DATA_KEY : "REQUEST_DATA",
            REQUEST_SECURITY_KEY : "REQUEST_SECURITY",
            callApi : function (name, method, params, callback, load, scope)
            {
                var layerIndex;
                if(load){
                    layerIndex = layer.load();
                }
                callback = callback || Cntysoft.emptyFn;
                scope = scope || Cntysoft.Front;
                var requestObject = {};
                var errorCallback = function (jqXHR, textStatus, errorThrown)
                {
                    //@TODO 是否要加入code参数
                    callback.call(scope, {
                        status : false,
                        msg : textStatus
                    });
                };
                var successCallback = function (data, textStatus, jqXHR)
                {
                    //@TODO 是否要加入code参数
                    callback.call(scope, data);
                    layerIndex ? layer.close(layerIndex) : '';
                };
                requestObject[this.REQUEST_META_KEY] = Cntysoft.Json.encode({
                    cls : name,
                    method : method
                });
                requestObject[this.REQUEST_DATA_KEY] = Cntysoft.Json.encode(params);
                requestObject[this.REQUEST_SECURITY_KEY] = Cntysoft.Json.encode({});

                var ajaxOpt = {
                    dataType : 'json',
                    type : 'POST',
                    data : requestObject,
                    async : true,
                    error : errorCallback,
                    success : successCallback
                };
                $.ajax(this.FRONT_API_GATEWAY, ajaxOpt);
            }
        });
    })(window);
});