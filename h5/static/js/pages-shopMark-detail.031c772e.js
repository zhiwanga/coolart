(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-shopMark-detail"],{"0655":function(t,i,e){var o=e("24fb"),a=e("1de5"),n=e("4ff3");i=o(!1);var s=a(n);i.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-2100407d]{background-color:#101010}.mask[data-v-2100407d]{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.3);overflow-y:hidden}.mask .maskcont[data-v-2100407d]{width:95%;height:70vh;background-color:#fff;left:0;bottom:0;margin-right:%?30?%;position:fixed;border-radius:%?25?%;padding:%?20?%;overflow:scroll}.mask .maskcont .maskhead[data-v-2100407d]{background:#fff;position:absolute;width:100%;height:%?64?%}.mask .maskcont .maskhead .maskhead-title[data-v-2100407d]{font-size:%?32?%}.mask .maskcont .maskhead .close[data-v-2100407d]{width:%?40?%;height:%?40?%;right:0;position:absolute;margin-right:%?10?%}.mask .maskcont .tabs[data-v-2100407d]{margin-top:%?32?%;width:%?180?%;border-radius:%?25?%;border-color:#101010;border-width:%?3?%;height:%?64?%}.mask .maskcont .numbers[data-v-2100407d]{width:100%;margin-top:%?64?%}.mask .maskcont .numbers .number-item[data-v-2100407d]{height:%?100?%;display:flex;flex-direction:row;border-bottom:%?0.5?% solid #e1dfe0}.mask .maskcont .numbers .number-item .number-left[data-v-2100407d]{display:flex;height:%?77?%;width:50%;flex-direction:column}.mask .maskcont .numbers .number-item .number-left .left-no[data-v-2100407d]{color:#000}.mask .maskcont .numbers .number-item .number-left .left-status[data-v-2100407d]{background-color:red;color:#fff;width:%?88?%;height:%?32?%;border-radius:%?8?%;text-align:center;padding:%?2?%;margin-top:%?10?%;margin-bottom:%?10?%;font-size:%?20?%}.mask .maskcont .numbers .number-item .number-right[data-v-2100407d]{display:flex;height:%?77?%;width:50%;flex-direction:column}.mask .maskcont .numbers .number-item .number-right .right-price[data-v-2100407d]{text-align:right;margin-right:%?80?%;color:#000}.auth[data-v-2100407d]{width:100%;padding:%?10?% %?40?%;box-sizing:border-box;display:flex;align-items:center}.auth uni-image[data-v-2100407d]{width:%?50?%;height:%?50?%}.auth uni-text[data-v-2100407d]{color:#ffc177;font-size:%?30?%}.goods-name[data-v-2100407d]{display:block;color:#fff;font-size:%?38?%;font-weight:700;padding:%?20?% %?40?% %?0?%;box-sizing:border-box}.sup-content[data-v-2100407d]{width:100%;padding:%?30?% %?40?% %?20?%;box-sizing:border-box;display:flex;align-items:center;position:relative}.sup-content .sup-content-list[data-v-2100407d]{height:%?60?%;line-height:%?60?%;margin-right:%?30?%}.sup-content .sup-content-list > uni-text[data-v-2100407d]:nth-of-type(1){padding:0 %?10?%;box-sizing:border-box;display:inline-block;color:#fff;background:#236de8;font-size:%?28?%}.sup-content .sup-content-list > uni-text[data-v-2100407d]:nth-of-type(2){padding:0 %?20?%;box-sizing:border-box;display:inline-block;color:#000;background:#e4f0fe;font-size:%?28?%}.sup-content .lookAll[data-v-2100407d]{position:absolute;right:%?40?%;font-size:%?30?%}.line-style[data-v-2100407d]{width:100%;height:%?30?%;background:#1c1c1c}.detail[data-v-2100407d]{width:100%;padding:0 0 %?120?%;box-sizing:border-box;overflow:hidden}.detail .back-btn[data-v-2100407d]{width:%?25?%;height:%?25?%;position:absolute;left:%?40?%;top:%?60?%;z-index:1000}.detail .share-btn[data-v-2100407d]{width:%?50?%;height:%?50?%;position:absolute;right:%?40?%;top:%?60?%;z-index:1000}.detail .detail-image[data-v-2100407d]{width:100%;text-align:center;-webkit-transform:translateZ(0);transform:translateZ(0)}.detail .F1[data-v-2100407d]{width:100%;position:relative}.detail .F1 .F1-bg[data-v-2100407d]{width:100%;position:relative;z-index:1}.detail .F1 .F1-logo[data-v-2100407d]{width:100%;position:absolute;z-index:2;top:17%}.detail .F1 .F1-goods-img[data-v-2100407d]{width:100%;position:relative;top:0;left:0;right:0;z-index:2}.detail .F1 .F1-bg-fly[data-v-2100407d]{width:100%;height:%?160?%;background:url('+s+") no-repeat;background-size:100% 100%;display:flex;justify-content:center;flex-wrap:wrap;flex-direction:column;-webkit-transform:translateY(%?-20?%);transform:translateY(%?-20?%)}.detail .F1 .F1-goods-name[data-v-2100407d]{width:60%;left:20%;display:block;position:relative;z-index:3;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#000;font-size:%?40?%}.detail .F1 .F1-goods-bottom[data-v-2100407d]{width:56%;left:22%;display:flex;position:relative;padding:%?5?% %?20?%;box-sizing:border-box;border-radius:%?10?%;z-index:3;color:#8b5704;align-items:center;justify-content:center}.detail .F1 .F1-goods-bottom > uni-text[data-v-2100407d]:nth-of-type(1){display:block;padding:%?5?% %?10?%;box-sizing:border-box;color:#864c14;background:#e8bd7a;font-size:%?25?%;border-top-left-radius:%?7?%;border-bottom-left-radius:%?7?%}.detail .F1 .F1-goods-bottom > uni-text[data-v-2100407d]:nth-of-type(2){display:block;padding:%?5?% %?10?%;box-sizing:border-box;color:#b58b48;background:#ffe5bc;border-top-right-radius:%?7?%;border-bottom-right-radius:%?7?%;font-size:%?25?%}.detail .list[data-v-2100407d]{width:100%;padding:0 %?40?%;box-sizing:border-box;margin:%?20?% auto;border-radius:%?20?%;position:relative}.detail .list .list-bg[data-v-2100407d]{display:block;height:%?150?%;position:absolute;right:%?120?%;top:%?40?%}.detail .list .list-item[data-v-2100407d]{display:flex;align-items:center;padding:%?20?% 0;border-bottom:%?1?% solid #1c1c1c;color:#fff;font-size:15px}.detail .list .list-item .icon-left[data-v-2100407d]{width:%?200?%;text-align:left}.detail .list .list-item[data-v-2100407d]:last-of-type{border:none}.detail .list .icon-right[data-v-2100407d]{display:block;max-width:50%;line-break:anywhere;color:#fff}.detail .list .icon-right > uni-image[data-v-2100407d]{width:%?30?%;height:%?30?%;margin-left:%?20?%}.detail .story-title[data-v-2100407d]{width:100%;padding:0 %?50?%;font-size:17px;font-weight:700;margin-top:%?50?%;color:#fff}.detail .time-box[data-v-2100407d]{width:calc(100% - %?50?%);margin-left:%?25?%;margin-top:%?20?%;padding:%?20?%;box-sizing:border-box;background:#2a2a2b;display:flex;border-radius:%?10?%;align-items:center;justify-content:space-around}.detail .time-box .time-box-item[data-v-2100407d]{width:30%;text-align:center}.detail .time-box .time-box-item > uni-text[data-v-2100407d]{line-height:%?50?%;white-space:nowrap;font-size:%?30?%;color:#f4dfb7;display:block;text-align:center}.detail .time-box > uni-text[data-v-2100407d]{color:#f4dfb7;display:block;-webkit-transform:scaleX(2);transform:scaleX(2)}.detail .time-btn[data-v-2100407d]{width:calc(100% - %?50?%);margin-top:%?20?%;margin-left:%?25?%;margin-bottom:%?40?%;padding:%?10?% 0;background:#ffecc8;border-radius:%?100?%;text-align:center}.detail .time-btn > uni-text[data-v-2100407d]:nth-of-type(1){display:block;color:#b27a33;font-size:%?35?%;font-weight:700}.detail .time-btn > uni-text[data-v-2100407d]:nth-of-type(2){display:block;color:#cd9854;font-size:%?30?%}.detail .tip[data-v-2100407d]{width:100%;margin-top:%?20?%;display:block;text-align:center;color:#999;font-size:%?25?%}.detail .story[data-v-2100407d]{width:100%;border-radius:%?30?%;margin-top:%?20?%;color:#000;padding:%?30?% %?50?%;margin-bottom:%?30?%;box-sizing:border-box}.detail .story .story-author[data-v-2100407d]{display:flex;align-items:center;justify-content:space-between;padding:%?30?% %?20?% %?20?%;color:#a3a3a3;font-size:%?35?%}.detail .story .story-author .story-author-text[data-v-2100407d]{color:#000}.detail .submit[data-v-2100407d]{position:fixed;bottom:0;left:0;width:100%;display:flex;justify-content:space-between;align-items:center;padding:%?0?% %?30?%;background:#3e3e3e;color:#fff;font-size:%?60?%;font-weight:700;box-sizing:border-box;border-top:%?1?% solid #1c1c1c}.detail .submit .submit-left-icon[data-v-2100407d]{display:flex;align-items:center}.detail .submit .submit-left-icon .icon-left[data-v-2100407d]{font-size:%?20?%;color:#fff;display:inline-block;-webkit-transform:translateY(%?10?%);transform:translateY(%?10?%)}.detail .submit .sub-price[data-v-2100407d]{color:#fff}.detail .submit .sub-btn[data-v-2100407d]{font-size:%?30?%;background:#000;color:#fff;border-radius:%?50?%;padding:%?25?% %?60?%;text-align:center}.detail .submit .sub-btn-other[data-v-2100407d]{width:45%;margin:%?20?%}.detail .submit .timeIcon[data-v-2100407d]{display:flex;align-items:center}.detail .submit .timeIcon .icon[data-v-2100407d]{width:%?36?%;height:%?36?%;margin-right:%?6?%}.detail .submit .timeIcon .icon uni-image[data-v-2100407d]{width:100%;height:100%}.detail .other-submit[data-v-2100407d]{padding:%?20?% %?30?%}@-webkit-keyframes detail-data-v-2100407d{0%{-webkit-transform:rotateY(0);transform:rotateY(0)}50%{-webkit-transform:rotateY(1turn);transform:rotateY(1turn)}100%{-webkit-transform:rotateY(0);transform:rotateY(0)}}@keyframes detail-data-v-2100407d{0%{-webkit-transform:rotateY(0);transform:rotateY(0)}50%{-webkit-transform:rotateY(1turn);transform:rotateY(1turn)}100%{-webkit-transform:rotateY(0);transform:rotateY(0)}}.back[data-v-2100407d]{width:100%;height:100%;background-color:rgba(0,0,0,.5);position:fixed;top:0;left:0;display:flex;justify-content:center;align-items:center;z-index:999}.back .change[data-v-2100407d]{width:86%;padding:%?30?%;background-color:#fff;border-radius:%?30?%;box-sizing:border-box}.back .change .title[data-v-2100407d]{text-align:center}.back .change .ipt[data-v-2100407d]{margin-top:%?40?%;margin-left:%?20?%;border-bottom:%?2?% solid silver}.back .change .item[data-v-2100407d]{width:100%;display:flex;justify-content:center;align-items:center;margin-top:%?30?%;padding:%?10?%}.back .change .item .img[data-v-2100407d]{width:%?84?%;height:%?64?%;margin-right:%?20?%}.back .change .item .img uni-image[data-v-2100407d]{width:100%;height:100%}.back .change .active[data-v-2100407d]{border:%?4?% dashed #f3e0bc;box-sizing:border-box}.back .yes[data-v-2100407d]{width:80%;height:%?70?%;line-height:%?70?%;text-align:center;background-color:#f3e0bc;color:#000;margin:0 auto;margin-top:%?40?%;border-radius:%?34?%}.chooseNum[data-v-2100407d]{width:100%;height:100%;background:rgba(0,0,0,.5);display:flex;align-items:flex-end;justify-content:center;position:fixed;left:0;right:0;bottom:0;top:0;z-index:999}.chooseNum .chooseNum-box[data-v-2100407d]{width:100%;background:#fff;border-top-left-radius:%?20?%;border-top-right-radius:%?20?%;padding:%?20?% %?40?%;box-sizing:border-box}.chooseNum .chooseNum-box .chooseNum-box-top[data-v-2100407d]{width:100%;padding-bottom:%?30?%;box-sizing:border-box;border-bottom:%?1?% solid #f1f1f1;display:flex;align-items:center;justify-content:space-between}.chooseNum .chooseNum-box .chooseNum-box-top > uni-text[data-v-2100407d]{font-weight:700}.chooseNum .chooseNum-box .chooseNum-box-top > uni-image[data-v-2100407d]{width:%?40?%;height:%?40?%}.chooseNum .chooseNum-box .chooseNum-box-center[data-v-2100407d]{width:100%;padding-bottom:%?30?%;padding-top:%?30?%;box-sizing:border-box;border-bottom:%?1?% solid #f1f1f1;display:flex;align-items:center;justify-content:space-between}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-left[data-v-2100407d]{display:flex;align-items:center}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-left > uni-text[data-v-2100407d]:first-of-type{font-weight:700}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-left > uni-text[data-v-2100407d]:last-of-type{color:#999;display:block;margin-left:%?20?%;font-size:%?25?%}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-right[data-v-2100407d]{display:flex;align-items:center}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-right > uni-image[data-v-2100407d]{width:%?50?%;height:%?50?%}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-right > uni-input[data-v-2100407d]{width:%?120?%;height:%?60?%;text-align:center;border:none;background:#fff;border-bottom:%?1?% solid #c8c8c8}.chooseNum .chooseNum-box .chooseNum-box-bottom[data-v-2100407d]{width:100%;padding:%?30?% 0;box-sizing:border-box}.chooseNum .chooseNum-box .chooseNum-box-bottom > uni-text[data-v-2100407d]:first-of-type{font-weight:700}.chooseNum .chooseNum-box .chooseNum-box-bottom > ul[data-v-2100407d]{margin-top:%?20?%}.chooseNum .chooseNum-box .chooseNum-box-bottom > ul > li[data-v-2100407d]{color:#999;font-size:%?30?%;-webkit-transform:translateX(%?-35?%);transform:translateX(%?-35?%);margin-bottom:%?15?%}.chooseNum .chooseNum-box .chooseNum-box-btn[data-v-2100407d]{width:100%;border-top:%?1?% solid #f1f1f1;padding:%?30?% 0;box-sizing:border-box;display:flex;align-items:center;justify-content:center}.chooseNum .chooseNum-box .chooseNum-box-btn > uni-button[data-v-2100407d]{width:80%;height:%?80?%;line-height:%?80?%;font-size:%?30?%;border-radius:%?100?%;color:#fff;background:#101010;border:none}body.?%PAGE?%[data-v-2100407d]{background-color:#101010}",""]),t.exports=i},"1de5":function(t,i,e){"use strict";t.exports=function(t,i){return i||(i={}),t=t&&t.__esModule?t.default:t,"string"!==typeof t?t:(/^['"].*['"]$/.test(t)&&(t=t.slice(1,-1)),i.hash&&(t+=i.hash),/["'() \t\n]/.test(t)||i.needQuotes?'"'.concat(t.replace(/"/g,'\\"').replace(/\n/g,"\\n"),'"'):t)}},"2ed0":function(t,i,e){"use strict";e.r(i);var o=e("34b5"),a=e("c6be");for(var n in a)"default"!==n&&function(t){e.d(i,t,(function(){return a[t]}))}(n);e("a060");var s,d=e("f0c5"),r=Object(d["a"])(a["default"],o["b"],o["c"],!1,null,"2100407d",null,!1,o["a"],s);i["default"]=r.exports},"34b5":function(t,i,e){"use strict";var o;e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return n})),e.d(i,"a",(function(){return o}));var a=function(){var t=this,i=t.$createElement,o=t._self._c||i;return o("v-uni-view",{staticClass:"detail"},[o("v-uni-image",{staticClass:"back-btn",attrs:{src:e("4404"),mode:"widthFix"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.goback.apply(void 0,arguments)}}}),o("v-uni-view",{staticClass:"F1 detail-image"},[o("v-uni-image",{staticClass:"F1-goods-img detail-img",attrs:{src:t.orderDetail.file_path,mode:"widthFix"}})],1),o("v-uni-view",{staticClass:"auth"},[o("v-uni-image",{attrs:{src:e("784f"),mode:"widthFix"}}),o("v-uni-text",[t._v("该作品拥有酷客艺术官方认证")])],1),o("v-uni-text",{staticClass:"goods-name"},[t._v(t._s(t.orderDetail.goods_name))]),o("v-uni-view",{staticClass:"sup-content"},[o("v-uni-view",{staticClass:"sup-content-list"},[o("v-uni-text",[t._v("发行")]),o("v-uni-text",[t._v(t._s(t.orderDetail.xn_sale)+"份")])],1)],1),o("v-uni-view",{staticClass:"line-style"}),o("v-uni-view",{staticClass:"list"},[o("v-uni-view",{staticClass:"list-item"},[o("v-uni-view",{staticClass:"icon-left"},[o("v-uni-view",{staticClass:"list-text"},[t._v("创作者")])],1),o("v-uni-view",{staticClass:"icon-right"},[t._v(t._s(t.orderDetail.author?t.orderDetail.author:"无"))])],1),o("v-uni-view",{staticClass:"list-item"},[o("v-uni-view",{staticClass:"icon-left"},[o("v-uni-view",{staticClass:"list-text"},[t._v("发行方")])],1),o("v-uni-view",{staticClass:"icon-right"},[t._v(t._s(t.orderDetail.c_name?t.orderDetail.c_name:"无"))])],1),o("v-uni-view",{staticClass:"list-item"},[o("v-uni-view",{staticClass:"icon-left"},[o("v-uni-view",{staticClass:"list-text"},[t._v("藏品编号")])],1),o("v-uni-view",{staticClass:"icon-right"},[t._v("#"+t._s(t.orderDetail.number+"/"+t.orderDetail.xn_sale))]),o("v-uni-text",{staticClass:"list-text",staticStyle:{right:"0",width:"45vw","text-align":"right"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.lookNumber()}}},[t._v("查看编号")])],1),o("v-uni-view",{staticClass:"list-item"})],1),o("v-uni-view",{staticClass:"story"},[o("v-uni-view",{staticClass:"story-content"},[o("v-uni-rich-text",{attrs:{nodes:t.orderDetail.content}})],1)],1),3!=t.orderDetail.type?o("v-uni-view",{staticClass:"submit other-submit"},[o("v-uni-view",{staticClass:"submit-left-icon"},[o("v-uni-view",{staticClass:"icon-left"},[t._v("￥")]),o("v-uni-view",{staticClass:"sub-price"},[t._v(t._s(t.orderDetail.price))])],1),[o("v-uni-view",{staticClass:"sub-btn",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.buy(t.orderDetail.goods_id)}}},[t._v("立即购买")])]],2):t._e(),t.showChoose?o("v-uni-view",{staticClass:"chooseNum"},[o("v-uni-view",{staticClass:"chooseNum-box"},[o("v-uni-view",{staticClass:"chooseNum-box-top"},[o("v-uni-text",[t._v("确认购买")]),o("v-uni-image",{attrs:{src:e("a007"),mode:"widthFix"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.showChoose=!1}}})],1),o("v-uni-view",{staticClass:"chooseNum-box-center"},[o("v-uni-view",{staticClass:"chooseNum-box-center-left"},[o("v-uni-text",[t._v("购买数量")])],1),o("v-uni-text",{staticStyle:{"margin-right":"60rpx"}},[t._v("1份")])],1),o("v-uni-view",{staticClass:"chooseNum-box-bottom"},[o("v-uni-text",[t._v("购买须知")]),o("ul",[o("li",[t._v("数字藏品一经售出，不支持退还，请查看作品详情谨慎购买")]),o("li",[t._v("付款时间5分钟，超过未支付订单将自动取消")])])],1),o("v-uni-view",{staticClass:"chooseNum-box-btn"},[o("v-uni-button",{on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.jumpPay.apply(void 0,arguments)}}},[t._v("立即购买")])],1)],1)],1):t._e(),o("div",{directives:[{name:"show",rawName:"v-show",value:t.showMask,expression:"showMask"}],staticClass:"mask",on:{touchmove:function(i){i.preventDefault(),arguments[0]=i=t.$handleEvent(i)}}},[o("v-uni-view",{staticClass:"maskcont"},[o("v-uni-view",{staticClass:"maskhead",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.closeMask.apply(void 0,arguments)}}},[o("v-uni-text",{staticClass:"maskhead-title"},[t._v("商品列表")]),o("v-uni-image",{staticClass:"close",attrs:{src:e("a44f")}})],1),t._l(t.numberInfoList,(function(i,e){return o("v-uni-view",{key:e,staticClass:"numbers"},[o("v-uni-view",{staticClass:"number-item"},[o("v-uni-view",{staticClass:"number-left"},[o("v-uni-view",{staticClass:"left-no"},[t._v("#"+t._s(i.number+"/"+i.xn_sale))]),1==i.status?o("v-uni-view",{staticClass:"left-status"},[o("v-uni-text",[t._v("寄售中")])],1):t._e()],1),o("v-uni-view",{staticClass:"number-right"},[i.price>0?o("v-uni-view",{staticClass:"right-price"},[o("v-uni-text",[t._v("￥"+t._s(i.price))])],1):t._e(),i.goods_price_min>0&&null==i.price?o("v-uni-view",{staticClass:"right-price"},[o("v-uni-text",[t._v("￥"+t._s(i.goods_price_min))])],1):t._e(),o("v-uni-text",{staticClass:"right-price",staticStyle:{"margin-top":"10rpx"}},[t._v("售价")])],1)],1)],1)}))],2)],1)],1)},n=[]},4404:function(t,i){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAApCAYAAAAxmNlDAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF3mlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNy4wLWMwMDAgNzkuMTM1N2M5ZSwgMjAyMS8wNy8xNC0wMDozOTo1NiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIyLjUgKFdpbmRvd3MpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAyMi0wNy0wM1QwMToxMSswODowMCIgeG1wOk1vZGlmeURhdGU9IjIwMjItMDktMTRUMTA6NDQ6MjErMDg6MDAiIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDktMTRUMTA6NDQ6MjErMDg6MDAiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIiBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NDA3NDY4YjYtZjg3MS1kODQzLThhZTMtY2I2NThmZjA2YmUxIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjZjZGY3NTZjLTEzZGItN2Q0ZS04N2JiLWMxMmY2YjY3ZjBmZSIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjZjZGY3NTZjLTEzZGItN2Q0ZS04N2JiLWMxMmY2YjY3ZjBmZSI+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNyZWF0ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6NmNkZjc1NmMtMTNkYi03ZDRlLTg3YmItYzEyZjZiNjdmMGZlIiBzdEV2dDp3aGVuPSIyMDIyLTA3LTAzVDAxOjExKzA4OjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjIuNSAoV2luZG93cykiLz4gPHJkZjpsaSBzdEV2dDphY3Rpb249InNhdmVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjQwNzQ2OGI2LWY4NzEtZDg0My04YWUzLWNiNjU4ZmYwNmJlMSIgc3RFdnQ6d2hlbj0iMjAyMi0wOS0xNFQxMDo0NDoyMSswODowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIyLjUgKFdpbmRvd3MpIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDwvcmRmOlNlcT4gPC94bXBNTTpIaXN0b3J5PiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pm9dzNcAAAEMSURBVEiJtdVBagJBEIXhf4JkZxYh4PECHiMQyLlyhtzAbMSFbnQVELPIczFKVLpnqrrKgll0VfPxpmnoThKJ9Ql8AW9dEjwD1kB3Wq8y4BmwuW0+3AMFDpHENVTAS2viQRTYtcCjKPjP2IR6YTPqgV2oFXajFrgJHYOb0SE4hNbgMAowcaBPwI8FhevEaeglnIoCdJKegW0mCn3i72wU+sR//D8p55pGUOgT/xb6ywh6hl8L/dq520sSkj5Uru1p7v4uF6n4bSMNLzVT8NogjA8NQ/jYhmbc8ls1fBeFm3DPFXLhrrvpwb2wGW+BTXgrPIpH4CF8EYWR9F6SM+ASvs+CkTSXtJe0kfR4BO4KWP7lPYfYAAAAAElFTkSuQmCC"},"4ff3":function(t,i,e){t.exports=e.p+"static/img/34_0_0.60a04349.png"},6684:function(t,i,e){"use strict";var o=e("4ea4");Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var a=o(e("7748")),n=(o(e("743a")),{data:function(){return{showChoose:!1,orderDetail:{file_path:"",goods_name:"",stock_total:"",content:"",author:"",goods_address:"",logo:"",startTime:"",goods_price_min:"",price:"",goods_id:""},showIcon:!1,startTime:"",userinfo:"",type:1,endTimeStatus:!1,has:"",configs:"",coll_id:0,is_give:0,status:0,price:"",is_box:0,showMask:!1,numberInfoList:[]}},onLoad:function(t){var i=this;this.configs=this.$config.ImgUrl,"null"!=t.has&&(this.has=t.has),t.coll_id&&(this.coll_id=t.coll_id),t.status&&(this.status=t.status),t.is_box&&(this.is_box=t.is_box),this.$request("get","order/details",{transactionId:t.id}).then((function(t){console.log("res",t),t.data.content=a.default.formatRichText(t.data.content),i.orderDetail=t.data,i.showIcon=!0})).catch((function(t){console.log("err",t)})),this.getUserInfo()},methods:{getUserInfo:function(){var t=this;this.$request("post","user/userinfo").then((function(i){200==i.status&&(uni.setStorageSync("userinfo",i.data),t.userinfo=i.data)}))},goback:function(){var t=getCurrentPages();if(t.length>1)uni.navigateBack(1);else{var i=this.$router.go(-1);void 0==i&&uni.switchTab({url:"/pages/index/index"})}},jumpPay:function(){var t=this;this.showChoose=!1;this.orderDetail.price;var i=uni.getStorageSync("token");uni.request({url:"".concat(this.$config.URL,"order/addcretetemporder"),header:{"Access-Token":i||"",platform:"H5"},method:"POST",data:{goods_id:this.orderDetail.goods_id,transaction_id:this.orderDetail.transaction_id},success:function(i){console.log(i);var e=i.data.status;if(200==e){var o=t.orderDetail.price,a=i.data.data.orderTime,n=i.data.data.order_id;uni.navigateTo({url:"./pay?price="+o+"&buyNumber=1&goods_id="+t.orderDetail.goods_id+"&transaction_id="+t.orderDetail.transaction_id+"&transactionTime="+a+"&order_id="+n})}else t.$showToast(i.data.message)},fail:function(t){}})},lookNumber:function(){var t=this;uni.request({url:"".concat(this.$config.URL,"order/marketdetails"),method:"POST",data:{goods_id:this.orderDetail.goods_id,number:this.orderDetail.number},success:function(i){console.log(i);var e=i.data.status;200==e?t.numberInfoList=i.data.data:t.$showToast(i.data.message),t.showMask=!0},fail:function(t){}})},closeMask:function(){this.showMask=!1},buy:function(t){this.showChoose=!0}}});i.default=n},7748:function(t,i,e){"use strict";function o(t){var i;return function(){var e=this,o=arguments;i&&clearTimeout(i),i=setTimeout((function(){t.apply(e,o)}),i)}}function a(t){return/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(t)}function n(t){return/^([\u4e00-\u9fa5]{1,20}|[a-zA-Z\.\s]{1,20})$/.test(t)}function s(t){var i=t.replace(/<img[^>]*>/gi,(function(t,i){return t=t.replace(/style="[^"]+"/gi,"").replace(/style='[^']+'/gi,""),t=t.replace(/width="[^"]+"/gi,"").replace(/width='[^']+'/gi,""),t=t.replace(/height="[^"]+"/gi,"").replace(/height='[^']+'/gi,""),t}));return i=i.replace(/style="[^"]+"/gi,(function(t,i){return t=t.replace(/width:[^;]+;/gi,"max-width:100%;").replace(/width:[^;]+;/gi,"max-width:100%;"),t})),i=i.replace(/<br[^>]*\/>/gi,""),i=i.replace(/\<img/gi,'<img style="max-width:100%;height:auto;display:block;margin-top:0;margin-bottom:0;"'),i}e("ac1f"),e("5319"),Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var d={debounce:o,checkIdCard:a,checkName:n,formatRichText:s};i.default=d},"784f":function(t,i){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAAAXNSR0IArs4c6QAABeRJREFUeF7tml1sFFUYht8zu5JWBUGwQUUMRTGRirutXlRJFDoLBkSIgN6IF1XanaICEsEYiIkgaI0JSNjZtiAExWARAlYUulNAgkg03VlLRZA/+VMolYAEsbW7x8y0VtrM7s45M7O1dOeu7fv9vM/55szs6RL08Iv0cP9IA0hPQA8nkL4FevgAdO0mmBcqukVbgFpf+aWuWoguuQU81cXjiCAUApisGyfYRFrImvDYQFWqQaQMQF6oaHBUcBcRSjXjt8cx2kiA1bFYrCIypuxwKmA4DsBb7Z8GgWimH2c0tI8IWB0eLZczxjHJHQEwYrs/x+USXgL01b6BqSMDMQVdByIEIgWBvVZzdY63FYBHkWaAwk8IcuxutC3fSVBU/JnZtOzQyA8v21HDMgDP9pJHBDdmUkqfsaMhszkosBM0tiziK9tiNsZIxwUgv3J25l/9m2aC0lcSbGhW+mKJbQFIoAV02X5RPsYS2PoAYry8ivQVgCcYw1IipxT1AH054gvuMluQBwA1m7wrdJTSUWkA6QlI3wLX1x7w/GARs+59GmXHt6Ls2NaEW8t1twcUZ49H8ZDx7abn7V+JUEM4LoTrCoBmXANw7fV/AKB9du/j9CPOyHz1uVq8Xr8qYWmBuO+pLVh+1Gx/HO8B/lMAGWS2AI+u89hrObad+x5v1K9Omu5yszvjyLjlTUmFbQJ2ADVSPSiGmy3AqjMyv/Xsd1jw45rkqSjOqz45K7nwPwU7AKVkL0DzExXRTPiycnGh+TIqT38NpUE11ZOR+arf9uHNA2tNxQMkrIqBPJNiXcYMwBPyf0oISfjJrzx3Fh7qN0wvEKUxzKkrw+7G/Qn7MjK/+de9eOunj037oZRWRnzBZ00H8ADwhqT5IFiYqMi1ADTd1Wgz5tQFse/CQcMwI/Mbz+zB2wc/YfECUCxQffIiliCOCSieSIiwOVERMcuLJTkvwEWEdtmlv6/g1boyqBePdAg1Mr/h9G4sObSexYeupTQ2ifV8gBnAA4qU7QaSPmYeGzACS3IKkeHq1W7kfNMlzK4L4sAfJ/TfGZlff2oXSn+uZDavBbQAQ1nPBJgBaIW8inQewIBkXeb3vx/v5BSit/vGdumZq79jdp2Mgixvhzc8TbDu5A68f/izZGnj/b1RFeXbWIP5ANRIFaB40Uyxh/sN02+HW3v1bpcfv3IWQ24a2CF87UkFSw9vMpPSWEOwUi2Qp7Mm4AOglEwB6AazxTx9h2Lx8EIMzOhnGLLmRDU+OJJwWzFRikxVxQDz+HABGLHdn+VykTMA3CY60yXD+9yNxTmFuCuz45Su+mUbVhz93GyaeLqWaJTeWTc22MCaiAtA2z6gdT2BpeCwmwfpELLbxr/8+JcIHvuCJUU8bZUqyk/xJOIGkKv4n6QgzP/LuyOjv7771zSoSV+OzBoioBPCYpCLJDcA3ikwa4pBx736Wg1LAHingMFcUqmV1bcMoHUKSjYDdGLSTh0RkC2qGJhkJbWlCdAKe6r9jxKB7LHSBG8sjdGRkTHBb3jjbZkAHYJS8i4BnWulEdZYClIaEQPzWOM66y1PgJYwr6poQDTTtYcA91ltyEw8BQ65rkZH1k4obzSjT6SxBYBWILdaeo4K+MhqQ2biSQzTwmNk8wcFCZLaBkDfEEPSQhDMN2OCW0OxSPXJC7jjOwXaCkCfBEWqpMBUuxq8Ng8BNoRF2dbvIdgOoPXRKNUAGG0zhB2qKBfYnNPai1CiZryKtJPji1HxUu5SRXmU3eZtewzGa8wmCI6ZdxxA2+1gZRIcNZ8SAG0bYykFXmMZYQK8FxZlx1+uHNkEjYx6Q/7JIMTciQ2lU1RfcCMLMF5tygC03g4z8oGYDODBOA3/AAiSKq74ltcQa1xKAWjNeXbO6ivEmksppR0OMAkhFTGh19zIqKUXWU1Y0accwL/NehRpEgEqtJ8pMD0iylZPRbk4dBkArm4dCEoDcABqt0qZnoButVwONJueAAegdquU6QnoVsvlQLP/AHry+1CAjIkPAAAAAElFTkSuQmCC"},a007:function(t,i,e){t.exports=e.p+"static/img/close.5b6ff465.png"},a060:function(t,i,e){"use strict";var o=e("ca3b"),a=e.n(o);a.a},a44f:function(t,i){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAAXNSR0IArs4c6QAABVRJREFUaEPtWWloHVUUPmdeXh74p6CIVkxdcEnenEkIwVZQ/2ixKlQUxAVrwb21EaxL0V+2vxRrVbBxr4KtuCAoBqwNbf9YQS0hJHPmJRpxxwVRyB8hy8yR+7jzvJnM+pIXifRCGF7mnuW7Z7l3vouwwgeucP/hBIAwgtVq9XTLsq4HgHMRcZWIrAIA9afGFCJOicgUAHwbBMEHtVrtt6WI/qIiQESnicgNiLgBADYWdGhQRA4h4vvM/HtB2cb0pgD09fWdND09vQ0A+gFgTbPGtdyPALC3UqkMDA8P/11UV2EARLQFAJTzFGPsGAAcQcRJ3/e/UU81R0TOL5VK56knAFwBAJfGyDIADDDzS0VAFAJARG8CwG0RA0MAcMD3/cPj4+O/5jHe1dW1ulQqrQeATQBwZURmPzNvzqNHzckNgIhGAaDbUDyJiHtc1305r7G4eY7j3CsiDwGAik44xpi5J4/eXACISCLK9pXL5cdGRkb+yGMka05vb++ps7OzTwDAneZcZs70L3OCbdsfI+LVoWJEfNB13WeznGrmveM420XkmVBWRA56nndNmq5UAET0FAA8EirIsyLNOB6ViUR8NzPvSNKbCEB3mxcNwQ3MrAq25YOIVGEfMgxtTepOsQB0n/8ibJUqrJ7nqUJbtmHb9h6VrtogVyqVdXH7RCwAIlJpo9JHjclyuXxJUsF2dnaePTEx8X0zyNJkdWF/ZnSnHcy8O2pnAQB1PACAL8MdFhG3JLVKI1d3MfPOIiCISM1/XMkk1ZZuseHGpnbstdFjxwIAtm0/gIhhlxliZnXOWTDU6rW1tX1nvMgNwnReyc/NzZ2TFEUiUrVQ3+xEZLvnec+ZziwA4DjOERG5XE1CxDtc130jaWWjjgBAJoiiMo7j3C4ir2t/jrquq44ijTEPQHd395lBEPxkvF/DzObvBViKOFRkbmiIiDoAQKVPfViW1TE2NvZz+HseAMdx+kXkef3yODOvzZPXMY7tZOZdpiwRqXw36yQzWgYIVZMX6Sjc77ru3lgARKSq/GH9MrcBNT8NxGKcj9H9NDM3NtdoBF4Rkbs10k2u676VJwLGSjU6S7gI+lnvNnosiE6WDcdxbhWRA9qvV13XvScpAu8CwI3qZRAEF9dqNbWZFRoxkTDlCzuvhKvV6jrLsj7Xit5j5puSAHwCAPW2KSKneJ73VyHv9eQEEIVS0rRr2/bJiPin/t8hZr7qfwug1SnUVBRyp5DjOMtRxIVBFCnipWyjDUfz7BNptRaRT26jRLQVAF7Qyo4x82V5ijhPn18MCCL61GAy7mPmxnfKvH1As2sNZsH3/TOymIY8zqfsE5ltVTMYv4Q6giBYbbJ6ccfpjwyWbTMz70+KQhHnmwVBRIrGUXSOGoPMfK3pT9xxehsihmeNxON0T0/PBb7vf2Uoy12c0XQqlUoXjo6Ofh23UJHjdL/neQOpAFb8B41CV+STUkUiafWyGkCabNOflMpo9KMeAPYx811ZDi3leyJ6zSC6in3U6ygoEvffdtVCQisKPEpwAUAxWsXoGPPI3P+I2EolezOpxRhSt2UEVwyhlUnyZgLQ6TSP3FVEV3t7+5NLSe7OzMw8ahBZ9STIE/FcAJSyKMmrCK9W0et5SN3Y74GsLhIle/X8IUR8R0QOZzEYRm11IOJ6Ebk55oIjlcyN+pg7AobxtCum4wCg6Pi0KyZFl9cZhsho/RVTaHBFX/KZq6avWW+xLGtjyOZlpWEjdxGPBkEwiIhvL/s1a5yTitUTketE5Ky0i25E/AERPzTZtbyg4+YVroHFGGuF7AkArVjVIjr/ASi/80+76THYAAAAAElFTkSuQmCC"},c6be:function(t,i,e){"use strict";e.r(i);var o=e("6684"),a=e.n(o);for(var n in o)"default"!==n&&function(t){e.d(i,t,(function(){return o[t]}))}(n);i["default"]=a.a},ca3b:function(t,i,e){var o=e("0655");o.__esModule&&(o=o.default),"string"===typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);var a=e("4f06").default;a("b6b008f6",o,!0,{sourceMap:!1,shadowMode:!1})}}]);