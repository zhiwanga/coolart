(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-collection-index"],{"1de5":function(t,i,e){"use strict";t.exports=function(t,i){return i||(i={}),t=t&&t.__esModule?t.default:t,"string"!==typeof t?t:(/^['"].*['"]$/.test(t)&&(t=t.slice(1,-1)),i.hash&&(t+=i.hash),/["'() \t\n]/.test(t)||i.needQuotes?'"'.concat(t.replace(/"/g,'\\"').replace(/\n/g,"\\n"),'"'):t)}},2911:function(t,i,e){"use strict";e.r(i);var n=e("9225"),c=e("90f2");for(var o in c)"default"!==o&&function(t){e.d(i,t,(function(){return c[t]}))}(o);e("9c6b");var a,s=e("f0c5"),l=Object(s["a"])(c["default"],n["b"],n["c"],!1,null,"2840bd90",null,!1,n["a"],a);i["default"]=l.exports},"4d9b":function(t,i,e){"use strict";Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var n={data:function(){return{arr:[],changeId:"",payType:!1,giveId:""}},onLoad:function(){this.getList()},methods:{getList:function(){var t=this;this.$request("post","order/myColl",{userid:uni.getStorageSync("userinfo").userId}).then((function(i){t.arr=i.data})).catch((function(i){console.log("err",i),t.$showToast(i.message)}))},open:function(t){this.changeId=t,this.payType=!0},give:function(t){var i=this;if(""==this.giveId)return this.$showToast("请输入转账人Id");this.$request("post","order/giveGoods",{collId:this.changeId,heuserid:this.giveId}).then((function(t){i.payType=!1,i.getList(),i.$showToast(t.message)})).catch((function(t){console.log("err",t),i.$showToast(t.message)}))},link:function(t){uni.navigateTo({url:"/pages/index/detail?id=".concat(t,"&type=1")})},utTime:function(t){return this.$utilTime(t)}}};i.default=n},"4e4d":function(t,i,e){var n=e("24fb"),c=e("1de5"),o=e("876c");i=n(!1);var a=c(o);i.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-2840bd90]{background-color:#0c0d0d}.collection[data-v-2840bd90]{padding:%?30?%}.collection .collection-pic[data-v-2840bd90]{width:100%}.collection .collection-pic .collection-pic-item[data-v-2840bd90]{margin-bottom:%?40?%}.collection .collection-pic .collection-pic-item .collection-pic-img[data-v-2840bd90]{width:100%;height:%?650?%;box-shadow:0 0 9px #fff}.collection .collection-pic .collection-pic-item .collection-pic-text[data-v-2840bd90]{position:relative;box-sizing:border-box;width:100%;margin-top:%?-60?%;font-size:%?45?%;color:#fff;padding:%?60?% %?30?% %?40?%;background:url('+a+") no-repeat;background-size:100% 100%;box-shadow:0 2px 5px #fff;display:flex;justify-content:space-between;align-items:center}.collection .collection-pic .collection-pic-item .collection-pic-text uni-view .titleTop[data-v-2840bd90]{font-weight:700}.collection .collection-pic .collection-pic-item .collection-pic-text uni-view .titleTtime[data-v-2840bd90]{font-size:%?30?%}.collection .collection-pic .collection-pic-item .collection-pic-text .away[data-v-2840bd90]{background-color:#f3e0bc;color:#000;padding:%?6?% %?16?%;border-radius:%?8?%;font-size:15px}.back[data-v-2840bd90]{width:100%;height:100%;background-color:rgba(0,0,0,.5);position:fixed;top:0;left:0;display:flex;justify-content:center;align-items:center;z-index:999}.back .change[data-v-2840bd90]{width:86%;padding:%?30?%;background-color:#fff;border-radius:%?30?%;box-sizing:border-box}.back .change .title[data-v-2840bd90]{text-align:center}.back .change .ipt[data-v-2840bd90]{margin:0 auto;margin-top:%?30?%;width:90%;height:%?70?%;line-height:%?70?%;border:%?4?% dashed #f3e0bc;padding-left:%?8?%}.back .change .ipt uni-input[data-v-2840bd90]{width:100%;height:100%}.back .yes[data-v-2840bd90]{width:80%;height:%?70?%;line-height:%?70?%;text-align:center;background-color:#f3e0bc;color:#000;margin:0 auto;margin-top:%?40?%;border-radius:%?34?%}body.?%PAGE?%[data-v-2840bd90]{background-color:#0c0d0d}",""]),t.exports=i},"71cc":function(t,i,e){var n=e("4e4d");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var c=e("4f06").default;c("0ef94607",n,!0,{sourceMap:!1,shadowMode:!1})},"876c":function(t,i,e){t.exports=e.p+"static/img/image02.8937fea3.png"},"90f2":function(t,i,e){"use strict";e.r(i);var n=e("4d9b"),c=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(i,t,(function(){return n[t]}))}(o);i["default"]=c.a},9225:function(t,i,e){"use strict";var n;e.d(i,"b",(function(){return c})),e.d(i,"c",(function(){return o})),e.d(i,"a",(function(){return n}));var c=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{staticClass:"collection"},[e("v-uni-view",{staticClass:"collection-pic"},t._l(t.arr,(function(i){return e("v-uni-view",{staticClass:"collection-pic-item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.link(i.goods_id)}}},[e("v-uni-image",{staticClass:"collection-pic-img",attrs:{src:"https://shucang.zhongyuansc.net/uploads/"+i.file_path,mode:""}}),e("v-uni-view",{staticClass:"collection-pic-text"},[e("v-uni-view",[e("v-uni-view",{staticClass:"titleTop"},[t._v(t._s(i.goods_name))]),e("v-uni-view",{staticClass:"titleTtime"},[t._v(t._s(t.utTime(i.addtime)))])],1),1==i.giveType?e("v-uni-view",{staticClass:"away",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.open(i.coll_id)}}},[t._v("转赠")]):t._e()],1)],1)})),1),t.payType?e("v-uni-view",{staticClass:"back",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.payType=!1}}},[e("v-uni-view",{staticClass:"change",on:{click:function(i){i.stopPropagation(),arguments[0]=i=t.$handleEvent(i)}}},[e("v-uni-view",{staticClass:"title"},[t._v("填写转赠人账户地址")]),e("v-uni-view",{staticClass:"ipt"},[e("v-uni-input",{attrs:{type:"text",value:"",placeholder:"请输入转赠地址"},model:{value:t.giveId,callback:function(i){t.giveId=i},expression:"giveId"}})],1),e("v-uni-view",{staticClass:"yes",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.give.apply(void 0,arguments)}}},[t._v("确定")])],1)],1):t._e()],1)},o=[]},"9c6b":function(t,i,e){"use strict";var n=e("71cc"),c=e.n(n);c.a}}]);