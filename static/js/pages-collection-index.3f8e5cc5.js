(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-collection-index"],{"196c":function(t,n,e){t.exports=e.p+"static/img/image.689b6096.png"},"1de5":function(t,n,e){"use strict";t.exports=function(t,n){return n||(n={}),t=t&&t.__esModule?t.default:t,"string"!==typeof t?t:(/^['"].*['"]$/.test(t)&&(t=t.slice(1,-1)),n.hash&&(t+=n.hash),/["'() \t\n]/.test(t)||n.needQuotes?'"'.concat(t.replace(/"/g,'\\"').replace(/\n/g,"\\n"),'"'):t)}},2911:function(t,n,e){"use strict";e.r(n);var i=e("d6f1"),c=e("90f2");for(var o in c)"default"!==o&&function(t){e.d(n,t,(function(){return c[t]}))}(o);e("4e5e");var r,a=e("f0c5"),l=Object(a["a"])(c["default"],i["b"],i["c"],!1,null,"056497e5",null,!1,i["a"],r);n["default"]=l.exports},"4d9b":function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var i={data:function(){return{link:function(){uni.navigateTo({url:"../index/detail?id=10001&type=1"})}}},onLoad:function(){this.getList()},methods:{getList:function(){var t=this;console.log("asdas",uni.getStorageSync("userinfo")),this.$request("post","order/myColl",{userid:uni.getStorageSync("userinfo").userId}).then((function(t){console.log("res",t)})).catch((function(n){console.log("err",n),t.$showToast(n.message)}))}}};n.default=i},"4e5e":function(t,n,e){"use strict";var i=e("52ea"),c=e.n(i);c.a},"52ea":function(t,n,e){var i=e("fbd0");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var c=e("4f06").default;c("2c32dc06",i,!0,{sourceMap:!1,shadowMode:!1})},"876c":function(t,n,e){t.exports=e.p+"static/img/image02.8937fea3.png"},"90f2":function(t,n,e){"use strict";e.r(n);var i=e("4d9b"),c=e.n(i);for(var o in i)"default"!==o&&function(t){e.d(n,t,(function(){return i[t]}))}(o);n["default"]=c.a},d6f1:function(t,n,e){"use strict";var i;e.d(n,"b",(function(){return c})),e.d(n,"c",(function(){return o})),e.d(n,"a",(function(){return i}));var c=function(){var t=this,n=t.$createElement,i=t._self._c||n;return i("v-uni-view",{staticClass:"collection"},[i("v-uni-view",{staticClass:"collection-pic"},t._l(3,(function(n){return i("v-uni-view",{staticClass:"collection-pic-item",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.link.apply(void 0,arguments)}}},[i("v-uni-image",{staticClass:"collection-pic-img",attrs:{src:e("196c"),mode:""}}),i("v-uni-view",{staticClass:"collection-pic-text"},[i("v-uni-view",{staticClass:"titleTop"},[t._v("亚运在中国-北京1990")]),i("v-uni-view",{staticClass:"titleTtime"},[t._v("2022-4-7")])],1)],1)})),1)],1)},o=[]},fbd0:function(t,n,e){var i=e("24fb"),c=e("1de5"),o=e("876c");n=i(!1);var r=c(o);n.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-056497e5]{background-color:#0c0d0d}.collection[data-v-056497e5]{padding:0 %?30?%}.collection .collection-pic[data-v-056497e5]{width:100%}.collection .collection-pic .collection-pic-item[data-v-056497e5]{margin-bottom:%?30?%}.collection .collection-pic .collection-pic-item .collection-pic-img[data-v-056497e5]{width:100%;height:%?650?%}.collection .collection-pic .collection-pic-item .collection-pic-text[data-v-056497e5]{position:relative;box-sizing:border-box;width:100%;margin-top:%?-60?%;font-size:%?45?%;color:#fff;padding:%?60?% %?30?% %?40?%;background:url('+r+") no-repeat;background-size:100% 100%}.collection .collection-pic .collection-pic-item .collection-pic-text .titleTop[data-v-056497e5]{font-weight:700}.collection .collection-pic .collection-pic-item .collection-pic-text .titleTtime[data-v-056497e5]{font-size:%?30?%}body.?%PAGE?%[data-v-056497e5]{background-color:#0c0d0d}",""]),t.exports=n}}]);