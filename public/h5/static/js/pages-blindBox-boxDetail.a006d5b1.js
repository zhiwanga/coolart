(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-blindBox-boxDetail"],{"083c":function(t,e,o){"use strict";var i;o.d(e,"b",(function(){return a})),o.d(e,"c",(function(){return n})),o.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"detail"},[i("v-uni-image",{staticClass:"detail-image",attrs:{src:t.goodsinfo.thumb,mode:"widthFix"}}),i("v-uni-view",{staticClass:"story-title"},[t._v(t._s(t.goodsinfo.blind_name))]),i("v-uni-view",{staticClass:"story-child"},[i("v-uni-view",{staticClass:"story-child-item"},[i("v-uni-text",[t._v("已售:")]),i("v-uni-text",[t._v(t._s(t.goodsinfo.sales))])],1),i("v-uni-view",{staticClass:"story-child-item"},[i("v-uni-text",[t._v("发行:")]),i("v-uni-text",[t._v(t._s(t.goodsinfo.get_total))])],1)],1),i("v-uni-text",{staticClass:"aside"},[t._v("///////////////////////////////////////////////////////////////")]),i("v-uni-view",{staticClass:"story-title-box"},[i("v-uni-text",[t._v("作品描述")]),i("v-uni-text",[t._v("您可能抽到以下作品的"),i("b",{staticClass:"big"},[t._v("1")]),t._v("个")])],1),i("v-uni-view",{staticClass:"goods-list"},t._l(t.goodsinfo.goods,(function(e,o){return i("v-uni-view",{key:o,staticClass:"goods-list-item"},[i("v-uni-view",{staticClass:"goods-list-item-left"},[i("v-uni-text",{staticClass:"goods-list-item-left-top"},[t._v(t._s(e.goods_name))]),i("v-uni-view",{staticClass:"goods-list-item-left-bottom"},[i("v-uni-view",{staticClass:"goods-list-item-left-bottom-box"},[i("v-uni-text",[t._v("概率:")]),i("v-uni-text",[t._v(t._s(e.probability)+"%")])],1),i("v-uni-view",{staticClass:"goods-list-item-left-bottom-box"},[i("v-uni-text",[t._v("原价:")]),i("v-uni-text",[t._v("￥"+t._s(e.goods_price_min))])],1)],1)],1),i("v-uni-image",{staticClass:"goods-list-item-right",attrs:{src:e.goods_image,mode:"heightFix"}})],1)})),1),i("v-uni-view",{staticClass:"story-title"},[t._v("细节展示")]),i("v-uni-view",{staticClass:"story"},[i("v-uni-view",{staticClass:"story-content"},[i("v-uni-rich-text",{attrs:{nodes:t.goodsinfo.content}})],1)],1),i("v-uni-view",{staticClass:"submit",style:{"justify-content":"space-between"}},[i("v-uni-view",{staticClass:"sub-price"},[t._v("￥"+t._s(t.goodsinfo.price))]),t.isEnding?[i("v-uni-view",{staticClass:"sub-btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.login.apply(void 0,arguments)}}},[t._v("已结束销售")])]:[t.showIcon&&t.goodsinfo.total>0?i("v-uni-view",{staticClass:"sub-btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.showChoose=!0}}},[t._v("立即购买")]):t.showIcon&&0==t.goodsinfo.total?i("v-uni-view",{staticClass:"sub-btn"},[t._v("已售罄")]):t._e()]],2),t.showChoose?i("v-uni-view",{staticClass:"chooseNum"},[i("v-uni-view",{staticClass:"chooseNum-box"},[i("v-uni-view",{staticClass:"chooseNum-box-top"},[i("v-uni-text",[t._v("确认购买")]),i("v-uni-image",{attrs:{src:o("a007"),mode:"widthFix"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.showChoose=!1}}})],1),i("v-uni-view",{staticClass:"chooseNum-box-center"},[i("v-uni-view",{staticClass:"chooseNum-box-center-left"},[i("v-uni-text",[t._v("购买数量")]),i("v-uni-text",[t._v("(限购"+t._s(t.limitCount)+"份)")])],1),i("v-uni-view",{staticClass:"chooseNum-box-center-right"},[i("v-uni-image",{attrs:{src:o("e5e2"),mode:"widthFix"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeNum(1)}}}),i("v-uni-input",{attrs:{type:"number"},model:{value:t.buyNumber,callback:function(e){t.buyNumber=e},expression:"buyNumber"}}),i("v-uni-image",{attrs:{src:o("c7c5"),mode:"widthFix"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeNum(2)}}})],1)],1),i("v-uni-view",{staticClass:"chooseNum-box-center"},[i("v-uni-view",{staticClass:"chooseNum-box-center-left"},[i("v-uni-text",[t._v("价格")]),i("v-uni-text")],1),i("v-uni-view",{staticClass:"chooseNum-box-center-right"},[i("v-uni-text",[t._v("￥"+t._s(t.price))])],1)],1),i("v-uni-view",{staticClass:"chooseNum-box-bottom"},[i("v-uni-text",[t._v("购买须知")]),i("ul",[i("li",[t._v("数字藏品一经售出，不支持退还，请查看作品详情谨慎购买")]),i("li",[t._v("开奖后请到我的藏品-盲盒查看")])])],1),i("v-uni-view",{staticClass:"chooseNum-box-btn"},[i("v-uni-button",{on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.jumpPay.apply(void 0,arguments)}}},[t._v("立即购买")])],1)],1)],1):t._e(),i("uniPopup",{ref:"lotteryPopup",staticClass:"lottery-popup",attrs:{type:"center"},on:{confirm:function(e){arguments[0]=e=t.$handleEvent(e),t.confirm.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"lotterying-title"},[t._v(t._s(t.isShowWinner?"恭喜获得以下奖品":"盲盒正在开奖中"))]),i("v-uni-image",{staticClass:"lotterying-head",attrs:{src:o("95d7"),mode:""}}),i("v-uni-view",{staticClass:"lotterying"},[i("v-uni-view",{staticClass:"iconfont lotterying-close-icon",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeDialog.apply(void 0,arguments)}}},[t._v("")]),i("v-uni-view",{staticClass:"lotterying-prize-winner"},[t.isShowWinner?t._l(t.prizeWinnerArr,(function(e,o){return i("v-uni-view",{key:o},[i("v-uni-view",{staticClass:"price-reward"},[i("v-uni-image",{attrs:{src:e.url,mode:""}}),i("v-uni-text",[t._v(t._s(e.name))])],1)],1)})):i("v-uni-view",t._l(t.lotteryJoinList,(function(e,o){return o<8?i("v-uni-image",{key:o,attrs:{src:e,mode:""}}):t._e()})),1)],2)],1)],1)],1)},n=[]},"51d3":function(t,e,o){"use strict";o.r(e);var i=o("e8d7"),a=o.n(i);for(var n in i)"default"!==n&&function(t){o.d(e,t,(function(){return i[t]}))}(n);e["default"]=a.a},"5dcf":function(t,e,o){var i=o("e560");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=o("4f06").default;a("1b4da4a2",i,!0,{sourceMap:!1,shadowMode:!1})},"8ee6":function(t,e,o){"use strict";var i=o("5dcf"),a=o.n(i);a.a},"95d7":function(t,e,o){t.exports=o.p+"static/img/reward_bg.f24f21a8.png"},"9efe":function(t,e,o){"use strict";o.r(e);var i=o("083c"),a=o("51d3");for(var n in a)"default"!==n&&function(t){o.d(e,t,(function(){return a[t]}))}(n);o("8ee6");var s,r=o("f0c5"),d=Object(r["a"])(a["default"],i["b"],i["c"],!1,null,"4aa87f12",null,!1,i["a"],s);e["default"]=d.exports},b85c:function(t,e,o){"use strict";o("a4d3"),o("e01a"),o("d28b"),o("d3b7"),o("3ca3"),o("ddb0"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=n;var i=a(o("06c5"));function a(t){return t&&t.__esModule?t:{default:t}}function n(t,e){var o;if("undefined"===typeof Symbol||null==t[Symbol.iterator]){if(Array.isArray(t)||(o=(0,i.default)(t))||e&&t&&"number"===typeof t.length){o&&(t=o);var a=0,n=function(){};return{s:n,n:function(){return a>=t.length?{done:!0}:{done:!1,value:t[a++]}},e:function(t){throw t},f:n}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var s,r=!0,d=!1;return{s:function(){o=t[Symbol.iterator]()},n:function(){var t=o.next();return r=t.done,t},e:function(t){d=!0,s=t},f:function(){try{r||null==o["return"]||o["return"]()}finally{if(d)throw s}}}}},e560:function(t,e,o){var i=o("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-4aa87f12]{background:#101010}.detail-image[data-v-4aa87f12]{width:100%}.detail[data-v-4aa87f12]{width:100%;padding:0 0 %?120?%;box-sizing:border-box;overflow-x:hidden}.detail .back-btn[data-v-4aa87f12]{width:%?25?%;height:%?25?%;position:absolute;left:%?40?%;top:%?60?%;z-index:1000}.detail .detail-image[data-v-4aa87f12]{width:100%;text-align:center;-webkit-transform:translateZ(0);transform:translateZ(0)}.detail .detail-image .detail-img[data-v-4aa87f12]{-webkit-animation:detail-data-v-4aa87f12 20s infinite;animation:detail-data-v-4aa87f12 20s infinite;-webkit-transform:translateZ(%?2?%);transform:translateZ(%?2?%)}.detail .F1[data-v-4aa87f12]{width:100%;position:relative}.detail .F1 .F1-bg[data-v-4aa87f12]{width:100%;position:relative;z-index:1}.detail .F1 .F1-goods-img[data-v-4aa87f12]{width:50%;position:absolute;z-index:2;left:25%;top:25%}.detail .F1 .F1-goods-name[data-v-4aa87f12]{width:60%;left:20%;display:block;position:absolute;bottom:%?120?%;z-index:3;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#fff;font-size:%?40?%}.detail .F1 .F1-goods-bottom[data-v-4aa87f12]{width:40%;left:30%;display:block;position:absolute;bottom:%?50?%;padding:%?5?% %?20?%;box-sizing:border-box;border-radius:%?10?%;z-index:3;text-align:center;background:linear-gradient(90deg,#ebc696,#f4dfb7);color:#8b5704}.detail .list[data-v-4aa87f12]{width:90%;margin:%?20?% auto;background:#36393c;border-radius:%?20?%;position:relative}.detail .list .list-bg[data-v-4aa87f12]{display:block;height:%?150?%;position:absolute;right:%?120?%;top:%?80?%}.detail .list .list-item[data-v-4aa87f12]{display:flex;justify-content:space-between;align-items:center;margin-bottom:%?20?%;padding:%?30?% %?30?%;border-bottom:%?1?% solid #434548;color:#fff;font-size:15px;color:#9d9d9d}.detail .list .list-item[data-v-4aa87f12]:last-of-type{border:none}.detail .list .icon-right[data-v-4aa87f12]{color:#fff}.detail .goods-list[data-v-4aa87f12]{width:100%;margin-top:%?30?%;padding:0 %?50?%;box-sizing:border-box;display:flex;align-items:center;flex-wrap:wrap}.detail .goods-list .goods-list-item[data-v-4aa87f12]{width:100%;margin-bottom:%?20?%;padding:%?20?%;box-sizing:border-box;background:#f7f5fa;border-radius:%?10?%;display:flex;align-items:center;justify-content:space-between}.detail .goods-list .goods-list-item .goods-list-item-left .goods-list-item-left-top[data-v-4aa87f12]{display:block;color:#000;font-weight:700}.detail .goods-list .goods-list-item .goods-list-item-left .goods-list-item-left-bottom[data-v-4aa87f12]{display:flex;align-items:center;font-size:%?25?%;margin-top:%?20?%}.detail .goods-list .goods-list-item .goods-list-item-left .goods-list-item-left-bottom .goods-list-item-left-bottom-box[data-v-4aa87f12]{margin-right:%?40?%}.detail .goods-list .goods-list-item .goods-list-item-left .goods-list-item-left-bottom .goods-list-item-left-bottom-box > uni-text[data-v-4aa87f12]:nth-of-type(1){color:#97959a}.detail .goods-list .goods-list-item .goods-list-item-left .goods-list-item-left-bottom .goods-list-item-left-bottom-box > uni-text[data-v-4aa87f12]:nth-of-type(2){color:#000;margin-left:%?10?%;display:inline-block}.detail .goods-list .goods-list-item .goods-list-item-right[data-v-4aa87f12]{height:%?50?%}.detail .story-title[data-v-4aa87f12]{width:100%;padding:0 %?50?%;font-size:17px;font-weight:700;margin-top:%?20?%;color:#fff}.detail .story-title-box[data-v-4aa87f12]{width:100%;box-sizing:border-box;padding:0 %?50?%;margin-top:%?40?%;display:flex;align-items:center;justify-content:space-between}.detail .story-title-box > uni-text[data-v-4aa87f12]:first-of-type{font-size:17px;font-weight:700;color:#fff}.detail .story-title-box > uni-text[data-v-4aa87f12]:last-of-type{color:#858585;font-size:%?25?%}.detail .story-title-box .big[data-v-4aa87f12]{color:#fff;display:inline-block;margin:0 %?10?%}.detail .story-child[data-v-4aa87f12]{width:100%;margin-top:%?20?%;padding:0 %?50?%;box-sizing:border-box;display:flex;align-items:center}.detail .story-child .story-child-item[data-v-4aa87f12]{display:flex;align-items:center;margin-right:%?40?%;color:#858585;font-size:%?25?%}.detail .aside[data-v-4aa87f12]{width:calc(100% - %?50?%);margin:%?20?% auto 0;box-sizing:border-box;display:block;overflow:hidden;color:#858585;opacity:.5}.detail .story[data-v-4aa87f12]{width:100%;border-radius:%?30?%;margin-top:%?20?%;color:#fff;padding:%?30?% %?50?%;margin-bottom:%?30?%;box-sizing:border-box}.detail .story .story-author[data-v-4aa87f12]{display:flex;align-items:center;justify-content:space-between;padding:%?30?% %?20?% %?20?%;color:#a3a3a3;font-size:%?35?%}.detail .story .story-author .story-author-text[data-v-4aa87f12]{color:#000}.detail .story .story-content img[data-v-4aa87f12]{width:100%}.detail .submit[data-v-4aa87f12]{position:fixed;bottom:0;left:0;width:100%;display:flex;justify-content:center;align-items:center;padding:%?20?% %?30?%;background:#3e3e3e;color:#fff;font-size:%?50?%;font-weight:700;box-sizing:border-box}.detail .submit .sub-price[data-v-4aa87f12]{letter-spacing:%?5?%;color:#fff;margin-left:%?50?%}.detail .submit .sub-btn[data-v-4aa87f12]{font-size:%?30?%;background:#101010;color:#fff;border-radius:%?50?%;padding:%?20?% %?60?%;text-align:center}.detail .submit .sub-btn-other[data-v-4aa87f12]{width:45%;margin:0 %?20?%}.detail .submit .timeIcon[data-v-4aa87f12]{display:flex;align-items:center}.detail .submit .timeIcon .icon[data-v-4aa87f12]{width:%?36?%;height:%?36?%;margin-right:%?6?%}.detail .submit .timeIcon .icon uni-image[data-v-4aa87f12]{width:100%;height:100%}@-webkit-keyframes detail-data-v-4aa87f12{0%{-webkit-transform:rotateY(0);transform:rotateY(0)}50%{-webkit-transform:rotateY(1turn);transform:rotateY(1turn)}100%{-webkit-transform:rotateY(0);transform:rotateY(0)}}@keyframes detail-data-v-4aa87f12{0%{-webkit-transform:rotateY(0);transform:rotateY(0)}50%{-webkit-transform:rotateY(1turn);transform:rotateY(1turn)}100%{-webkit-transform:rotateY(0);transform:rotateY(0)}}.back[data-v-4aa87f12]{width:100%;height:100%;background-color:rgba(0,0,0,.5);position:fixed;top:0;left:0;display:flex;justify-content:center;align-items:center;z-index:999}.back .change[data-v-4aa87f12]{width:86%;padding:%?30?%;background-color:#fff;border-radius:%?30?%;box-sizing:border-box}.back .change .title[data-v-4aa87f12]{text-align:center}.back .change .ipt[data-v-4aa87f12]{margin-top:%?40?%;margin-left:%?20?%;border-bottom:%?2?% solid silver}.back .change .item[data-v-4aa87f12]{width:100%;display:flex;justify-content:center;align-items:center;margin-top:%?30?%;padding:%?10?%}.back .change .item .img[data-v-4aa87f12]{width:%?84?%;height:%?64?%;margin-right:%?20?%}.back .change .item .img uni-image[data-v-4aa87f12]{width:100%;height:100%}.back .change .active[data-v-4aa87f12]{border:%?4?% dashed #f3e0bc;box-sizing:border-box}.back .yes[data-v-4aa87f12]{width:80%;height:%?70?%;line-height:%?70?%;text-align:center;background-color:#f3e0bc;color:#000;margin:0 auto;margin-top:%?40?%;border-radius:%?34?%}.chooseNum[data-v-4aa87f12]{width:100%;height:100%;background:rgba(0,0,0,.5);display:flex;align-items:flex-end;justify-content:center;position:fixed;left:0;right:0;bottom:0;top:0;z-index:999}.chooseNum .chooseNum-box[data-v-4aa87f12]{width:100%;background:#fff;border-top-left-radius:%?20?%;border-top-right-radius:%?20?%;padding:%?20?% %?40?%;box-sizing:border-box}.chooseNum .chooseNum-box .chooseNum-box-top[data-v-4aa87f12]{width:100%;padding-bottom:%?30?%;box-sizing:border-box;border-bottom:%?1?% solid #f1f1f1;display:flex;align-items:center;justify-content:space-between}.chooseNum .chooseNum-box .chooseNum-box-top > uni-text[data-v-4aa87f12]{font-weight:700}.chooseNum .chooseNum-box .chooseNum-box-top > uni-image[data-v-4aa87f12]{width:%?40?%;height:%?40?%}.chooseNum .chooseNum-box .chooseNum-box-center[data-v-4aa87f12]{width:100%;padding-bottom:%?30?%;padding-top:%?30?%;box-sizing:border-box;border-bottom:%?1?% solid #f1f1f1;display:flex;align-items:center;justify-content:space-between}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-left[data-v-4aa87f12]{display:flex;align-items:center}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-left > uni-text[data-v-4aa87f12]:first-of-type{font-weight:700}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-left > uni-text[data-v-4aa87f12]:last-of-type{color:#999;display:block;margin-left:%?20?%;font-size:%?25?%}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-right[data-v-4aa87f12]{display:flex;align-items:center}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-right > uni-image[data-v-4aa87f12]{width:%?50?%;height:%?50?%}.chooseNum .chooseNum-box .chooseNum-box-center .chooseNum-box-center-right > uni-input[data-v-4aa87f12]{width:%?120?%;height:%?60?%;text-align:center;border:none;background:#fff;border-bottom:%?1?% solid #c8c8c8}.chooseNum .chooseNum-box .chooseNum-box-bottom[data-v-4aa87f12]{width:100%;padding:%?30?% 0;box-sizing:border-box}.chooseNum .chooseNum-box .chooseNum-box-bottom > uni-text[data-v-4aa87f12]:first-of-type{font-weight:700}.chooseNum .chooseNum-box .chooseNum-box-bottom > ul[data-v-4aa87f12]{margin-top:%?20?%}.chooseNum .chooseNum-box .chooseNum-box-bottom > ul > li[data-v-4aa87f12]{color:#999;font-size:%?30?%;-webkit-transform:translateX(%?-35?%);transform:translateX(%?-35?%);margin-bottom:%?15?%}.chooseNum .chooseNum-box .chooseNum-box-btn[data-v-4aa87f12]{width:100%;border-top:%?1?% solid #f1f1f1;padding:%?30?% 0;box-sizing:border-box;display:flex;align-items:center;justify-content:center}.chooseNum .chooseNum-box .chooseNum-box-btn > uni-button[data-v-4aa87f12]{width:80%;height:%?80?%;line-height:%?80?%;font-size:%?30?%;border-radius:%?100?%;color:#fff;background:#101010;border:none}.lottery-main[data-v-4aa87f12]{display:flex;margin-bottom:%?20?%;align-items:center}.lottery-main uni-image[data-v-4aa87f12]{width:%?80?%;height:%?80?%;border-radius:50%}.lottery-main .name[data-v-4aa87f12]{margin:0 %?20?%}.lottery-popup .lotterying-title[data-v-4aa87f12]{position:relative;bottom:%?-128?%;text-align:center;z-index:1;font-size:%?32?%;font-family:PingFang-SC-Bold,PingFang-SC;font-weight:700;color:#fff;line-height:%?32?%;text-shadow:0 %?2?% %?2?% rgba(224,99,35,.5)}.lottery-popup .lotterying-head[data-v-4aa87f12]{width:%?408?%;height:%?93?%;position:relative;bottom:%?-64?%;left:50%;-webkit-transform:translate(-50%);transform:translate(-50%)}.lottery-popup .lotterying[data-v-4aa87f12]{width:%?590?%;padding:%?100?% 0 %?36?%;background:#fefced;border-radius:%?30?%;border:%?20?% solid #ffe499}.lottery-popup .lotterying .see-winner-footer[data-v-4aa87f12]{display:flex;align-items:center;justify-content:space-between}.lottery-popup .lotterying .lotterying-close-icon[data-v-4aa87f12]{color:#ffe499;position:absolute;top:%?160?%;right:%?40?%}.lottery-popup .lotterying .see-winner-btn[data-v-4aa87f12]{margin:0 auto;width:%?250?%;height:%?72?%;border-radius:%?40?%;border:%?2?% solid #ffaa1d;font-size:%?26?%;font-family:PingFangSC-Regular,PingFang SC;font-weight:400;color:#ffaa1d;text-align:center;line-height:%?72?%}.lottery-popup .lotterying .lotterying-prize-winner[data-v-4aa87f12]{display:flex;align-items:center;justify-content:center;flex-wrap:wrap;width:calc(100% + %?44?%)}.lottery-popup .lotterying .lotterying-prize-winner uni-image[data-v-4aa87f12]{margin-right:%?44?%;margin-bottom:%?44?%;width:%?90?%;height:%?90?%;border:%?4?% solid #fff1cc;border-radius:50%}.lottery-popup .lotterying .lotterying-prize-winner .price-reward[data-v-4aa87f12]{width:%?92?%;margin-right:%?44?%;display:flex;flex-direction:column}.lottery-popup .lotterying .lotterying-prize-winner .price-reward uni-text[data-v-4aa87f12]{width:100%}body.?%PAGE?%[data-v-4aa87f12]{background:#101010}',""]),t.exports=e},e8d7:function(t,e,o){"use strict";var i=o("4ea4");o("99af"),o("7db0"),o("a9e3"),o("d3b7"),o("e25e"),o("ac1f"),o("25f0"),o("4d90"),o("5319"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i(o("b85c"));o("96cf");var n=i(o("1da1")),s=(i(o("743a")),i(o("df68"))),r={components:{uniPopup:s.default},data:function(){return{goodsinfo:{},userinfo:{},showIcon:!1,showChoose:!1,buyNumber:0,status:0,charges:"",copyright:"",price:0,thenCount:0,limitCount:0,isEnding:!1,lotteryJoinList:[],isShowWinner:!1,prizeWinnerArr:[],lottery_timer:null,rewards:[]}},onShow:function(){uni.getStorageSync("userinfo")&&(this.userinfo=uni.getStorageSync("userinfo"))},onLoad:function(t){this.id=t.id,this.getThenCount()},methods:{getThenCount:function(){var t=this;return(0,n.default)(regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:t.$request("get","user/extensioncount",{}).then((function(e){t.thenCount=e.data.count,t.getDetail()})).catch((function(t){console.log("err",t),that.$showToast(t.message)}));case 1:case"end":return e.stop()}}),e)})))()},getDetail:function(){var t=this;this.$request("get","blind/detail",{blindid:this.id}).then((function(e){t.goodsinfo=e.data.goodsinfo;var o,i=(0,a.default)(t.goodsinfo.goods);try{for(i.s();!(o=i.n()).done;){var n=o.value;n.probability=Number(n.xn_sale/t.goodsinfo.get_total*100).toFixed(2)}}catch(f){i.e(f)}finally{i.f()}t.limitCount=t.goodsinfo.get_limit+t.thenCount,uni.setNavigationBarTitle({title:t.goodsinfo.blind_name});var s=new Date(e.data.time.replace(/-/g,"/")).getTime(),r=new Date(e.data.goodsinfo.starttime.replace(/-/g,"/")).getTime(),d=new Date(e.data.goodsinfo.endtime.replace(/-/g,"/")).getTime();t.lotteryJoinList=[],t.rewards=[];var l,c=(0,a.default)(t.goodsinfo.goods);try{for(c.s();!(l=c.n()).done;){var u=l.value;t.rewards.push({id:Number(u.goods_id),name:u.goods_name,url:u.goods_image}),t.lotteryJoinList.push(u.goods_image)}}catch(f){c.e(f)}finally{c.f()}t.showIcon=s>r,t.isEnding=d<s})).catch((function(t){console.log("err",t)}))},formateSeconds:function(t){var e=parseInt(t/1e3),o=0,i=0,a="";return e>60&&(o=parseInt(e/60),e=parseInt(e%60),o>60&&(i=parseInt(o/60),o=parseInt(o%60))),a="".concat(i.toString().padStart(2,"0"),":").concat(o.toString().padStart(2,"0"),":").concat(e.toString().padStart(2,"0")),a},changeNum:function(t){1==t?this.buyNumber=0!=this.buyNumber?this.buyNumber-1:0:this.buyNumber++,this.price=Number(this.buyNumber*this.goodsinfo.price).toFixed(2)},jumpPay:function(){return 0==this.buyNumber?this.$showToast("请输入购买份数"):this.buyNumber>this.limitCount?this.$showToast("限购"+this.limitCount+"份"):(this.showChoose=!1,void this.payBox())},payBox:function(){var t=this;this.$request("post","blindpay/pay",{blind_id:this.id,order_id:this.order_id,pay_type:"balance",total:this.buyNumber}).then((function(e){if(console.log(e.data),t.prizeWinnerArr=[],200!=e.status)return t.$showToast(e.data.message);var o,i=(0,a.default)(e.data.data);try{var n=function(){var e=o.value;s=t.rewards.find((function(t){return Number(t.id)==e})),s&&t.prizeWinnerArr.push(s)};for(i.s();!(o=i.n()).done;){var s;n()}}catch(r){i.e(r)}finally{i.f()}t.startLottery()})).catch((function(e){console.log("err",e),t.$showToast(e.message)}))},startLottery:function(){this.isShowWinner=!1,this.$refs.lotteryPopup.open();var t=this;t.lottery_timer=setInterval((function(){for(var e=0;e<8;e++){var o=t.random(),i=t.lotteryJoinList[e],a=t.lotteryJoinList[o];t.$set(t.lotteryJoinList,e,a),t.$set(t.lotteryJoinList,o,i)}}),80),setTimeout((function(){t.stopLottery()}),2500)},random:function(){return Math.floor(Math.random()*this.lotteryJoinList.length)},stopLottery:function(){this.isShowWinner=!0,clearInterval(this.lottery_timer),this.getDetail(),this.buyNumber=0,this.price=0},closeDialog:function(){this.$refs.lotteryPopup.close()}}};e.default=r}}]);