(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-zzlist-zzlist"],{1425:function(t,e,n){"use strict";n.r(e);var i=n("55ea"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},"359c":function(t,e,n){"use strict";var i=n("a797"),a=n.n(i);a.a},"525c":function(t,e,n){"use strict";n.r(e);var i=n("aeba"),a=n("1425");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("359c");var r,c=n("f0c5"),s=Object(c["a"])(a["default"],i["b"],i["c"],!1,null,"bc7b3c42",null,!1,i["a"],r);e["default"]=s.exports},"55ea":function(t,e,n){"use strict";var i=n("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i(n("b85c")),o={data:function(){return{changeIndex:0,box:[],configs:""}},onLoad:function(){this.configs=this.$config.imgpreve,this.getList()},methods:{getList:function(){var t=this;this.$request("post","order/myColl",{userid:uni.getStorageSync("userinfo").userId,type:1,tran_type:0}).then((function(e){t.box=e.data;var n,i=(0,a.default)(t.box);try{for(i.s();!(n=i.n()).done;){var o=n.value;o.cover_path?(o.isPreview=!0,o.cover_path=t.$config.imgpreve+o.cover_path):(o.isPreview=!1,o.goods_image=t.$config.imgpreve+o.goods_image)}}catch(r){i.e(r)}finally{i.f()}})).catch((function(e){t.$showToast(e.message)}))},utTime:function(t){return this.$utilTime(t)},link:function(t,e,n){uni.navigateTo({url:"/pages/index/detail?id=".concat(t,"&type=1&has=")+e+"&number="+n})}}};e.default=o},a797:function(t,e,n){var i=n("c9b5");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("0a8f1bc8",i,!0,{sourceMap:!1,shadowMode:!1})},aeba:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[0!=t.box.length?i("v-uni-view",{staticClass:"collect"},t._l(t.box,(function(e,n){return i("v-uni-view",{staticClass:"item",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.link(e.goods_id,e.tx_hash,e.number)}}},[i("v-uni-view",{staticClass:"shop_img"},[i("v-uni-image",{attrs:{src:1==e.isPreview?t.configs+e.cover_path:t.configs+e.goods_image,mode:"aspectFill"}})],1),i("v-uni-view",{staticClass:"detail_box"},[i("v-uni-view",{staticClass:"name"},[t._v("编号#"+t._s(e.number+"/"+e.xn_sale))]),i("v-uni-view",{staticClass:"name"},[t._v(t._s(e.goods_name))]),i("v-uni-view",{staticClass:"time"},[t._v("转赠人:"+t._s(e.usmobile))]),i("v-uni-view",{staticClass:"time"},[t._v(t._s(t.utTime(e.addtime)))])],1)],1)})),1):i("v-uni-view",{staticClass:"empty"},[i("v-uni-image",{attrs:{src:n("de12"),mode:"widthFix"}}),i("v-uni-text",[t._v("暂无转赠记录~")])],1)],1)},o=[]},b85c:function(t,e,n){"use strict";n("a4d3"),n("e01a"),n("d28b"),n("d3b7"),n("3ca3"),n("ddb0"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=o;var i=a(n("06c5"));function a(t){return t&&t.__esModule?t:{default:t}}function o(t,e){var n;if("undefined"===typeof Symbol||null==t[Symbol.iterator]){if(Array.isArray(t)||(n=(0,i.default)(t))||e&&t&&"number"===typeof t.length){n&&(t=n);var a=0,o=function(){};return{s:o,n:function(){return a>=t.length?{done:!0}:{done:!1,value:t[a++]}},e:function(t){throw t},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var r,c=!0,s=!1;return{s:function(){n=t[Symbol.iterator]()},n:function(){var t=n.next();return c=t.done,t},e:function(t){s=!0,r=t},f:function(){try{c||null==n["return"]||n["return"]()}finally{if(s)throw r}}}}},c9b5:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-bc7b3c42]{background:#101010}.empty[data-v-bc7b3c42]{width:100%}.empty uni-image[data-v-bc7b3c42]{width:80%;margin-left:10%;margin-top:20%;opacity:.5}.empty uni-text[data-v-bc7b3c42]{width:100%;color:#fff;display:block;text-align:center;opacity:.8;margin-top:%?20?%}.collect[data-v-bc7b3c42]{display:flex;justify-content:space-between;align-items:center;padding:%?30?% %?20?%;flex-wrap:wrap}.collect .item[data-v-bc7b3c42]{width:48%;border-radius:%?10?%;margin-bottom:%?20?%;background-color:#101010;box-shadow:%?0?% %?0?% %?10?% %?2?% #101010;border-radius:%?30?%;overflow:hidden;border:%?1?% solid #fff}.collect .item .shop_img[data-v-bc7b3c42]{width:100%;height:%?320?%;border-radius:%?30?% %?30?% 0 0;overflow:hidden}.collect .item .shop_img uni-image[data-v-bc7b3c42]{width:100%;height:100%}.collect .item .detail_box[data-v-bc7b3c42]{background-color:#101010;border-radius:0 0 %?30?% %?30?%;padding:%?40?% %?20?% %?20?%;box-sizing:border-box;color:#fff;margin-top:%?-20?%}.collect .item .detail_box .copy-btn[data-v-bc7b3c42]{display:flex;align-items:center}.collect .item .detail_box .copy-btn > uni-image[data-v-bc7b3c42]{width:%?50?%;height:%?50?%;margin-left:%?20?%}.collect .item .detail_box .name[data-v-bc7b3c42]{margin-bottom:%?12?%}.collect .item .detail_box .time[data-v-bc7b3c42]{color:#fff;font-size:%?25?%}.collect .item .detail_box .away_box[data-v-bc7b3c42]{display:flex;justify-content:space-between}.collect .item .detail_box .away_box .away[data-v-bc7b3c42]{background-color:#f3e0bc;color:#fff;padding:%?6?% %?16?%;border-radius:%?8?%;font-size:15px;margin-top:%?10?%;display:flex}.collect .item .detail_box .away_box .sell[data-v-bc7b3c42]{background-color:#e5004d;color:#fff;padding:%?6?% %?16?%;border-radius:%?8?%;font-size:15px;margin-top:%?10?%;display:flex;margin-left:auto}body.?%PAGE?%[data-v-bc7b3c42]{background:#101010}',""]),t.exports=e},de12:function(t,e,n){t.exports=n.p+"static/img/img21.76eaa030.png"}}]);