(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-billingMessage"],{"0252":function(e,t,a){var n=a("24fb");t=n(!1),t.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-76130890]{background-color:#fff}.back[data-v-76130890]{width:100%;height:100%;background-color:rgba(0,0,0,.5);position:fixed;top:0;left:0;display:flex;justify-content:center;align-items:center;z-index:999}.back .change[data-v-76130890]{width:86%;padding:%?30?%;background-color:#fff;border-radius:%?30?%;box-sizing:border-box}.back .change .title[data-v-76130890]{text-align:center}.back .change .ipt[data-v-76130890]{margin-top:%?40?%;margin-left:%?20?%;border-bottom:%?2?% solid silver}.back .change .item[data-v-76130890]{width:100%;display:flex;justify-content:center;align-items:center;margin-top:%?30?%;padding:%?10?%}.back .change .item .img[data-v-76130890]{width:%?84?%;height:%?64?%;margin-right:%?20?%}.back .change .item .img uni-image[data-v-76130890]{width:100%;height:100%}.back .change .active[data-v-76130890]{border:%?4?% dashed #f3e0bc;box-sizing:border-box}.back .yes[data-v-76130890]{width:80%;height:%?70?%;line-height:%?70?%;text-align:center;background-color:#f3e0bc;color:#000;margin:0 auto;margin-top:%?40?%;border-radius:%?34?%}.seting-tab[data-v-76130890]{width:96%;margin:0 auto;margin-top:%?20?%;padding:%?20?%;box-sizing:border-box;-moz-box-shadow:0 1px 5px #d8d6d6;-webkit-box-shadow:0 1px 5px #d8d6d6;box-shadow:0 1px 5px #d8d6d6;border-radius:%?20?%}.seting-tab .van_tabs[data-v-76130890]{display:flex;font-size:16px;color:#333}.seting-tab .van_tabs .van_tab[data-v-76130890]{width:33.3%;height:%?100?%;line-height:%?100?%;text-align:center}.seting-tab .van_tabs .active[data-v-76130890]{background-color:#f8f8f8}.seting-tab .upload[data-v-76130890]{width:%?160?%;height:%?160?%;border:%?2?% solid #333;border-radius:%?14?%;margin:0 auto;margin-top:%?60?%;line-height:%?160?%;text-align:center;font-size:30px;color:#aaa}.seting-tab .uploadImgW[data-v-76130890]{width:%?160?%;height:%?160?%;border-radius:%?14?%;margin:0 auto;margin-top:%?60?%;overflow:hidden}.seting-tab .flex_ipt[data-v-76130890]{display:flex;padding:%?20?% %?10?%;border-bottom:%?2?% solid #f7f7f7}.seting-tab .flex_ipt .txt[data-v-76130890]{color:#333;margin-right:%?10?%}.seting-tab .flex_ipt .ipt[data-v-76130890]{color:#999}.seting-tab .submit[data-v-76130890]{width:90%;height:%?70?%;line-height:%?70?%;border-radius:%?16?%;margin:0 auto;margin-top:%?40?%;text-align:center;background:#fdb428}body.?%PAGE?%[data-v-76130890]{background-color:#fff}',""]),e.exports=t},"242e":function(e,t,a){"use strict";a.r(t);var n=a("63d5"),i=a.n(n);for(var s in n)"default"!==s&&function(e){a.d(t,e,(function(){return n[e]}))}(s);t["default"]=i.a},"470c":function(e,t,a){"use strict";a.r(t);var n=a("6ee3"),i=a("242e");for(var s in i)"default"!==s&&function(e){a.d(t,e,(function(){return i[e]}))}(s);a("6164");var o,c=a("f0c5"),r=Object(c["a"])(i["default"],n["b"],n["c"],!1,null,"76130890",null,!1,n["a"],o);t["default"]=r.exports},6164:function(e,t,a){"use strict";var n=a("bcb7"),i=a.n(n);i.a},"63d5":function(e,t,a){"use strict";var n=a("4ea4");a("4160"),a("159b"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i=n(a("743a")),s={data:function(){return{arr:[{name:"支付宝"},{name:""},{name:"银行卡"}],changeIndex:0,apply:"",wechat:"",mobile:"",name:"",openBank:"",openOther:"",bankNumber:"",uploadImgZ:"",uploadImgW:"",upZ:"",upW:"",payFlag:!1,paymentPwd:"",payform:{}}},onShow:function(){var e=this;this.$request("get","user/collectionInfo",{}).then((function(t){if(console.log("asd",t),t.data.data){var a=t.data.data;a.forEach((function(t){0==t.type?(e.name=t.real_name||"",e.apply=t.name,e.mobile=t.mobile,e.uploadImgZ=t.path,e.upZ=t.path):1==t.type?(e.name=t.real_name||"",e.wechat=t.name,e.mobile=t.mobile,e.uploadImgW=t.path):(e.openBank=t.bank,e.openOther=t.branch,e.bankNumber=t.name,e.name=t.real_name,e.mobile=t.mobile)}))}})).catch((function(t){console.log("err",t),e.$showToast(t.message)}))},methods:{change:function(e){this.changeIndex=e},checkPwd:function(){var e=this;if(!this.paymentPwd||""==this.paymentPwd)return this.$showToast("填写二级密码");this.$request("post","user/checksecondpawd",{second_pswd:this.paymentPwd}).then((function(t){var a=t.status;200==a?e.utilPost():e.$showToast(t.message)})).catch((function(t){e.$showToast(t.message)}))},submit:function(){var e={name:0==this.changeIndex?this.apply:1==this.changeIndex?this.wechat:this.bankNumber,type:this.changeIndex,mobile:this.mobile,path:0==this.changeIndex?this.uploadImgZ:1==this.changeIndex?this.uploadImgW:"",bank:2==this.changeIndex?this.openBank:"",branch:2==this.changeIndex?this.openOther:"",real_name:this.name};if(""==this.mobile||!/^1(3|4|5|6|7|8|9)\d{9}$/.test(this.mobile))return this.$showToast("请输入正确的手机号");switch(this.changeIndex){case 0:if(""==this.upZ||""==this.apply||""==this.mobile)return this.$showToast("请完善支付宝信息");this.payFlag=!0;break;case 1:if(""==this.upW||""==this.wechat||""==this.mobile)return this.$showToast("请完善微信收款信息");this.payFlag=!0;break;case 2:if(""==this.openBank||""==this.openOther||""==this.bankNumber||""==this.mobile||""==this.name)return this.$showToast("请完善银行卡收款信息");this.payFlag=!0;break}this.payform=e,console.log(this.payform)},utilPost:function(){var e=this;this.$request("post","user/collectionEdit",{form:this.payform}).then((function(t){console.log("asd",t),e.$showToast(t.message),setTimeout((function(){uni.navigateBack({delta:1})}),200)})).catch((function(t){e.$showToast(t.message)}))},upLoad:function(){var e=this,t=uni.getStorageSync("token");uni.chooseImage({count:1,sizeType:["original","compressed"],success:function(a){console.log(a.tempFiles[0]),uni.uploadFile({url:"".concat(i.default.URL,"upload/image"),filePath:a.tempFilePaths[0],name:"file",formData:{userid:uni.getStorageSync("userinfo").userId,type:"payInfo"},header:{"access-token":t||"",platform:"H5"},success:function(t){0==e.changeIndex?(e.uploadImgZ=JSON.parse(t.data).data.fileInfo.preview_url,e.upZ=JSON.parse(t.data).data.fileInfo.preview_url):(e.uploadImgW=JSON.parse(t.data).data.fileInfo.preview_url,e.upW=JSON.parse(t.data).data.fileInfo.preview_url)}})}})}}};t.default=s},"6ee3":function(e,t,a){"use strict";var n;a.d(t,"b",(function(){return i})),a.d(t,"c",(function(){return s})),a.d(t,"a",(function(){return n}));var i=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("v-uni-view",[a("v-uni-view",{staticClass:"seting-tab"},[a("v-uni-view",{staticClass:"van_tabs"},e._l(e.arr,(function(t,n){return a("v-uni-view",{key:n,staticClass:"van_tab",class:e.changeIndex==n?"active":"",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.change(n)}}},[e._v(e._s(t.name))])})),1),0!=e.changeIndex||e.uploadImgZ?e._e():a("v-uni-view",{staticClass:"upload",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.upLoad.apply(void 0,arguments)}}},[e._v("+")]),0==e.changeIndex&&e.uploadImgZ?a("v-uni-view",{staticClass:"uploadImgW"},[a("v-uni-image",{staticClass:"auto-img",attrs:{src:e.uploadImgZ,mode:""},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.upLoad.apply(void 0,arguments)}}})],1):e._e(),0==e.changeIndex?a("v-uni-view",{staticClass:"flex_ipt"},[a("v-uni-view",{staticClass:"txt"},[e._v("支付宝号")]),a("v-uni-view",{staticClass:"ipt"},[a("v-uni-input",{attrs:{type:"text",value:"",placeholder:"请输入支付宝号码"},model:{value:e.apply,callback:function(t){e.apply=t},expression:"apply"}})],1)],1):e._e(),0==e.changeIndex||2==e.changeIndex?a("v-uni-view",{staticClass:"flex_ipt"},[a("v-uni-view",{staticClass:"txt"},[e._v("姓名")]),a("v-uni-view",{staticClass:"ipt"},[a("v-uni-input",{attrs:{type:"text",value:"",placeholder:"请输入姓名"},model:{value:e.name,callback:function(t){e.name=t},expression:"name"}})],1)],1):e._e(),2==e.changeIndex?a("v-uni-view",{staticClass:"flex_ipt"},[a("v-uni-view",{staticClass:"txt"},[e._v("开户银行")]),a("v-uni-view",{staticClass:"ipt"},[a("v-uni-input",{attrs:{type:"text",value:"",placeholder:"请输入开户银行"},model:{value:e.openBank,callback:function(t){e.openBank=t},expression:"openBank"}})],1)],1):e._e(),2==e.changeIndex?a("v-uni-view",{staticClass:"flex_ipt"},[a("v-uni-view",{staticClass:"txt"},[e._v("开户支行")]),a("v-uni-view",{staticClass:"ipt"},[a("v-uni-input",{attrs:{type:"text",value:"",placeholder:"请输入开户支行"},model:{value:e.openOther,callback:function(t){e.openOther=t},expression:"openOther"}})],1)],1):e._e(),2==e.changeIndex?a("v-uni-view",{staticClass:"flex_ipt"},[a("v-uni-view",{staticClass:"txt"},[e._v("银行卡号")]),a("v-uni-view",{staticClass:"ipt"},[a("v-uni-input",{attrs:{type:"text",value:"",placeholder:"请输入银行卡号"},model:{value:e.bankNumber,callback:function(t){e.bankNumber=t},expression:"bankNumber"}})],1)],1):e._e(),0==e.changeIndex||2==e.changeIndex?a("v-uni-view",{staticClass:"flex_ipt"},[a("v-uni-view",{staticClass:"txt"},[e._v("手机号")]),a("v-uni-view",{staticClass:"ipt"},[a("v-uni-input",{attrs:{type:"text",value:"",placeholder:"请输入手机号码"},model:{value:e.mobile,callback:function(t){e.mobile=t},expression:"mobile"}})],1)],1):e._e(),0==e.changeIndex||2==e.changeIndex?a("v-uni-view",{staticClass:"submit",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.submit.apply(void 0,arguments)}}},[e._v("提交")]):e._e()],1),e.payFlag?a("v-uni-view",{staticClass:"back",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.payFlag=!1}}},[a("v-uni-view",{staticClass:"change",on:{click:function(t){t.stopPropagation(),arguments[0]=t=e.$handleEvent(t)}}},[a("v-uni-view",{staticClass:"title"},[e._v("二级密码")]),a("v-uni-view",{staticClass:"ipt"},[a("v-uni-input",{attrs:{type:"number",password:!0,maxlength:"6",value:"",placeholder:"请输入二级密码"},model:{value:e.paymentPwd,callback:function(t){e.paymentPwd=t},expression:"paymentPwd"}})],1),a("v-uni-view",{staticClass:"yes",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.checkPwd()}}},[e._v("确定")])],1)],1):e._e()],1)},s=[]},bcb7:function(e,t,a){var n=a("0252");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);var i=a("4f06").default;i("b7818556",n,!0,{sourceMap:!1,shadowMode:!1})}}]);