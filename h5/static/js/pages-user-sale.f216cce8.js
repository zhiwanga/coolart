(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-sale"],{"1fe5":function(t,e,a){"use strict";var i;a.d(e,"b",(function(){return n})),a.d(e,"c",(function(){return s})),a.d(e,"a",(function(){return i}));var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{staticClass:"fix_title"},t._l(t.arr,(function(e,a){return i("v-uni-view",{key:a,class:[t.changeIndex==a?"fix_item":"no_change"],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.change(a)}}},[i("v-uni-view",{},[t._v(t._s(e.name))])],1)})),1),i("v-uni-view",{staticClass:"list"},t._l(t.list,(function(e,a){return i("v-uni-view",{key:a,staticClass:"item"},[i("v-uni-view",{staticClass:"shop"},[i("v-uni-view",{staticClass:"shop_img"},[i("v-uni-image",{attrs:{src:e.file_path,mode:""}})],1),i("v-uni-view",[i("v-uni-view",{staticStyle:{"margin-bottom":"10rpx",color:"#fff","font-size":"17px","font-weight":"700"}},[t._v(t._s(e.goods_name))]),i("v-uni-view",[t._v(t._s(t.utilTime(e.create_time)))]),i("v-uni-view",{staticStyle:{color:"#fff","margin-top":"20rpx"}},[t._v(t._s(e.total_price)+"元")])],1),30==e.order_status?i("v-uni-view",{staticClass:"success"},[t._v("交易成功")]):t._e(),20==e.order_status?i("v-uni-view",{staticClass:"error"},[t._v("交易取消")]):t._e()],1),t.changeIndex<=1?i("v-uni-view",{staticClass:"items"},[10==e.pay_status?i("v-uni-view",{staticClass:"pay",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.closePay(e)}}},[t._v("取消订单")]):t._e(),i("v-uni-view",{staticClass:"pay",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.openPay(e,e.pay_status)}}},[t._v(t._s(10==e.pay_status?"未付款":"已付款"))])],1):t._e()],1)})),1),0==t.list.length?i("v-uni-view",{staticClass:"empty"},[i("v-uni-image",{attrs:{src:a("de12"),mode:"widthFix"}}),i("v-uni-text",[t._v("这里什么都没有呢~")])],1):t._e(),t.payType?i("v-uni-view",{staticClass:"back",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.payType=!1}}},[i("v-uni-view",{staticClass:"change",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e)}}},[i("v-uni-view",{staticClass:"title"},[t._v("选择支付方式")]),t._l(t.payArr,(function(e,a){return i("v-uni-view",{key:a,staticClass:"item",class:a==t.payIndex?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeType(a)}}},[i("v-uni-view",[t._v(t._s(e.name))])],1)})),i("v-uni-view",{staticClass:"yes",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.submit.apply(void 0,arguments)}}},[t._v("确定")])],2)],1):t._e()],1)},s=[]},2909:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=c;var i=r(a("6005")),n=r(a("db90")),s=r(a("06c5")),o=r(a("3427"));function r(t){return t&&t.__esModule?t:{default:t}}function c(t){return(0,i.default)(t)||(0,n.default)(t)||(0,s.default)(t)||(0,o.default)()}},3427:function(t,e,a){"use strict";function i(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}Object.defineProperty(e,"__esModule",{value:!0}),e.default=i},"3b2e":function(t,e,a){"use strict";a.r(e);var i=a("1fe5"),n=a("fe5c");for(var s in n)"default"!==s&&function(t){a.d(e,t,(function(){return n[t]}))}(s);a("a6f8");var o,r=a("f0c5"),c=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"83486260",null,!1,i["a"],o);e["default"]=c.exports},"5f16":function(t,e,a){var i=a("f89c");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("2dac488a",i,!0,{sourceMap:!1,shadowMode:!1})},6005:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=s;var i=n(a("6b75"));function n(t){return t&&t.__esModule?t:{default:t}}function s(t){if(Array.isArray(t))return(0,i.default)(t)}},"8cf7":function(t,e,a){"use strict";var i=a("4ea4");a("99af"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i(a("2909")),s={data:function(){return{changeIndex:0,payArr:[{type:"balance",name:"余额支付",image:"/static/jd.jpg"}],arr:[{name:"全部"},{name:"售卖中"},{name:"已出售"},{name:"已取消"}],list:[],payIndex:0,payType:!1,detailList:"",configs:"",page:1,total:"",idcar:"",code:"",requestNum:"",newResult:!0}},onShow:function(){this.page=1,this.list=[],this.getList(),this.configs=this.$config.ImgUrl},methods:{change:function(t){this.page=1,this.changeIndex=t,this.list=[],this.getList()},changeType:function(t){this.payIndex=t},openPay:function(t,e){20!=e&&(this.detailList=t,uni.navigateTo({url:"/pages/user/payPage/payPage?status=3&order_no="+this.detailList.order_no+"&goods_id="+(this.detailList.goods_id?this.detailList.goods_id:0)+"&price="+this.detailList.total_price+"&is_box="+this.detailList.is_box}))},closePay:function(t){var e=this;uni.request({url:"".concat(this.$config.URL,"Checkout/qxorder"),header:{"Access-Token":uni.getStorageSync("token")||"",platform:"H5"},method:"POST",data:{order_id:t.order_id},success:function(t){e.$showToast("取消成功"),setTimeout((function(){e.page=1,e.total="",e.list=[],e.getList()}),1200)}})},submit:function(t){var e=this;0!=this.payIndex&&1!=this.payIndex&&2!=this.payIndex||uni.request({url:"".concat(this.$config.URL,"checkout/buy"),header:{"Access-Token":uni.getStorageSync("userinfo").token||"",platform:"H5"},method:"POST",data:{order_no:this.detailList.order_no,goodsId:this.detailList.goods_id,isBox:this.detailList.is_box,pay_type:this.payArr[this.payIndex].type},success:function(t){if(0==e.payIndex){if(500==t.data.status)return e.newResult=!0,e.$showToast(t.data.message);200==t.data.status&&e.$showToast(t.data.message),e.newResult=!0,setTimeout((function(){uni.redirectTo({url:"/pages/order/index"})}),1200)}else if(1==e.payIndex){if(500==t.data.status)return e.$showToast(t.data.message);200==t.data.status&&(window.location.href=t.data.data.url),e.newResult=!0}else{if(200!=t.data.status)return e.$showToast(t.data.message);window.location.href="http://nft.dingfengkj.com/h5/pay/index.html?pid="+t.data.data.pid}}})},utilTime:function(t){var e=new Date(1e3*t),a=e.getFullYear(),i=e.getMonth()+1;i=i<10?"0"+i:i;var n=e.getDate();n=n<10?"0"+n:n;var s=e.getHours();s=s<10?"0"+s:s;var o=e.getMinutes();o=o<10?"0"+o:o;var r=e.getSeconds();r=r<10?"0"+r:r;var c=["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],d=c[e.getDay()];e.getDay();return"".concat(a,"-").concat(i,"-").concat(n," ").concat(s,":").concat(o,":").concat(r," ").concat(d)},getList:function(){var t=this;this.$request("post","Checkout/myOrderList",{order_status:0==this.changeIndex?"":1==this.changeIndex?10:2==this.changeIndex?30:20,userid:uni.getStorageSync("userinfo").userId,page:this.page}).then((function(e){console.log("res",e),""!=e.data?(t.list=[].concat((0,n.default)(t.list),(0,n.default)(e.data.order.data)),t.total=e.data.order.last_page):(t.list=[],t.$showToast("暂无数据"))})).catch((function(e){console.log("err",e),t.list=[],t.$showToast(e.message)}))},onReachBottom:function(){if(console.log("sskkfjk"),this.page+1>this.total)return this.$showToast("暂无更多数据");this.page+=1,this.getList()}}};e.default=s},a6f8:function(t,e,a){"use strict";var i=a("5f16"),n=a.n(i);n.a},db90:function(t,e,a){"use strict";function i(t){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}a("a4d3"),a("e01a"),a("d28b"),a("a630"),a("d3b7"),a("3ca3"),a("ddb0"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=i},de12:function(t,e,a){t.exports=a.p+"static/img/img21.76eaa030.png"},f89c:function(t,e,a){var i=a("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-83486260]{background-color:#101010}.fix_title[data-v-83486260]{display:flex;justify-content:space-around;padding:%?20?% %?30?%}.fix_title .no_change[data-v-83486260]{color:#fff}.fix_title .fix_item[data-v-83486260]{color:#fff;border-bottom:%?4?% solid #fff;padding-bottom:%?8?%}.list[data-v-83486260]{padding:%?30?% %?20?%}.item[data-v-83486260]{font-size:%?24?%;color:#cccccd;padding:%?25?% %?29?%;border-radius:%?15?%;margin-bottom:%?22?%}.item .items[data-v-83486260]{display:flex;justify-content:flex-end}.item .items .pay[data-v-83486260]{margin-left:%?10?%}.item .shop[data-v-83486260]{display:flex}.item .shop .shop_img[data-v-83486260]{width:%?140?%;height:%?140?%;margin-bottom:%?20?%;margin-right:%?10?%;border-radius:%?20?%;overflow:hidden}.item .shop .shop_img uni-image[data-v-83486260]{width:100%;height:100%}.item .shop .success[data-v-83486260]{color:#99db91}.item .shop .copy-btn[data-v-83486260]{display:flex;align-items:center}.item .shop .copy-btn > uni-image[data-v-83486260]{width:%?50?%;height:%?50?%;margin-left:%?20?%}.item .pay[data-v-83486260]{padding:%?6?% %?18?%;border-radius:%?10?%;background-color:#f3e0bc;letter-spacing:%?4?%;color:#000}.name[data-v-83486260]{margin-bottom:%?10?%;color:#fff}.back[data-v-83486260]{width:100%;height:100%;background-color:rgba(0,0,0,.5);position:fixed;top:0;left:0;display:flex;justify-content:center;align-items:center;z-index:999}.back .change[data-v-83486260]{width:86%;padding:%?30?%;background-color:#fff;border-radius:%?30?%;box-sizing:border-box}.back .change .title[data-v-83486260]{text-align:center}.back .change .ipt[data-v-83486260]{margin-top:%?40?%;margin-left:%?20?%;border-bottom:%?2?% solid silver}.back .change .item[data-v-83486260]{width:100%;display:flex;justify-content:center;align-items:center;margin-top:%?30?%;padding:%?10?%;color:#fff;font-size:%?32?%}.back .change .item .img[data-v-83486260]{width:%?64?%;height:%?64?%;margin-right:%?20?%}.back .change .item .img uni-image[data-v-83486260]{width:100%;height:100%}.back .change .active[data-v-83486260]{border:%?4?% dashed #f3e0bc;box-sizing:border-box}.back .yes[data-v-83486260]{width:80%;height:%?70?%;line-height:%?70?%;text-align:center;background-color:#f3e0bc;color:#fff;margin:0 auto;margin-top:%?40?%;border-radius:%?34?%}.empty[data-v-83486260]{width:100%}.empty uni-image[data-v-83486260]{width:80%;margin-left:10%;margin-top:20%;opacity:.5}.empty uni-text[data-v-83486260]{width:100%;color:#999;display:block;text-align:center;opacity:.8;margin-top:%?20?%}body.?%PAGE?%[data-v-83486260]{background-color:#101010}',""]),t.exports=e},fe5c:function(t,e,a){"use strict";a.r(e);var i=a("8cf7"),n=a.n(i);for(var s in i)"default"!==s&&function(t){a.d(e,t,(function(){return i[t]}))}(s);e["default"]=n.a}}]);