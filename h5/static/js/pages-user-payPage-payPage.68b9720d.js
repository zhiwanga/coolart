(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-payPage-payPage"],{"044b":function(t,a){function e(t){return!!t.constructor&&"function"===typeof t.constructor.isBuffer&&t.constructor.isBuffer(t)}function n(t){return"function"===typeof t.readFloatLE&&"function"===typeof t.slice&&e(t.slice(0,0))}
/*!
 * Determine if an object is a Buffer
 *
 * @author   Feross Aboukhadijeh <https://feross.org>
 * @license  MIT
 */
t.exports=function(t){return null!=t&&(e(t)||n(t)||!!t._isBuffer)}},"076b":function(t,a,e){"use strict";var n;e.d(a,"b",(function(){return i})),e.d(a,"c",(function(){return r})),e.d(a,"a",(function(){return n}));var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",[e("v-uni-view",{staticClass:"price"},[e("v-uni-text",[t._v("￥")]),e("v-uni-text",[t._v(t._s(t.price))])],1),e("v-uni-view",{staticClass:"pay-way"},[e("v-uni-radio-group",{staticClass:"radio-group",on:{change:function(a){arguments[0]=a=t.$handleEvent(a),t.radioChange.apply(void 0,arguments)}}},t._l(t.payWayArr,(function(a,n){return e("v-uni-label",{key:n,staticClass:"row-between"},[e("v-uni-view",{staticClass:"row"},[e("v-uni-view",{staticClass:"main"},[e("v-uni-view",{staticClass:"name"},[t._v(t._s(a.name))])],1)],1),e("v-uni-radio",{staticClass:"radio",attrs:{value:String(a.pay_way),checked:a.pay_way==t.payWay}})],1)})),1)],1),e("v-uni-view",{staticClass:"submit",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.submit.apply(void 0,arguments)}}},[e("v-uni-text",[t._v("快捷支付")]),e("v-uni-text",[t._v("￥"+t._s(t.price))])],1),t.payFlag?e("v-uni-view",{staticClass:"back",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.payFlag=!1}}},[e("v-uni-view",{staticClass:"change",on:{click:function(a){a.stopPropagation(),arguments[0]=a=t.$handleEvent(a)}}},[e("v-uni-view",{staticClass:"title"},[t._v("二级密码")]),e("v-uni-view",{staticClass:"ipt"},[e("v-uni-input",{attrs:{password:!0,maxlength:"20",value:"",placeholder:"请输入二级密码"},model:{value:t.paymentPwd,callback:function(a){t.paymentPwd=a},expression:"paymentPwd"}})],1),e("v-uni-view",{staticClass:"yes",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.checkPwd()}}},[t._v("确定")])],1)],1):t._e()],1)},r=[]},1620:function(t,a,e){"use strict";e.r(a);var n=e("42e1"),i=e.n(n);for(var r in n)"default"!==r&&function(t){e.d(a,t,(function(){return n[t]}))}(r);a["default"]=i.a},"26b0":function(t,a,e){e("a9e3"),e("d3b7"),e("e25e"),e("ac1f"),e("25f0"),e("5319"),function(){var a="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",e={rotl:function(t,a){return t<<a|t>>>32-a},rotr:function(t,a){return t<<32-a|t>>>a},endian:function(t){if(t.constructor==Number)return 16711935&e.rotl(t,8)|4278255360&e.rotl(t,24);for(var a=0;a<t.length;a++)t[a]=e.endian(t[a]);return t},randomBytes:function(t){for(var a=[];t>0;t--)a.push(Math.floor(256*Math.random()));return a},bytesToWords:function(t){for(var a=[],e=0,n=0;e<t.length;e++,n+=8)a[n>>>5]|=t[e]<<24-n%32;return a},wordsToBytes:function(t){for(var a=[],e=0;e<32*t.length;e+=8)a.push(t[e>>>5]>>>24-e%32&255);return a},bytesToHex:function(t){for(var a=[],e=0;e<t.length;e++)a.push((t[e]>>>4).toString(16)),a.push((15&t[e]).toString(16));return a.join("")},hexToBytes:function(t){for(var a=[],e=0;e<t.length;e+=2)a.push(parseInt(t.substr(e,2),16));return a},bytesToBase64:function(t){for(var e=[],n=0;n<t.length;n+=3)for(var i=t[n]<<16|t[n+1]<<8|t[n+2],r=0;r<4;r++)8*n+6*r<=8*t.length?e.push(a.charAt(i>>>6*(3-r)&63)):e.push("=");return e.join("")},base64ToBytes:function(t){t=t.replace(/[^A-Z0-9+\/]/gi,"");for(var e=[],n=0,i=0;n<t.length;i=++n%4)0!=i&&e.push((a.indexOf(t.charAt(n-1))&Math.pow(2,-2*i+8)-1)<<2*i|a.indexOf(t.charAt(n))>>>6-2*i);return e}};t.exports=e}()},3749:function(t,a,e){var n=e("24fb");a=n(!1),a.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-33013765]{background:#101010}.back[data-v-33013765]{width:100%;height:100%;background-color:rgba(0,0,0,.5);position:fixed;top:0;left:0;display:flex;justify-content:center;align-items:center;z-index:999}.back .change[data-v-33013765]{width:86%;padding:%?30?%;background-color:#fff;border-radius:%?30?%;box-sizing:border-box}.back .change .title[data-v-33013765]{text-align:center}.back .change .ipt[data-v-33013765]{margin-top:%?40?%;margin-left:%?20?%;border-bottom:%?2?% solid silver}.back .change .item[data-v-33013765]{width:100%;display:flex;justify-content:center;align-items:center;margin-top:%?30?%;padding:%?10?%}.back .change .item .img[data-v-33013765]{width:%?84?%;height:%?64?%;margin-right:%?20?%}.back .change .item .img uni-image[data-v-33013765]{width:100%;height:100%}.back .change .active[data-v-33013765]{border:%?4?% dashed #f3e0bc;box-sizing:border-box}.back .yes[data-v-33013765]{width:80%;height:%?70?%;line-height:%?70?%;text-align:center;background-color:#f3e0bc;color:#000;margin:0 auto;margin-top:%?40?%;border-radius:%?34?%}.price[data-v-33013765]{width:100%;height:%?200?%;display:flex;align-items:center;justify-content:center;color:#fff}.price > uni-text[data-v-33013765]:first-of-type{font-weight:700}.price > uni-text[data-v-33013765]:last-of-type{font-weight:700;font-size:%?50?%;-webkit-transform:translateY(%?-10?%);transform:translateY(%?-10?%)}.pay-way[data-v-33013765]{width:100%;padding:20px;box-sizing:border-box}.pay-way .row-between[data-v-33013765]{width:100%;padding:%?40?% 0;border-bottom:1px solid #1c1c1c;display:flex;align-items:center;justify-content:space-between}.pay-way .row-between .row[data-v-33013765]{display:flex;align-items:center}.pay-way .row-between .row .image[data-v-33013765]{width:%?80?%;height:%?80?%}.pay-way .row-between .row .main[data-v-33013765]{color:#fff}.submit[data-v-33013765]{width:100%;height:%?100?%;line-height:%?100?%;text-align:center;position:fixed;bottom:0;left:0;right:0;background:#3e3e3e;color:#fff}body.?%PAGE?%[data-v-33013765]{background:#101010}',""]),t.exports=a},4015:function(t,a,e){e("fb6a"),e("ace4"),e("d3b7"),e("25f0"),e("5cc6"),e("9a8c"),e("a975"),e("735e"),e("c1ac"),e("d139"),e("3a7b"),e("d5d6"),e("82f8"),e("e91f"),e("60bd"),e("5f96"),e("3280"),e("3fcc"),e("ca91"),e("25a1"),e("cd26"),e("2954"),e("649e"),e("219c"),e("b39a"),e("72f7"),function(){var a=e("26b0"),n=e("823d").utf8,i=e("044b"),r=e("823d").bin,s=function t(e,s){e.constructor==String?e=s&&"binary"===s.encoding?r.stringToBytes(e):n.stringToBytes(e):i(e)?e=Array.prototype.slice.call(e,0):Array.isArray(e)||e.constructor===Uint8Array||(e=e.toString());for(var o=a.bytesToWords(e),d=8*e.length,c=1732584193,u=-271733879,h=-1732584194,f=271733878,p=0;p<o.length;p++)o[p]=16711935&(o[p]<<8|o[p]>>>24)|4278255360&(o[p]<<24|o[p]>>>8);o[d>>>5]|=128<<d%32,o[14+(d+64>>>9<<4)]=d;var l=t._ff,y=t._gg,g=t._hh,m=t._ii;for(p=0;p<o.length;p+=16){var b=c,v=u,w=h,_=f;c=l(c,u,h,f,o[p+0],7,-680876936),f=l(f,c,u,h,o[p+1],12,-389564586),h=l(h,f,c,u,o[p+2],17,606105819),u=l(u,h,f,c,o[p+3],22,-1044525330),c=l(c,u,h,f,o[p+4],7,-176418897),f=l(f,c,u,h,o[p+5],12,1200080426),h=l(h,f,c,u,o[p+6],17,-1473231341),u=l(u,h,f,c,o[p+7],22,-45705983),c=l(c,u,h,f,o[p+8],7,1770035416),f=l(f,c,u,h,o[p+9],12,-1958414417),h=l(h,f,c,u,o[p+10],17,-42063),u=l(u,h,f,c,o[p+11],22,-1990404162),c=l(c,u,h,f,o[p+12],7,1804603682),f=l(f,c,u,h,o[p+13],12,-40341101),h=l(h,f,c,u,o[p+14],17,-1502002290),u=l(u,h,f,c,o[p+15],22,1236535329),c=y(c,u,h,f,o[p+1],5,-165796510),f=y(f,c,u,h,o[p+6],9,-1069501632),h=y(h,f,c,u,o[p+11],14,643717713),u=y(u,h,f,c,o[p+0],20,-373897302),c=y(c,u,h,f,o[p+5],5,-701558691),f=y(f,c,u,h,o[p+10],9,38016083),h=y(h,f,c,u,o[p+15],14,-660478335),u=y(u,h,f,c,o[p+4],20,-405537848),c=y(c,u,h,f,o[p+9],5,568446438),f=y(f,c,u,h,o[p+14],9,-1019803690),h=y(h,f,c,u,o[p+3],14,-187363961),u=y(u,h,f,c,o[p+8],20,1163531501),c=y(c,u,h,f,o[p+13],5,-1444681467),f=y(f,c,u,h,o[p+2],9,-51403784),h=y(h,f,c,u,o[p+7],14,1735328473),u=y(u,h,f,c,o[p+12],20,-1926607734),c=g(c,u,h,f,o[p+5],4,-378558),f=g(f,c,u,h,o[p+8],11,-2022574463),h=g(h,f,c,u,o[p+11],16,1839030562),u=g(u,h,f,c,o[p+14],23,-35309556),c=g(c,u,h,f,o[p+1],4,-1530992060),f=g(f,c,u,h,o[p+4],11,1272893353),h=g(h,f,c,u,o[p+7],16,-155497632),u=g(u,h,f,c,o[p+10],23,-1094730640),c=g(c,u,h,f,o[p+13],4,681279174),f=g(f,c,u,h,o[p+0],11,-358537222),h=g(h,f,c,u,o[p+3],16,-722521979),u=g(u,h,f,c,o[p+6],23,76029189),c=g(c,u,h,f,o[p+9],4,-640364487),f=g(f,c,u,h,o[p+12],11,-421815835),h=g(h,f,c,u,o[p+15],16,530742520),u=g(u,h,f,c,o[p+2],23,-995338651),c=m(c,u,h,f,o[p+0],6,-198630844),f=m(f,c,u,h,o[p+7],10,1126891415),h=m(h,f,c,u,o[p+14],15,-1416354905),u=m(u,h,f,c,o[p+5],21,-57434055),c=m(c,u,h,f,o[p+12],6,1700485571),f=m(f,c,u,h,o[p+3],10,-1894986606),h=m(h,f,c,u,o[p+10],15,-1051523),u=m(u,h,f,c,o[p+1],21,-2054922799),c=m(c,u,h,f,o[p+8],6,1873313359),f=m(f,c,u,h,o[p+15],10,-30611744),h=m(h,f,c,u,o[p+6],15,-1560198380),u=m(u,h,f,c,o[p+13],21,1309151649),c=m(c,u,h,f,o[p+4],6,-145523070),f=m(f,c,u,h,o[p+11],10,-1120210379),h=m(h,f,c,u,o[p+2],15,718787259),u=m(u,h,f,c,o[p+9],21,-343485551),c=c+b>>>0,u=u+v>>>0,h=h+w>>>0,f=f+_>>>0}return a.endian([c,u,h,f])};s._ff=function(t,a,e,n,i,r,s){var o=t+(a&e|~a&n)+(i>>>0)+s;return(o<<r|o>>>32-r)+a},s._gg=function(t,a,e,n,i,r,s){var o=t+(a&n|e&~n)+(i>>>0)+s;return(o<<r|o>>>32-r)+a},s._hh=function(t,a,e,n,i,r,s){var o=t+(a^e^n)+(i>>>0)+s;return(o<<r|o>>>32-r)+a},s._ii=function(t,a,e,n,i,r,s){var o=t+(e^(a|~n))+(i>>>0)+s;return(o<<r|o>>>32-r)+a},s._blocksize=16,s._digestsize=16,t.exports=function(t,e){if(void 0===t||null===t)throw new Error("Illegal argument "+t);var n=a.wordsToBytes(s(t,e));return e&&e.asBytes?n:e&&e.asString?r.bytesToString(n):a.bytesToHex(n)}}()},"42e1":function(t,a,e){"use strict";var n=e("4ea4");e("4e82"),e("a9e3"),e("d3b7"),e("25f0"),Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i=n(e("4015")),r=n(e("743a")),s=e("f6d3"),o={data:function(){return{payWayArr:[{name:"账户余额",extra:"支付",pay_way:0,type:"balance"}],payWay:0,charStr:"abacdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789",type:1,price:0,buyNumber:0,payFlag:!1,paymentPwd:""}},onLoad:function(t){if(this.type=t.type,this.buyNumber=t.buyNumber,this.buyNumber?(this.price=t.price*this.buyNumber,this.price=this.price.toFixed(2)):this.price=t.price,this.transaction_id=t.transaction_id,this.goods_id=t.goods_id,this.blind_id=t.blind_id,this.status=t.status,this.order_no=t.order_no,this.is_box=t.is_box,this.order_id=t.order_id,t.hasPay){var a={name:"哆啦宝支付",extra:"支付",pay_way:0,type:"dlb"};this.payWayArr=[],this.payWayArr.push(a)}else{var e={name:"账户余额",extra:"支付",pay_way:0,type:"balance"};t.userbalance&&(e.pay_way=Number(t.userbalance)),console.log(t),this.payWayArr=[],this.payWayArr.push(e)}},methods:{radioChange:function(t){console.log(t.detail),this.payWay=0},createSign:function(t){t.sort();var a="";for(var e in t)a+=e+"="+t[e]+"&";return a=a.substr(0,a.length-1),(0,i.default)(a)},RandomIndex:function(t){function a(a,e,n){return t.apply(this,arguments)}return a.toString=function(){return t.toString()},a}((function(t,a,e){var n=Math.floor(Math.random()*(a-t+1)+t),i=this.charStr.length-10;return 0==e&&n>=i&&(n=RandomIndex(t,a,e)),n})),getRandomString:function(t){var a=0,e=this.charStr.length-1,n="";t=t||15;for(var i,r=0;r<t;r++)i=this.RandomIndex(a,e,r),n+=this.charStr[i];return n},checkPwd:function(){var t=this;if(!this.paymentPwd||""==this.paymentPwd)return this.$showToast("填写二级密码");this.$request("post","user/checksecondpawd",{cipcont:(0,s.encrypts)(this.paymentPwd)}).then((function(a){var e=a.status;200==e?(t.payFlag=!1,t.payOrder()):t.$showToast(a.message)})).catch((function(a){t.$showToast(a.message)}))},submit:function(){if(null==this.payWay)return this.$showToast("请选择支付方式");this.payFlag=!0},payOrder:function(){var t=this,a=uni.getStorageSync("token");if(console.log(a),4==this.status)this.$request("post","recharge/submit",{planId:0,customMoney:this.price,pay_type:this.payWayArr[this.payWay].type}).then((function(t){window.location.href=t.data.url})).catch((function(a){t.$showToast(a.message)}));else if(2==this.status)uni.request({url:"".concat(r.default.URL,"blindpay/pay"),header:{"Access-Token":a||"",platform:"H5"},method:"POST",data:{blind_id:this.blind_id,order_id:this.order_id,pay_type:this.payWayArr[this.payWay].type,total:this.buyNumber},success:function(a){if(0==t.payWay){if(500==a.data.status)return t.$showToast(a.data.message);200==a.data.status&&t.$showToast(a.data.message),setTimeout((function(){uni.reLaunch({url:"/pages/boxorder/boxorder"})}),1200)}else{if(200!=a.data.status)return t.$showToast(a.data.message);window.location.href=a.data.data.url}}});else if(3==this.status){var e=new Array;e["md_id"]=this.goods_id,e["md_str"]=this.getRandomString(10),e["timestamp"]=Math.ceil((new Date).getTime()/1e3);var n=this.createSign(e);e["sign"]=n,uni.request({url:"".concat(this.$config.URL,"checkout/new_buy"),header:{"Access-Token":a||"",platform:"H5"},method:"POST",data:{order_no:this.order_no,goodsId:this.goods_id,isBox:this.is_box,pay_type:this.payWayArr[this.payWay].type,timestamp:e["timestamp"],md_id:e["md_id"],md_str:e["md_str"],sign:e["sign"],cipcont:(0,s.encrypts)(this.paymentPwd)},success:function(a){if(0==t.payWay){if(500==a.data.status)return t.$showToast(a.data.message);200==a.data.status&&t.$showToast(a.data.message),setTimeout((function(){uni.redirectTo({url:"/pages/order/index"})}),1200)}else{if(200!=a.data.status)return t.$showToast(a.data.message);window.location.href="http://nft4.dingfengkj.com/h5/pay/index.html?pid="+a.data.data.pid,window.location.href=a.data.data.url}}})}else if(1==this.type){e=new Array;e["md_id"]=this.goods_id,e["md_str"]=this.getRandomString(10),e["timestamp"]=Math.ceil((new Date).getTime()/1e3);n=this.createSign(e);e["sign"]=n,e["cipcont"]=(0,s.encrypts)(this.paymentPwd),console.log(e),console.log("".concat(r.default.URL,"checkout/new_buy")),uni.request({url:"".concat(r.default.URL,"checkout/new_buy"),header:{"Access-Token":a||"",platform:"H5"},method:"POST",data:{goodsId:this.goods_id,order_no:this.order_no,isBox:0,pay_type:this.payWayArr[this.payWay].type,total:this.buyNumber,timestamp:e["timestamp"],md_id:e["md_id"],md_str:e["md_str"],sign:e["sign"],cipcont:(0,s.encrypts)(this.paymentPwd)},success:function(a){if(0==t.payWay){if(500==a.data.status)return t.$showToast(a.data.message);200==a.data.status&&t.$showToast(a.data.message),setTimeout((function(){uni.redirectTo({url:"/pages/order/index"})}),1200)}else{if(200!=a.data.status)return t.$showToast(a.data.message);window.location.href="http://nft4.dingfengkj.com/h5/pay/index.html?pid="+a.data.data.pid,window.location.href=a.data.data.url}}})}else 0!=this.payWay&&1!=this.payWay||uni.request({url:"".concat(r.default.URL,"order/transaction"),header:{"Access-Token":a||"",platform:"H5"},method:"POST",data:{goodsId:this.goods_id,isBox:0,pay_type:this.payWayArr[this.payWay].type,transactionId:this.transaction_id,total:this.buyNumber},success:function(a){if(0==t.payIndex){if(500==a.data.status)return t.newResult=!0,t.$showToast(a.data.message);200==a.data.status&&t.$showToast(a.data.message),setTimeout((function(){uni.redirectTo({url:"/pages/order/index"})}),1200)}else{if(200!=a.data.status)return t.$showToast(a.data.message);window.location.href="http://nft4.dingfengkj.com/h5/pay/index.html?pid="+a.data.data.pid,window.location.href=a.data.data.url}}})}}};a.default=o},"823d":function(t,a){var e={utf8:{stringToBytes:function(t){return e.bin.stringToBytes(unescape(encodeURIComponent(t)))},bytesToString:function(t){return decodeURIComponent(escape(e.bin.bytesToString(t)))}},bin:{stringToBytes:function(t){for(var a=[],e=0;e<t.length;e++)a.push(255&t.charCodeAt(e));return a},bytesToString:function(t){for(var a=[],e=0;e<t.length;e++)a.push(String.fromCharCode(t[e]));return a.join("")}}};t.exports=e},b1d0:function(t,a,e){"use strict";var n=e("d441"),i=e.n(n);i.a},bf37:function(t,a,e){"use strict";e.r(a);var n=e("076b"),i=e("1620");for(var r in i)"default"!==r&&function(t){e.d(a,t,(function(){return i[t]}))}(r);e("b1d0");var s,o=e("f0c5"),d=Object(o["a"])(i["default"],n["b"],n["c"],!1,null,"33013765",null,!1,n["a"],s);a["default"]=d.exports},d441:function(t,a,e){var n=e("3749");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("65775a76",n,!0,{sourceMap:!1,shadowMode:!1})}}]);