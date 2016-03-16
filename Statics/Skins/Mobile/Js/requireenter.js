/**
 * Created by jiayin on 2015/12/31.
 */
requirejs.config({
    baseUrl: '/Statics/Skins/Mobile/Js/lib',
    paths: {
        app: '/Statics/Skins/Mobile/Js/app'
    }
});
requirejs([], function () {
    var require = document.getElementById('require');
    var appJs = require.getAttribute('app');
    requirejs(['app/'+appJs]);
});
