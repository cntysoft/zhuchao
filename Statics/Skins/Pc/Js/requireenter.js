/**
 * Created by jiayin on 2015/12/31.
 */
requirejs.config({
    baseUrl : '/Statics/Skins/Pc/Js/lib',
    paths : {
        app : '/Statics/Skins/Pc/Js/app',
        'kindEditor' : 'kindeditor-all',
    },
    shim : {
        'zh_CN' : ['kindEditor']
    }
});
requirejs([], function (){
    var require = document.getElementById('require'),
    appJs = require.getAttribute('app');
    requirejs(['app/' + appJs]);
});
