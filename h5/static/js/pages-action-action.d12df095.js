(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-action-action"],{"2f01":function(t,n,e){"use strict";e.r(n);var i=e("5ead"),a=e.n(i);for(var o in i)"default"!==o&&function(t){e.d(n,t,(function(){return i[t]}))}(o);n["default"]=a.a},4905:function(t,n,e){"use strict";var i;e.d(n,"b",(function(){return a})),e.d(n,"c",(function(){return o})),e.d(n,"a",(function(){return i}));var a=function(){var t=this,n=t.$createElement,i=t._self._c||n;return i("v-uni-view",{staticClass:"content"},[i("v-uni-view",{staticClass:"container"},[0==t.list.length?i("v-uni-view",{staticClass:"none"},[i("v-uni-image",{attrs:{src:e("981e"),mode:"widthFix"}})],1):t._e(),0==t.list.length?i("v-uni-view",{staticClass:"cont"},[t._v("暂无公告")]):t._l(t.list,(function(n,e){return i("v-uni-view",{staticClass:"goods-list",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.detail(n.article_id)}}},[i("v-uni-view",{staticClass:"article-item"},[i("v-uni-view",{staticClass:"article-item-top"},[i("v-uni-text",[t._v(t._s(n.title))]),i("v-uni-text",[t._v(">")])],1)],1)],1)}))],2)],1)},o=[]},"5e81":function(t,n,e){"use strict";var i=e("ef5b"),a=e.n(i);a.a},"5ead":function(t,n,e){"use strict";e("c975"),Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var i={data:function(){return{list:[]}},onLoad:function(){this.getArticle()},computed:{changeTime:function(){return function(t){return t.substring(0,t.indexOf(" "))}}},methods:{detail:function(t){uni.navigateTo({url:"/pages/artice/index?articleId="+t})},getArticle:function(){var t=this;this.$request("get","article/list",{categoryId:3}).then((function(n){t.list=n.data.list.data})).catch((function(t){console.log("err",t)}))}}};n.default=i},"981e":function(t,n,e){t.exports=e.p+"static/img/img1.b1f4e8be.png"},a2c6:function(t,n,e){var i=e("24fb");n=i(!1),n.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-002e3918]{background:#101010}.content[data-v-002e3918]{width:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.content .container[data-v-002e3918]{padding:0 %?10?% %?40?%;width:100%}.content .container .none[data-v-002e3918]{width:%?246?%;height:%?246?%;margin:0 auto}.content .container .none uni-image[data-v-002e3918]{width:100%;height:100%}.content .container .cont[data-v-002e3918]{display:block;text-align:center;font-size:16px;color:#fff;margin-top:5px}.content .container .goods-list .article-item[data-v-002e3918]{width:100%;box-sizing:border-box;border-bottom:%?1?% solid #353535;padding:%?40?% %?20?%}.content .container .goods-list .article-item .article-item-top[data-v-002e3918]{width:100%;display:flex;justify-content:space-between;color:#fff}.content .container .goods-list .article-item .article-item-top > uni-text[data-v-002e3918]:first-of-type{width:90%;display:inline-block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:%?28?%}.content .container .goods-list .article-item .article-item-top > uni-text[data-v-002e3918]:last-of-type{display:inline-block;width:%?20?%;height:%?20?%;-webkit-transform:scaleX(.5) scale(1.2);transform:scaleX(.5) scale(1.2)}.content .container .goods-list .article-item .article-item-bottom[data-v-002e3918]{color:#fff;font-size:%?25?%}body.?%PAGE?%[data-v-002e3918]{background:#101010}',""]),t.exports=n},eddf:function(t,n,e){"use strict";e.r(n);var i=e("4905"),a=e("2f01");for(var o in a)"default"!==o&&function(t){e.d(n,t,(function(){return a[t]}))}(o);e("5e81");var r,c=e("f0c5"),s=Object(c["a"])(a["default"],i["b"],i["c"],!1,null,"002e3918",null,!1,i["a"],r);n["default"]=s.exports},ef5b:function(t,n,e){var i=e("a2c6");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=e("4f06").default;a("11e968ac",i,!0,{sourceMap:!1,shadowMode:!1})}}]);