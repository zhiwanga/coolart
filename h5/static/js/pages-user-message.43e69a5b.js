(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-message"],{3388:function(t,e,n){var i=n("e508");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("3a821349",i,!0,{sourceMap:!1,shadowMode:!1})},"61e1":function(t,e,n){"use strict";(function(t){var i=n("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;i(n("7748"));var a={data:function(){return{userinfo:[{title:"微信",text:""},{title:"QQ",text:""}]}},onShow:function(){this.getContent()},methods:{change:function(t){var e=this;this.$copyText(t).then((function(t){e.$showToast("复制成功")}))},getContent:function(){var e=this;this.$request("post","/Setting/chakeAbout",{}).then((function(n){e.userinfo[0].text=n.data.data[0].wecha,e.userinfo[1].text=n.data.data[0].qq,t("log","res",n," at pages/user/message.vue:56")})).catch((function(e){t("log","err",e," at pages/user/message.vue:58")}))}}};e.default=a}).call(this,n("0de9")["log"])},7132:function(t,e,n){"use strict";n.r(e);var i=n("61e1"),a=n.n(i);for(var r in i)"default"!==r&&function(t){n.d(e,t,(function(){return i[t]}))}(r);e["default"]=a.a},7748:function(t,e,n){"use strict";function i(t){var e;return function(){var n=this,i=arguments;e&&clearTimeout(e),e=setTimeout((function(){t.apply(n,i)}),e)}}function a(t){return/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(t)}function r(t){return/^([\u4e00-\u9fa5]{1,20}|[a-zA-Z\.\s]{1,20})$/.test(t)}function o(t){var e=t.replace(/<img[^>]*>/gi,(function(t,e){return t=t.replace(/style="[^"]+"/gi,"").replace(/style='[^']+'/gi,""),t=t.replace(/width="[^"]+"/gi,"").replace(/width='[^']+'/gi,""),t=t.replace(/height="[^"]+"/gi,"").replace(/height='[^']+'/gi,""),t}));return e=e.replace(/style="[^"]+"/gi,(function(t,e){return t=t.replace(/width:[^;]+;/gi,"max-width:100%;").replace(/width:[^;]+;/gi,"max-width:100%;"),t})),e=e.replace(/<br[^>]*\/>/gi,""),e=e.replace(/\<img/gi,'<img style="max-width:100%;height:auto;display:block;margin-top:0;margin-bottom:0;"'),e}n("ac1f"),n("5319"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var c={debounce:i,checkIdCard:a,checkName:r,formatRichText:o};e.default=c},b2d5:function(t,e,n){"use strict";n.r(e);var i=n("b824"),a=n("7132");for(var r in a)"default"!==r&&function(t){n.d(e,t,(function(){return a[t]}))}(r);n("f504");var o,c=n("f0c5"),s=Object(c["a"])(a["default"],i["b"],i["c"],!1,null,"523a9e34",null,!1,i["a"],o);e["default"]=s.exports},b824:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"userinfo"},[t._l(t.userinfo,(function(e){return n("v-uni-view",{staticClass:"userinfo-item",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.change(e.text)}}},[n("v-uni-view",{staticClass:"icon-left"},[t._v(t._s(e.title))]),n("v-uni-view",{staticClass:"icon-right"},[n("v-uni-view",{staticClass:"icon-text"},[t._v(t._s(e.text))])],1)],1)})),n("v-uni-view",{staticClass:"tip"},[t._v("提示:点击上方号码可复制")])],2)},r=[]},e508:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-523a9e34]{background:#101010}.userinfo[data-v-523a9e34]{padding:0 %?20?%}.userinfo .userinfo-item[data-v-523a9e34]{display:flex;justify-content:space-between;align-items:center;color:#fff;background:#292b2b;border-radius:%?30?%;margin-top:%?25?%;padding:%?30?% %?50?%}.userinfo .login_out[data-v-523a9e34]{width:100%;border-radius:%?30?%;background:#292b2b;color:#c94f58;text-align:center;padding:%?30?% 0;margin-top:%?200?%}.userinfo .icon-left[data-v-523a9e34]{font-size:%?35?%}.userinfo .icon-right[data-v-523a9e34]{display:flex;align-items:center;color:#a6a7a7;font-size:%?35?%}.userinfo .icon-right .icon-text[data-v-523a9e34]{display:flex;align-items:center}.userinfo .icon-right .icon-text .icon-a[data-v-523a9e34]{margin-right:%?10?%}.userinfo .tip[data-v-523a9e34]{color:#f3e0bc;margin-top:%?20?%;margin-left:%?10?%}body.?%PAGE?%[data-v-523a9e34]{background:#101010}',""]),t.exports=e},f504:function(t,e,n){"use strict";var i=n("3388"),a=n.n(i);a.a}}]);