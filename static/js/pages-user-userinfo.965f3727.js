(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-userinfo"],{"0caf":function(e,t,i){var n=i("24fb");t=n(!1),t.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-67451db0]{background-color:#0c0d0d}.userinfo[data-v-67451db0]{padding:0 %?20?%}.userinfo .userinfo-item[data-v-67451db0]{display:flex;justify-content:space-between;align-items:center;color:#fff;background:#292b2b;border-radius:%?30?%;margin-bottom:%?25?%;padding:%?30?% %?50?%}.userinfo .login_out[data-v-67451db0]{width:100%;border-radius:%?30?%;background:#292b2b;color:#c94f58;text-align:center;padding:%?30?% 0;margin-top:%?200?%}.userinfo .icon-left[data-v-67451db0]{font-size:%?35?%}.userinfo .icon-right[data-v-67451db0]{display:flex;align-items:center;color:#a6a7a7;font-size:%?35?%}.userinfo .icon-right .icon-text-img[data-v-67451db0]{width:%?100?%;border-radius:100%}.userinfo .icon-right .icon-text[data-v-67451db0]{display:flex;align-items:center}.userinfo .icon-right .icon-text .icon-a[data-v-67451db0]{margin-right:%?10?%}.userinfo .icon-right .icon-o[data-v-67451db0]{width:%?25?%;height:%?25?%;background:#ff3141;border-radius:100%;margin-right:%?10?%}.userinfo .icon-right .icon-img[data-v-67451db0]{width:%?50?%}.pop-item .popup-text[data-v-67451db0]{width:100vw;background:#2c2f2f;padding:%?30?%;text-align:center;color:#fff;font-size:%?30?%}.pop-item .camera[data-v-67451db0]{border-radius:%?20?% %?20?% 0 0;margin-bottom:%?1?%}.pop-item .close[data-v-67451db0]{margin-top:%?15?%}.popupName-item[data-v-67451db0]{padding:%?20?% 0 0;background:#2c2f2f;color:#fff;font-size:%?30?%;border-radius:%?20?%;text-align:center}.popupName-item .popupName-title[data-v-67451db0]{font-size:%?40?%;font-weight:700}.popupName-item .popupName-text[data-v-67451db0]{margin:%?30?% 0 %?25?%}.popupName-item .popupName-input[data-v-67451db0]{background:#363939;border-radius:%?10?%;padding:%?20?% 0 %?20?% %?30?%;margin:0 %?40?%;width:%?500?%;text-align:left}.popupName-item .popupName-btn[data-v-67451db0]{display:flex;align-items:center;border-top:%?2?% solid #5e6161;margin-top:%?30?%}.popupName-item .popupName-btn .popupName-a[data-v-67451db0]{width:50%;line-height:%?100?%;text-align:center}.popupName-item .popupName-btn .close[data-v-67451db0]{border-right:%?2?% solid #5e6161}body.?%PAGE?%[data-v-67451db0]{background-color:#0c0d0d}',""]),e.exports=t},4106:function(e,t,i){"use strict";var n=i("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var a=n(i("7cd2")),o={data:function(){return{userinfo:[{title:"头像",type:1,img:i("21df")},{title:"昵称",type:2,text:"藏家_001"},{title:"手机号",type:3,text:"10086"},{title:"实名认证",type:4}],popupName:"",userInfoIdType:!1}},onShow:function(){if(uni.getStorageSync("userinfo")){var e=uni.getStorageSync("userinfo");this.userinfo[0].img=e.file_path,this.userinfo[1].text=e.nick_name,this.userinfo[2].text=e.phone,e.idcar&&(this.userInfoIdType=!0)}},methods:{loginOut:a.default.debounce((function(){uni.removeStorageSync("token"),uni.removeStorageSync("userinfo"),uni.removeStorageSync("login"),uni.switchTab({url:"../index/index"})})),change:function(e){switch(e){case 1:this.chooseImg();break;case 2:this.changeName();break;case 4:uni.navigateTo({url:"./realname?type="+this.userInfoIdType});break}},chooseImg:function(){uni.chooseImage({count:6,sizeType:["original","compressed"],success:function(e){console.log(JSON.stringify(e.tempFilePaths))}})},changeName:function(){this.$refs.popupName.open("center")},popupNameClose:function(){this.$refs.popupName.close()},popupNameSub:function(){if(""==this.popupName)return uni.showToast({title:"输入不能为空",icon:"none"});this.userinfo[1].text=this.popupName,this.popupName="",this.$refs.popupName.close()}}};t.default=o},"77be":function(e,t,i){"use strict";i.r(t);var n=i("b81b"),a=i("7a30");for(var o in a)"default"!==o&&function(e){i.d(t,e,(function(){return a[e]}))}(o);i("7ab1");var r,s=i("f0c5"),p=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"67451db0",null,!1,n["a"],r);t["default"]=p.exports},"7a30":function(e,t,i){"use strict";i.r(t);var n=i("4106"),a=i.n(n);for(var o in n)"default"!==o&&function(e){i.d(t,e,(function(){return n[e]}))}(o);t["default"]=a.a},"7ab1":function(e,t,i){"use strict";var n=i("b0a9"),a=i.n(n);a.a},b0a9:function(e,t,i){var n=i("0caf");"string"===typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);var a=i("4f06").default;a("f8010130",n,!0,{sourceMap:!1,shadowMode:!1})},b81b:function(e,t,i){"use strict";i.d(t,"b",(function(){return a})),i.d(t,"c",(function(){return o})),i.d(t,"a",(function(){return n}));var n={uniPopup:i("d230").default},a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-uni-view",{staticClass:"userinfo"},[e._l(e.userinfo,(function(t){return n("v-uni-view",{staticClass:"userinfo-item",on:{click:function(i){arguments[0]=i=e.$handleEvent(i),e.change(t.type)}}},[n("v-uni-view",{staticClass:"icon-left"},[e._v(e._s(t.title))]),n("v-uni-view",{staticClass:"icon-right"},[1==t.type?n("v-uni-image",{staticClass:"icon-text-img",attrs:{src:t.img,mode:"widthFix"}}):e._e(),2==t.type||3==t.type?n("v-uni-view",{staticClass:"icon-text"},[e._v(e._s(t.text))]):e._e(),4==t.type?n("v-uni-view",{staticClass:"icon-text"},[e.userInfoIdType?n("v-uni-icon",{staticClass:"icon-a",attrs:{type:"success",size:"20",color:"#5BA151"}}):e._e(),e.userInfoIdType?e._e():n("v-uni-view",{staticClass:"icon-o"}),e._v(e._s(e.userInfoIdType?"已认证":"未认证"))],1):e._e(),n("v-uni-image",{staticClass:"icon-img",attrs:{src:i("2e0c"),mode:"widthFix"}})],1)],1)})),n("v-uni-view",{staticClass:"login_out",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.loginOut.apply(void 0,arguments)}}},[e._v("退出登录")]),n("uni-popup",{ref:"popupName",attrs:{type:"center",name:"userName"}},[n("v-uni-view",{staticClass:"popupName-item"},[n("v-uni-view",{staticClass:"popupName-title"},[e._v("修改昵称")]),n("v-uni-view",{staticClass:"popupName-text"},[e._v("原昵称: "+e._s(e.userinfo[1].text))]),n("v-uni-input",{staticClass:"popupName-input",attrs:{focus:!0,type:"text",placeholder:"支持2-16位中英文,数字","placeholder-style":"color:'#5E6161'"},model:{value:e.popupName,callback:function(t){e.popupName=t},expression:"popupName"}}),n("v-uni-view",{staticClass:"popupName-btn"},[n("v-uni-view",{staticClass:"popupName-a close",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.popupNameClose.apply(void 0,arguments)}}},[e._v("取消")]),n("v-uni-view",{staticClass:"popupName-a",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.popupNameSub.apply(void 0,arguments)}}},[e._v("确定")])],1)],1)],1)],2)},o=[]}}]);