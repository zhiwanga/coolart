(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-index-detail"],{"08e8":function(t,e,a){var i=a("5b31");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("fd474598",i,!0,{sourceMap:!1,shadowMode:!1})},"0c71":function(t,e,a){t.exports=a.p+"static/img/story_02.e2848f40.png"},1150:function(t,e,a){"use strict";var i=a("4ea4");a("ac1f"),a("5319"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i(a("7cd2")),o={data:function(){return{detailImg:a("2596"),storyImg:a("32d9"),noticeText:"数字藏品为虚拟数字商品，而非实物，仅限实名认证为年满14周岁的中国大陆用户购买。数字藏品的版权由发行方或原创者拥有，除另行取得版权拥有者书面同意外，用户不得将数字藏品用于任何商业用途。本商品一经售出，不支持退换。本商品源文件不支持本地下载。请勿对数字藏品进行炒作、场外交易、欺诈，或以任何其他非法方式进行使用。",detailList:{},detailType:!0,payType:!1,payArr:[{name:"微信",image:"/static/wechat.png"},{name:"支付宝",image:"/static/zfb.png"}],payIndex:0,showIcon:!0}},onLoad:function(t){console.log(t),this.getDetailList(t.id),1==t.type&&(this.detailType=!1)},methods:{getDetailList:function(t){var e=this;this.$request("get","Goods/goodsDetails",{goodsid:t}).then((function(t){console.log("res",t),t.data.goodsdetails.content=n.default.formatRichText(t.data.goodsdetails.content),e.detailList=t.data.goodsdetails;var a=new Date(t.data.goodsdetails.startTime.replace(/-/g,"/")).getTime(),i=(new Date).getTime();return e.showIcon=i>a})).catch((function(t){console.log("err",t)}))},submit:function(){var t=this;0==this.payIndex?uni.request({url:"https://shucang.zhongyuansc.net/index.php?s=/api/Checkout/ordersWe",header:{"Access-Token":uni.getStorageSync("userinfo").token||"",platform:"H5"},method:"POST",data:{goodsid:this.detailList.goods_id,userid:uni.getStorageSync("userinfo").userId,isBox:0,pay_type:20},success:function(e){if(500==e.data.status)return t.$showToast(e.data.message);console.log("dd",e);var a=e.data+"&redirect_url="+encodeURIComponent("https://shucang.zhongyuansc.net/#/pages/order/index");console.log("asd",a),location.href=a}}):1==this.payIndex&&uni.request({url:"https://shucang.zhongyuansc.net/index.php?s=/api/Checkout/orders",header:{"Access-Token":uni.getStorageSync("userinfo").token||"",platform:"H5"},method:"POST",data:{goodsid:this.detailList.goods_id,userid:uni.getStorageSync("userinfo").userId,isBox:0,pay_type:30},success:function(t){document.querySelector("body").innerHTML=t.data,document.forms[0].submit()}})},changeType:function(t){this.payIndex=t},buy:function(t){this.payType=!0}}};e.default=o},"16f4":function(t,e,a){"use strict";a.r(e);var i=a("a534"),n=a("5789");for(var o in n)"default"!==o&&function(t){a.d(e,t,(function(){return n[t]}))}(o);a("2caa");var r,s=a("f0c5"),d=Object(s["a"])(n["default"],i["b"],i["c"],!1,null,"94e64daa",null,!1,i["a"],r);e["default"]=d.exports},"1de5":function(t,e,a){"use strict";t.exports=function(t,e){return e||(e={}),t=t&&t.__esModule?t.default:t,"string"!==typeof t?t:(/^['"].*['"]$/.test(t)&&(t=t.slice(1,-1)),e.hash&&(t+=e.hash),/["'() \t\n]/.test(t)||e.needQuotes?'"'.concat(t.replace(/"/g,'\\"').replace(/\n/g,"\\n"),'"'):t)}},2596:function(t,e,a){t.exports=a.p+"static/img/story_03.8252b782.png"},"2caa":function(t,e,a){"use strict";var i=a("08e8"),n=a.n(i);n.a},"32d9":function(t,e,a){t.exports=a.p+"static/img/story.a6dd6950.png"},5789:function(t,e,a){"use strict";a.r(e);var i=a("1150"),n=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);e["default"]=n.a},"5b31":function(t,e,a){var i=a("24fb"),n=a("1de5"),o=a("0c71");e=i(!1);var r=n(o);e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-94e64daa]{background-color:#0c0d0d}.detail[data-v-94e64daa]{width:100%;padding:0 %?30?% %?120?%;box-sizing:border-box}.detail .detail-image[data-v-94e64daa]{width:100%;text-align:center;-webkit-transform:translateZ(0);transform:translateZ(0)}.detail .detail-image .detail-img[data-v-94e64daa]{width:70%;-webkit-animation:detail-data-v-94e64daa 20s infinite;animation:detail-data-v-94e64daa 20s infinite;-webkit-transform:translateZ(%?2?%);transform:translateZ(%?2?%)}.detail .exhibition[data-v-94e64daa]{width:100%;padding:%?100?% 0 %?100?%;background:url('+r+") no-repeat;background-size:100% 100%;font-size:%?45?%;color:#fff;text-align:center}.detail .exhibition .titleTop[data-v-94e64daa]{margin-top:%?50?%;font-weight:700}.detail .exhibition .number[data-v-94e64daa]{display:flex;align-items:center;justify-content:center;font-size:%?28?%;margin:%?20?% 0}.detail .exhibition .number .numText[data-v-94e64daa]{display:flex;align-items:center;border-radius:%?5?%;background:#4b4b4c;color:#f3e0bc;padding-right:%?20?%;margin-right:%?15?%}.detail .exhibition .number .numText .title[data-v-94e64daa]{background:#f3e0bc;padding:%?5?% %?10?%;color:#000;border-radius:%?5?%;margin-right:%?20?%}.detail .story[data-v-94e64daa]{width:100%;background:#181919;border-radius:%?30?%;color:#fff;padding:%?40?% %?30?%;margin-bottom:%?30?%;box-sizing:border-box}.detail .story .story-title[data-v-94e64daa]{font-size:%?50?%;font-weight:700;margin-bottom:%?20?%}.detail .story .story-content-img[data-v-94e64daa]{width:100%}.detail .story .story-author[data-v-94e64daa]{display:flex;align-items:center;justify-content:space-between;padding:%?30?% %?20?% %?20?%;color:#a3a3a3;font-size:%?35?%}.detail .story .story-author .story-author-text[data-v-94e64daa]{color:#fff}.detail .author[data-v-94e64daa]{width:100%;background:#181919;border-radius:%?30?%;color:#fff;padding:%?40?% %?30?%;margin-bottom:%?30?%}.detail .author uni-view[data-v-94e64daa]:nth-child(1){color:#9b9b9b}.detail .notice[data-v-94e64daa]{width:100%;background:#181919;border-radius:%?30?%;color:#fff;padding:%?40?% %?30?%;margin-bottom:%?30?%;box-sizing:border-box}.detail .notice .notice-title[data-v-94e64daa]{font-size:%?50?%;font-weight:700;margin-bottom:%?20?%}.detail .notice .notice-content[data-v-94e64daa]{color:#9b9b9b;letter-spacing:%?3.5?%}.detail .submit[data-v-94e64daa]{position:fixed;bottom:0;left:0;width:100%;display:flex;justify-content:space-between;align-items:center;padding:%?35?% %?30?%;background:#0d0c0e;color:#fff;font-size:%?50?%;font-weight:700;box-sizing:border-box}.detail .submit .sub-price[data-v-94e64daa]{letter-spacing:%?5?%}.detail .submit .sub-btn[data-v-94e64daa]{font-size:%?30?%;background:#333;border-radius:%?50?%;padding:%?20?% %?50?%;text-align:center}@-webkit-keyframes detail-data-v-94e64daa{0%{-webkit-transform:rotateY(0);transform:rotateY(0)}50%{-webkit-transform:rotateY(1turn);transform:rotateY(1turn)}100%{-webkit-transform:rotateY(0);transform:rotateY(0)}}@keyframes detail-data-v-94e64daa{0%{-webkit-transform:rotateY(0);transform:rotateY(0)}50%{-webkit-transform:rotateY(1turn);transform:rotateY(1turn)}100%{-webkit-transform:rotateY(0);transform:rotateY(0)}}.back[data-v-94e64daa]{width:100%;height:100%;background-color:rgba(0,0,0,.5);position:fixed;top:0;left:0;display:flex;justify-content:center;align-items:center;z-index:999}.back .change[data-v-94e64daa]{width:86%;padding:%?30?%;background-color:#fff;border-radius:%?30?%;box-sizing:border-box}.back .change .title[data-v-94e64daa]{text-align:center}.back .change .item[data-v-94e64daa]{display:flex;align-items:center;margin-top:%?30?%;padding:%?10?%}.back .change .item .img[data-v-94e64daa]{width:%?64?%;height:%?64?%;margin-right:%?20?%}.back .change .item .img uni-image[data-v-94e64daa]{width:100%;height:100%}.back .change .active[data-v-94e64daa]{border:%?4?% dashed #f3e0bc;box-sizing:border-box}.back .yes[data-v-94e64daa]{width:80%;height:%?70?%;line-height:%?70?%;text-align:center;background-color:#f3e0bc;color:#000;margin:0 auto;margin-top:%?40?%;border-radius:%?34?%}body.?%PAGE?%[data-v-94e64daa]{background-color:#0c0d0d}",""]),t.exports=e},"7cd2":function(t,e,a){"use strict";function i(t){var e;return function(){var a=this,i=arguments;e&&clearTimeout(e),e=setTimeout((function(){t.apply(a,i)}),e)}}function n(t){return/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(t)}function o(t){return/^([\u4e00-\u9fa5]{1,20}|[a-zA-Z\.\s]{1,20})$/.test(t)}function r(t){var e=t.replace(/<img[^>]*>/gi,(function(t,e){return t=t.replace(/style="[^"]+"/gi,"").replace(/style='[^']+'/gi,""),t=t.replace(/width="[^"]+"/gi,"").replace(/width='[^']+'/gi,""),t=t.replace(/height="[^"]+"/gi,"").replace(/height='[^']+'/gi,""),t}));return e=e.replace(/style="[^"]+"/gi,(function(t,e){return t=t.replace(/width:[^;]+;/gi,"max-width:100%;").replace(/width:[^;]+;/gi,"max-width:100%;"),t})),e=e.replace(/<br[^>]*\/>/gi,""),e=e.replace(/\<img/gi,'<img style="max-width:100%;height:auto;display:block;margin-top:0;margin-bottom:0;"'),e}a("ac1f"),a("5319"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var s={debounce:i,checkIdCard:n,checkName:o,formatRichText:r};e.default=s},a534:function(t,e,a){"use strict";var i;a.d(e,"b",(function(){return n})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return i}));var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"detail"},[a("v-uni-view",{staticClass:"detail-image"},[a("v-uni-image",{staticClass:"detail-img",attrs:{src:"https://shucang.zhongyuansc.net/uploads/"+t.detailList.file_path,mode:"widthFix"}})],1),a("v-uni-view",{staticClass:"exhibition"},[a("v-uni-view",{staticClass:"titleTop"},[t._v(t._s(t.detailList.goods_name))]),t.detailType?a("v-uni-view",{staticClass:"number"},[a("v-uni-view",{staticClass:"numText"},[a("v-uni-view",{staticClass:"title"},[t._v("限量")]),t._v(t._s(t.detailList.stock_total)+"份")],1),a("v-uni-view",{staticClass:"numText"},[a("v-uni-view",{staticClass:"title"},[t._v("剩余")]),t._v(t._s(t.detailList.stock_num)+"份")],1)],1):t._e()],1),a("v-uni-view",{staticClass:"story"},[a("v-uni-view",{staticClass:"story-title"},[t._v("作品故事")]),a("v-uni-view",{staticClass:"story-content"},[a("v-uni-rich-text",{attrs:{nodes:t.detailList.content}})],1)],1),a("v-uni-view",{staticClass:"author"},[a("v-uni-view",[t._v("创造者")]),a("v-uni-view",[t._v(t._s(t.detailList.author))])],1),t.detailType?a("v-uni-view",{staticClass:"notice"},[a("v-uni-view",{staticClass:"notice-title"},[t._v("购买须知")]),a("v-uni-view",{staticClass:"notice-content"},[t._v(t._s(t.noticeText))])],1):t._e(),t.detailType?a("v-uni-view",{staticClass:"submit"},[a("v-uni-view",{staticClass:"sub-price"},[t._v("￥"+t._s(t.detailList.goods_price_min))]),t.showIcon?a("v-uni-view",{staticClass:"sub-btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.buy(t.detailList.goods_id)}}},[t._v("立即购买")]):a("v-uni-view",{staticClass:"sub-btn"},[t._v("暂未开售")])],1):t._e(),t.payType?a("v-uni-view",{staticClass:"back",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.payType=!1}}},[a("v-uni-view",{staticClass:"change",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e)}}},[a("v-uni-view",{staticClass:"title"},[t._v("选择支付方式")]),t._l(t.payArr,(function(e,i){return a("v-uni-view",{key:i,staticClass:"item",class:i==t.payIndex?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeType(i)}}},[a("v-uni-view",{staticClass:"img"},[a("v-uni-image",{attrs:{src:e.image,mode:""}})],1),a("v-uni-view",[t._v(t._s(e.name))])],1)})),a("v-uni-view",{staticClass:"yes",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.submit.apply(void 0,arguments)}}},[t._v("确定")])],2)],1):t._e(),a("v-uni-form",{attrs:{id:"alipaysubmit",name:"alipaysubmit",action:"支付宝地址",method:"POST"}})],1)},o=[]}}]);