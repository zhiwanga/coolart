(function(n){function e(e){for(var t,r,s=e[0],u=e[1],c=e[2],g=0,l=[];g<s.length;g++)r=s[g],Object.prototype.hasOwnProperty.call(o,r)&&o[r]&&l.push(o[r][0]),o[r]=0;for(t in u)Object.prototype.hasOwnProperty.call(u,t)&&(n[t]=u[t]);d&&d(e);while(l.length)l.shift()();return i.push.apply(i,c||[]),a()}function a(){for(var n,e=0;e<i.length;e++){for(var a=i[e],t=!0,r=1;r<a.length;r++){var u=a[r];0!==o[u]&&(t=!1)}t&&(i.splice(e--,1),n=s(s.s=a[0]))}return n}var t={},o={index:0},i=[];function r(n){return s.p+"static/js/"+({"pages-blindBox-index":"pages-blindBox-index","pages-collection-index":"pages-collection-index","pages-fraction-index":"pages-fraction-index","pages-index-detail":"pages-index-detail","pages-index-index":"pages-index-index","pages-invite-index":"pages-invite-index","pages-login-index":"pages-login-index","pages-order-index":"pages-order-index","pages-register-index":"pages-register-index","pages-setting-index":"pages-setting-index","pages-user-about":"pages-user-about","pages-user-feedback":"pages-user-feedback","pages-user-index":"pages-user-index","pages-user-message":"pages-user-message","pages-user-realname":"pages-user-realname","pages-user-userinfo":"pages-user-userinfo"}[n]||n)+"."+{"pages-blindBox-index":"f71f1468","pages-collection-index":"98416fd3","pages-fraction-index":"dc03734a","pages-index-detail":"4d05f14b","pages-index-index":"aa8120fa","pages-invite-index":"401fcf17","pages-login-index":"4ed3ffb1","pages-order-index":"a00fb4aa","pages-register-index":"c803d1d3","pages-setting-index":"23e29bf7","pages-user-about":"8edabbe2","pages-user-feedback":"5e3e5f23","pages-user-index":"d6c31989","pages-user-message":"f962cf22","pages-user-realname":"a689e724","pages-user-userinfo":"80b0016f"}[n]+".js"}function s(e){if(t[e])return t[e].exports;var a=t[e]={i:e,l:!1,exports:{}};return n[e].call(a.exports,a,a.exports,s),a.l=!0,a.exports}s.e=function(n){var e=[],a=o[n];if(0!==a)if(a)e.push(a[2]);else{var t=new Promise((function(e,t){a=o[n]=[e,t]}));e.push(a[2]=t);var i,u=document.createElement("script");u.charset="utf-8",u.timeout=120,s.nc&&u.setAttribute("nonce",s.nc),u.src=r(n);var c=new Error;i=function(e){u.onerror=u.onload=null,clearTimeout(g);var a=o[n];if(0!==a){if(a){var t=e&&("load"===e.type?"missing":e.type),i=e&&e.target&&e.target.src;c.message="Loading chunk "+n+" failed.\n("+t+": "+i+")",c.name="ChunkLoadError",c.type=t,c.request=i,a[1](c)}o[n]=void 0}};var g=setTimeout((function(){i({type:"timeout",target:u})}),12e4);u.onerror=u.onload=i,document.head.appendChild(u)}return Promise.all(e)},s.m=n,s.c=t,s.d=function(n,e,a){s.o(n,e)||Object.defineProperty(n,e,{enumerable:!0,get:a})},s.r=function(n){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(n,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(n,"__esModule",{value:!0})},s.t=function(n,e){if(1&e&&(n=s(n)),8&e)return n;if(4&e&&"object"===typeof n&&n&&n.__esModule)return n;var a=Object.create(null);if(s.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:n}),2&e&&"string"!=typeof n)for(var t in n)s.d(a,t,function(e){return n[e]}.bind(null,t));return a},s.n=function(n){var e=n&&n.__esModule?function(){return n["default"]}:function(){return n};return s.d(e,"a",e),e},s.o=function(n,e){return Object.prototype.hasOwnProperty.call(n,e)},s.p="/",s.oe=function(n){throw console.error(n),n};var u=window["webpackJsonp"]=window["webpackJsonp"]||[],c=u.push.bind(u);u.push=e,u=u.slice();for(var g=0;g<u.length;g++)e(u[g]);var d=c;i.push([0,"chunk-vendors"]),a()})({0:function(n,e,a){n.exports=a("ad9d")},"0c5e":function(n,e,a){"use strict";var t=a("4ea4");a("d3b7"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=t(a("c87c"));function i(n,e){var a=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{},t=!(arguments.length>3&&void 0!==arguments[3])||arguments[3];return new Promise((function(i,r){t&&uni.showLoading({mask:!0,title:"加载中.."}),e=o.default.URL+e,uni.request({url:e,header:{"Access-Token":uni.getStorageSync("userinfo").token||"",platform:"H5"},method:n,data:a,success:function(n){if(200===n.statusCode)0==n.data.status||200==n.data.status?i(n.data):2==n.data.status?(uni.showToast({title:"未登录",icon:"none"}),r(n.data)):1==n.data.status?(uni.showToast({title:n.data.data.message,icon:"none",duration:2e3,position:"bottom"}),r(n.data)):-2==n.data.status||(uni.showToast({title:n.data.data.message,icon:"none",duration:2e3,position:"bottom"}),r(n.data));else{switch(n.statusCode){case 500:uni.showToast({title:"500:服务器内部错误",icon:"none",duration:2e3});break;case 404:uni.showToast({title:"404:网络请求不存在",icon:"none",duration:2e3});break;case 403:uni.showToast({title:"403:网络请求不存在",icon:"none",duration:2e3});break;case 401:uni.showToast({title:"很抱歉，用户信息不存在或已删除",icon:"none",duration:2e3});default:uni.showToast({title:"服务器繁忙，请稍后再试",icon:"none",duration:2e3})}r(n.data)}},fail:function(n){console.log("errs",n),uni.showToast({title:"网络超时"+n.message,icon:"none",duration:2e3,position:"bottom"}),r(n.message)},complete:function(n){console.log("kk",n),401==n.data.status&&(uni.showToast({title:n.data.message,icon:"none",duration:2e3,position:"bottom"}),uni.clearStorageSync(),setTimeout((function(){uni.redirectTo({url:"/pages/login/index"})}),1e3)),setTimeout((function(){uni.hideLoading()}),500)}})}))}var r=function(n,e,a,t){var i=uni.getStorageSync("userinfo").token||"";return console.log(i,"正在请求uploadFile上传文件"),new Promise((function(r,s){uni.uploadFile({url:o.default.URL+n,filePath:e,name:a,formData:t||"",header:{"Access-Token":i},success:function(n){console.log("请求成功的结果",n.statusCode,n),r(n)},fail:function(n){console.log("请求失败的结果",res),s(res)}})}))},s=i;e.default=s,n.exports.sendUploadFile=r},"3cc9":function(n,e,a){"use strict";a("99af"),Object.defineProperty(e,"__esModule",{value:!0}),e.utilDate=e.utilTime=e.showToast=void 0;var t=function(n){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:2e3,a=arguments.length>2?arguments[2]:void 0,t=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"none",o=arguments.length>4&&void 0!==arguments[4]&&arguments[4];uni.showToast({title:n,duration:e,icon:t,success:function(){setTimeout((function(){a&&a()}),e)},mask:o})};e.showToast=t;var o=function(n){var e=new Date(1e3*n),a=e.getFullYear(),t=e.getMonth()+1;t=t<10?"0"+t:t;var o=e.getDate();o=o<10?"0"+o:o;var i=e.getHours();i=i<10?"0"+i:i;var r=e.getMinutes();r=r<10?"0"+r:r;var s=e.getSeconds();s=s<10?"0"+s:s;var u=["星期日","星期一","星期二","星期三","星期四","星期五","星期六"];u[e.getDay()],e.getDay();return"".concat(a,"-").concat(t,"-").concat(o," ").concat(i,":").concat(r,":").concat(s)};e.utilTime=o;var i=function(n){var e=new Date(1e3*n),a=e.getFullYear(),t=e.getMonth()+1;t=t<10?"0"+t:t;var o=e.getDate();o=o<10?"0"+o:o;var i=e.getHours();i=i<10?"0"+i:i;var r=e.getMinutes();r=r<10?"0"+r:r;var s=e.getSeconds();s=s<10?"0"+s:s;var u=["星期日","星期一","星期二","星期三","星期四","星期五","星期六"];u[e.getDay()],e.getDay();return"".concat(a,"-").concat(t,"-").concat(o)};e.utilDate=i},6264:function(n,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var t={onLaunch:function(){console.log("App Launch")},onShow:function(){console.log("App Show")},onHide:function(){console.log("App Hide")}};e.default=t},"6c9a":function(n,e,a){var t=a("24fb");e=t(!1),e.push([n.i,"\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n/*每个页面公共css */uni-page-body{background:#33383a}body.?%PAGE?%{background:#33383a}",""]),n.exports=e},9257:function(n,e,a){"use strict";a.r(e);var t=a("6264"),o=a.n(t);for(var i in t)"default"!==i&&function(n){a.d(e,n,(function(){return t[n]}))}(i);e["default"]=o.a},9376:function(n,e,a){"use strict";var t;a.d(e,"b",(function(){return o})),a.d(e,"c",(function(){return i})),a.d(e,"a",(function(){return t}));var o=function(){var n=this,e=n.$createElement,a=n._self._c||e;return a("App",{attrs:{keepAliveInclude:n.keepAliveInclude}})},i=[]},"99ee":function(n,e,a){"use strict";(function(n){var e=a("4ea4");a("13d5"),a("d3b7"),a("ac1f"),a("5319"),a("ddb0");var t=e(a("e143")),o={keys:function(){return[]}};n["____ECB30B6____"]=!0,delete n["____ECB30B6____"],n.__uniConfig={globalStyle:{navigationBarTextStyle:"black",navigationBarTitleText:"uni-app",navigationBarBackgroundColor:"#F8F8F8",backgroundColor:"#F8F8F8"},tabBar:{color:"#656565",selectedColor:"#fff",backgroundColor:"#000",borderStyle:"black",fontSize:"14px",list:[{pagePath:"pages/index/index",text:"首页",iconPath:"./static/logo_01.png",selectedIconPath:"./static/logo_01_active.png",redDot:!1,badge:""},{pagePath:"pages/blindBox/index",text:"盲盒",iconPath:"./static/logo_02.png",selectedIconPath:"./static/logo_02_active.png",redDot:!1,badge:""},{pagePath:"pages/user/index",text:"个人中心",iconPath:"./static/logo_03.png",selectedIconPath:"./static/logo_03_active.png",redDot:!1,badge:""}]}},n.__uniConfig.compilerVersion="3.3.5",n.__uniConfig.router={mode:"hash",base:"/"},n.__uniConfig.publicPath="/",n.__uniConfig["async"]={loading:"AsyncLoading",error:"AsyncError",delay:200,timeout:6e4},n.__uniConfig.debug=!1,n.__uniConfig.networkTimeout={request:6e4,connectSocket:6e4,uploadFile:6e4,downloadFile:6e4},n.__uniConfig.sdkConfigs={},n.__uniConfig.qqMapKey=void 0,n.__uniConfig.googleMapKey=void 0,n.__uniConfig.locale="",n.__uniConfig.fallbackLocale=void 0,n.__uniConfig.locales=o.keys().reduce((function(n,e){var a=e.replace(/\.\/(uni-app.)?(.*).json/,"$2"),t=o(e);return Object.assign(n[a]||(n[a]={}),t.common||t),n}),{}),n.__uniConfig.nvue={"flex-direction":"column"},n.__uniConfig.__webpack_chunk_load__=a.e,t.default.component("pages-index-index",(function(n){var e={component:a.e("pages-index-index").then(function(){return n(a("ae96"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-login-index",(function(n){var e={component:a.e("pages-login-index").then(function(){return n(a("f22f"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-register-index",(function(n){var e={component:a.e("pages-register-index").then(function(){return n(a("66c4"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-index-detail",(function(n){var e={component:a.e("pages-index-detail").then(function(){return n(a("16f4"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-blindBox-index",(function(n){var e={component:a.e("pages-blindBox-index").then(function(){return n(a("fb6b"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-user-index",(function(n){var e={component:a.e("pages-user-index").then(function(){return n(a("3066"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-order-index",(function(n){var e={component:a.e("pages-order-index").then(function(){return n(a("0277"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-setting-index",(function(n){var e={component:a.e("pages-setting-index").then(function(){return n(a("e63e"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-user-userinfo",(function(n){var e={component:a.e("pages-user-userinfo").then(function(){return n(a("77be"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-user-feedback",(function(n){var e={component:a.e("pages-user-feedback").then(function(){return n(a("bbba"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-user-about",(function(n){var e={component:a.e("pages-user-about").then(function(){return n(a("b28e"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-user-realname",(function(n){var e={component:a.e("pages-user-realname").then(function(){return n(a("6327"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-collection-index",(function(n){var e={component:a.e("pages-collection-index").then(function(){return n(a("2911"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-fraction-index",(function(n){var e={component:a.e("pages-fraction-index").then(function(){return n(a("0fa3"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-invite-index",(function(n){var e={component:a.e("pages-invite-index").then(function(){return n(a("73f1"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),t.default.component("pages-user-message",(function(n){var e={component:a.e("pages-user-message").then(function(){return n(a("02e9"))}.bind(null,a)).catch(a.oe),delay:__uniConfig["async"].delay,timeout:__uniConfig["async"].timeout};return __uniConfig["async"]["loading"]&&(e.loading={name:"SystemAsyncLoading",render:function(n){return n(__uniConfig["async"]["loading"])}}),__uniConfig["async"]["error"]&&(e.error={name:"SystemAsyncError",render:function(n){return n(__uniConfig["async"]["error"])}}),e})),n.__uniRoutes=[{path:"/",alias:"/pages/index/index",component:{render:function(n){return n("Page",{props:Object.assign({isQuit:!0,isEntry:!0,isTabBar:!0,tabBarIndex:0},__uniConfig.globalStyle,{navigationBarTitleText:"uni-app",titleNView:!1})},[n("pages-index-index",{slot:"page"})])}},meta:{id:1,name:"pages-index-index",isNVue:!1,maxWidth:0,pagePath:"pages/index/index",isQuit:!0,isEntry:!0,isTabBar:!0,tabBarIndex:0,windowTop:0}},{path:"/pages/login/index",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"登录",titleNView:!1})},[n("pages-login-index",{slot:"page"})])}},meta:{name:"pages-login-index",isNVue:!1,maxWidth:0,pagePath:"pages/login/index",windowTop:0}},{path:"/pages/register/index",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"注册",titleNView:!1})},[n("pages-register-index",{slot:"page"})])}},meta:{name:"pages-register-index",isNVue:!1,maxWidth:0,pagePath:"pages/register/index",windowTop:0}},{path:"/pages/index/detail",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-index-detail",{slot:"page"})])}},meta:{name:"pages-index-detail",isNVue:!1,maxWidth:0,pagePath:"pages/index/detail",windowTop:44}},{path:"/pages/blindBox/index",component:{render:function(n){return n("Page",{props:Object.assign({isQuit:!0,isTabBar:!0,tabBarIndex:1},__uniConfig.globalStyle,{navigationBarTitleText:"uni-app",titleNView:!1})},[n("pages-blindBox-index",{slot:"page"})])}},meta:{id:2,name:"pages-blindBox-index",isNVue:!1,maxWidth:0,pagePath:"pages/blindBox/index",isQuit:!0,isTabBar:!0,tabBarIndex:1,windowTop:0}},{path:"/pages/user/index",component:{render:function(n){return n("Page",{props:Object.assign({isQuit:!0,isTabBar:!0,tabBarIndex:2},__uniConfig.globalStyle,{navigationBarTitleText:"uni-app",titleNView:!1})},[n("pages-user-index",{slot:"page"})])}},meta:{id:3,name:"pages-user-index",isNVue:!1,maxWidth:0,pagePath:"pages/user/index",isQuit:!0,isTabBar:!0,tabBarIndex:2,windowTop:0}},{path:"/pages/order/index",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"我的订单",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-order-index",{slot:"page"})])}},meta:{name:"pages-order-index",isNVue:!1,maxWidth:0,pagePath:"pages/order/index",windowTop:44}},{path:"/pages/setting/index",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"设置",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-setting-index",{slot:"page"})])}},meta:{name:"pages-setting-index",isNVue:!1,maxWidth:0,pagePath:"pages/setting/index",windowTop:44}},{path:"/pages/user/userinfo",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"个人信息",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-user-userinfo",{slot:"page"})])}},meta:{name:"pages-user-userinfo",isNVue:!1,maxWidth:0,pagePath:"pages/user/userinfo",windowTop:44}},{path:"/pages/user/feedback",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"意见反馈",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-user-feedback",{slot:"page"})])}},meta:{name:"pages-user-feedback",isNVue:!1,maxWidth:0,pagePath:"pages/user/feedback",windowTop:44}},{path:"/pages/user/about",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"关于我们",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-user-about",{slot:"page"})])}},meta:{name:"pages-user-about",isNVue:!1,maxWidth:0,pagePath:"pages/user/about",windowTop:44}},{path:"/pages/user/realname",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"实名认证",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-user-realname",{slot:"page"})])}},meta:{name:"pages-user-realname",isNVue:!1,maxWidth:0,pagePath:"pages/user/realname",windowTop:44}},{path:"/pages/collection/index",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"我的收藏",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-collection-index",{slot:"page"})])}},meta:{name:"pages-collection-index",isNVue:!1,maxWidth:0,pagePath:"pages/collection/index",windowTop:44}},{path:"/pages/fraction/index",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"我的藏分",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-fraction-index",{slot:"page"})])}},meta:{name:"pages-fraction-index",isNVue:!1,maxWidth:0,pagePath:"pages/fraction/index",windowTop:44}},{path:"/pages/invite/index",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"邀请好友",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-invite-index",{slot:"page"})])}},meta:{name:"pages-invite-index",isNVue:!1,maxWidth:0,pagePath:"pages/invite/index",windowTop:44}},{path:"/pages/user/message",component:{render:function(n){return n("Page",{props:Object.assign({},__uniConfig.globalStyle,{navigationBarTitleText:"我的客服",navigationBarBackgroundColor:"#0C0D0D",navigationBarTextStyle:"white"})},[n("pages-user-message",{slot:"page"})])}},meta:{name:"pages-user-message",isNVue:!1,maxWidth:0,pagePath:"pages/user/message",windowTop:44}},{path:"/preview-image",component:{render:function(n){return n("Page",{props:{navigationStyle:"custom"}},[n("system-preview-image",{slot:"page"})])}},meta:{name:"preview-image",pagePath:"/preview-image"}},{path:"/choose-location",component:{render:function(n){return n("Page",{props:{navigationStyle:"custom"}},[n("system-choose-location",{slot:"page"})])}},meta:{name:"choose-location",pagePath:"/choose-location"}},{path:"/open-location",component:{render:function(n){return n("Page",{props:{navigationStyle:"custom"}},[n("system-open-location",{slot:"page"})])}},meta:{name:"open-location",pagePath:"/open-location"}}],n.UniApp&&new n.UniApp}).call(this,a("c8ba"))},a897:function(n,e,a){"use strict";a.r(e);var t=a("9376"),o=a("9257");for(var i in o)"default"!==i&&function(n){a.d(e,n,(function(){return o[n]}))}(i);a("cb50");var r,s=a("f0c5"),u=Object(s["a"])(o["default"],t["b"],t["c"],!1,null,null,null,!1,t["a"],r);e["default"]=u.exports},ad9d:function(n,e,a){"use strict";var t=a("4ea4"),o=t(a("5530"));a("e260"),a("e6cf"),a("cca6"),a("a79d"),a("99ee"),a("1c31");var i=t(a("a897")),r=t(a("0c5e")),s=t(a("c87c")),u=a("3cc9"),c=t(a("d6b5")),g=t(a("e143"));g.default.use(c.default),g.default.prototype.$request=r.default,g.default.prototype.$sendUploadFile=r.default,g.default.prototype.$config=s.default,g.default.prototype.$showToast=u.showToast,g.default.prototype.$utilTime=u.utilTime,g.default.prototype.$utilDate=u.utilDate,g.default.config.productionTip=!1,i.default.mpType="app";var d=new g.default((0,o.default)({},i.default));d.$mount()},bc14:function(n,e,a){var t=a("6c9a");"string"===typeof t&&(t=[[n.i,t,""]]),t.locals&&(n.exports=t.locals);var o=a("4f06").default;o("1ec5b9cb",t,!0,{sourceMap:!1,shadowMode:!1})},c87c:function(n,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var t="https://shucang.zhongyuansc.net/index.php?s=/api/",o="https://shucang.zhongyuansc.net/uploads/",i={URL:t,ImgUrl:o};e.default=i},cb50:function(n,e,a){"use strict";var t=a("bc14"),o=a.n(t);o.a}});