(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-goodsAll-goodsAll"],{"02a0":function(t,e,n){"use strict";var i=n("4ea4");n("99af"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i(n("2909")),o={data:function(){return{arr:[{name:"挂售中"},{name:"未挂售"}],changeIndex:0,list:[],total:0,goods_id:0,file_path:"",goods_name:"",page:1}},methods:{change:function(t){this.changeIndex=t,this.list=[],this.getList()},getList:function(){var t=this;this.$request("post","order/myColl",{goods_id:this.goods_id,page:this.page,status:0==this.changeIndex?1:0,tran_type:1}).then((function(e){t.list=[].concat((0,a.default)(t.list),(0,a.default)(e.data))})).catch((function(t){console.log("err",t),uni.showToast({title:t.message,icon:"none"})}))},link:function(t,e){0==this.changeIndex?uni.navigateTo({url:"/pages/index/detail?id="+t+"&type=2&end="+!0}):uni.navigateTo({url:"/pages/index/detail?id="+e})}},onLoad:function(t){this.configs=this.$config.ImgUrl,this.goods_id=t.goods_id,this.file_path=t.file_path,this.goods_name=t.goods_name,this.getList()},onReachBottom:function(){}};e.default=o},2909:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=c;var i=r(n("6005")),a=r(n("db90")),o=r(n("06c5")),d=r(n("3427"));function r(t){return t&&t.__esModule?t:{default:t}}function c(t){return(0,i.default)(t)||(0,a.default)(t)||(0,o.default)(t)||(0,d.default)()}},3427:function(t,e,n){"use strict";function i(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}Object.defineProperty(e,"__esModule",{value:!0}),e.default=i},"42b6":function(t,e,n){"use strict";var i=n("b35d"),a=n.n(i);a.a},6005:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=o;var i=a(n("6b75"));function a(t){return t&&t.__esModule?t:{default:t}}function o(t){if(Array.isArray(t))return(0,i.default)(t)}},8896:function(t,e,n){"use strict";n.r(e);var i=n("97c0"),a=n("c6cf");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("42b6");var d,r=n("f0c5"),c=Object(r["a"])(a["default"],i["b"],i["c"],!1,null,"55ffcd28",null,!1,i["a"],d);e["default"]=c.exports},"97c0":function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{staticClass:"fix_title"},t._l(t.arr,(function(e,n){return i("v-uni-view",{key:n,class:[t.changeIndex==n?"fix_item":"no_change"],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.change(n)}}},[i("v-uni-view",{},[t._v(t._s(e.name))])],1)})),1),0!=t.list.length?i("v-uni-view",{staticClass:"collect"},t._l(t.list,(function(e,n){return i("v-uni-view",{key:n,staticClass:"item",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.link(e.transaction_id,e.goods_id)}}},[i("v-uni-view",{staticClass:"shop_img"},[i("v-uni-image",{attrs:{src:t.configs+e.file_path}})],1),i("v-uni-view",{staticClass:"detail_box"},[i("v-uni-view",{staticClass:"name"},[t._v("编号#"+t._s(e.number)+"/"+t._s(e.get_total))]),i("v-uni-view",{staticClass:"name"},[t._v(t._s(e.goods_name))]),0==t.changeIndex?i("v-uni-view",[t._v("￥"+t._s(e.tran_price))]):t._e()],1)],1)})),1):i("v-uni-view",{staticClass:"empty"},[i("v-uni-image",{attrs:{src:n("de12"),mode:"widthFix"}}),i("v-uni-text",[t._v("空空如也~")])],1)],1)},o=[]},b35d:function(t,e,n){var i=n("c89b");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("912fc108",i,!0,{sourceMap:!1,shadowMode:!1})},c6cf:function(t,e,n){"use strict";n.r(e);var i=n("02a0"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},c89b:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-55ffcd28]{background-color:#fbfbfb}.fix_title[data-v-55ffcd28]{display:flex;justify-content:space-around;padding:%?20?% %?30?%}.fix_title .no_change[data-v-55ffcd28]{color:#999}.fix_title .fix_item[data-v-55ffcd28]{color:#000;border-bottom:%?4?% solid #000;padding-bottom:%?8?%}.collect[data-v-55ffcd28]{display:flex;justify-content:space-between;align-items:center;padding:%?30?% %?20?%;flex-wrap:wrap}.collect .item[data-v-55ffcd28]{width:48%;border-radius:%?10?%;margin-bottom:%?20?%;background-color:#fff;border-radius:%?30?%;overflow:hidden}.collect .item .shop_img[data-v-55ffcd28]{width:100%;height:%?400?%;border-radius:%?30?%;overflow:hidden}.collect .item .shop_img uni-image[data-v-55ffcd28]{width:100%;height:100%}.collect .item .detail_box[data-v-55ffcd28]{background-color:#fff;border-radius:0 0 %?30?% %?30?%;padding:%?40?% %?20?% %?20?%;box-sizing:border-box;color:#000;margin-top:%?-20?%}.collect .item .detail_box .copy-btn[data-v-55ffcd28]{display:flex;align-items:center}.collect .item .detail_box .copy-btn > uni-image[data-v-55ffcd28]{width:%?50?%;height:%?50?%;margin-left:%?20?%}.collect .item .detail_box .name[data-v-55ffcd28]{margin-bottom:%?12?%}.collect .item .detail_box .away_box[data-v-55ffcd28]{display:flex;justify-content:space-between}.collect .item .detail_box .away_box .away[data-v-55ffcd28]{background-color:#f3e0bc;color:#000;padding:%?6?% %?16?%;border-radius:%?8?%;font-size:15px;margin-top:%?10?%;display:flex}.collect .item .detail_box .away_box .sell[data-v-55ffcd28]{background-color:#e5004d;color:#fff;padding:%?6?% %?16?%;border-radius:%?8?%;font-size:15px;margin-top:%?10?%;display:flex;margin-left:auto}.empty[data-v-55ffcd28]{width:100%}.empty uni-image[data-v-55ffcd28]{width:40%;margin-left:30%;margin-top:40%;opacity:.5}.empty uni-text[data-v-55ffcd28]{width:100%;color:#999;display:block;text-align:center;opacity:.8;margin-top:%?20?%}body.?%PAGE?%[data-v-55ffcd28]{background-color:#fbfbfb}',""]),t.exports=e},db90:function(t,e,n){"use strict";function i(t){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}n("a4d3"),n("e01a"),n("d28b"),n("a630"),n("d3b7"),n("3ca3"),n("ddb0"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=i},de12:function(t,e,n){t.exports=n.p+"static/img/img21.76eaa030.png"}}]);