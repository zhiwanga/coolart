(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-login-index"],{"3bfb":function(t,n,e){"use strict";var i;e.d(n,"b",(function(){return o})),e.d(n,"c",(function(){return a})),e.d(n,"a",(function(){return i}));var o=function(){var t=this,n=t.$createElement,i=t._self._c||n;return i("v-uni-view",{staticClass:"login"},[i("v-uni-view",{staticClass:"logo"},[i("v-uni-image",{attrs:{src:e("940e"),mode:""}})],1),i("v-uni-view",{staticClass:"name"},[t._v("天琪数藏")]),i("v-uni-view",{staticClass:"box"},[i("v-uni-view",{staticClass:"title"},[t._v("手机号")]),i("v-uni-input",{staticClass:"login-phone",attrs:{type:"number",focus:!0,maxlength:"11",placeholder:"请输入手机号"},on:{input:function(n){arguments[0]=n=t.$handleEvent(n),t.getInput.apply(void 0,arguments)}},model:{value:t.phone,callback:function(n){t.phone=n},expression:"phone"}}),i("v-uni-view",{staticClass:"title"},[t._v("密码")]),i("v-uni-input",{staticClass:"login-phone",attrs:{type:"text",placeholder:"请输入密码"},model:{value:t.password,callback:function(n){t.password=n},expression:"password"}})],1),i("v-uni-view",{staticClass:"login-submit",class:t.subType?"login-sub":"",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.loginSubmit.apply(void 0,arguments)}}},[t._v("登录")]),i("v-uni-view",{staticStyle:{display:"flex","justify-content":"space-between"}},[i("v-uni-view",{staticClass:"input-texts",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.forget.apply(void 0,arguments)}}},[t._v("忘记密码")]),i("v-uni-view",{staticClass:"input-texts",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.register.apply(void 0,arguments)}}},[t._v("暂无账号,去注册")])],1)],1)},a=[]},"49bf":function(t,n,e){"use strict";e.r(n);var i=e("d8af"),o=e.n(i);for(var a in i)"default"!==a&&function(t){e.d(n,t,(function(){return i[t]}))}(a);n["default"]=o.a},6492:function(t,n,e){"use strict";var i=e("7f3c"),o=e.n(i);o.a},"7b57":function(t,n,e){"use strict";function i(t){var n;return function(){var e=this,i=arguments;n&&clearTimeout(n),n=setTimeout((function(){t.apply(e,i)}),n)}}function o(t){return/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(t)}function a(t){return/^([\u4e00-\u9fa5]{1,20}|[a-zA-Z\.\s]{1,20})$/.test(t)}function r(t){var n=t.replace(/<img[^>]*>/gi,(function(t,n){return t=t.replace(/style="[^"]+"/gi,"").replace(/style='[^']+'/gi,""),t=t.replace(/width="[^"]+"/gi,"").replace(/width='[^']+'/gi,""),t=t.replace(/height="[^"]+"/gi,"").replace(/height='[^']+'/gi,""),t}));return n=n.replace(/style="[^"]+"/gi,(function(t,n){return t=t.replace(/width:[^;]+;/gi,"max-width:100%;").replace(/width:[^;]+;/gi,"max-width:100%;"),t})),n=n.replace(/<br[^>]*\/>/gi,""),n=n.replace(/\<img/gi,'<img style="max-width:100%;height:auto;display:block;margin-top:0;margin-bottom:0;"'),n}e("ac1f"),e("5319"),Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var s={debounce:i,checkIdCard:o,checkName:a,formatRichText:r};n.default=s},"7f3c":function(t,n,e){var i=e("f724");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var o=e("4f06").default;o("e43f305a",i,!0,{sourceMap:!1,shadowMode:!1})},"940e":function(t,n,e){t.exports=e.p+"static/img/logo.427bfef0.png"},a4d9:function(t,n,e){"use strict";e.r(n);var i=e("3bfb"),o=e("49bf");for(var a in o)"default"!==a&&function(t){e.d(n,t,(function(){return o[t]}))}(a);e("6492");var r,s=e("f0c5"),l=Object(s["a"])(o["default"],i["b"],i["c"],!1,null,"10f46b93",null,!1,i["a"],r);n["default"]=l.exports},d8af:function(t,n,e){"use strict";var i=e("4ea4");e("c975"),e("d3b7"),e("ac1f"),e("1276"),Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var o=i(e("7b57")),a={data:function(){return{phone:null,subType:!0,codeTip:"获取验证码",inter:null,code:"",invite:"",password:""}},onLoad:function(t){console.log("lll",t),t.code&&(this.invite=t.code)},methods:{loginClose:function(){uni.switchTab({url:"../index/index"})},forget:function(){uni.navigateTo({url:"/pages/forget/index"})},register:function(){uni.navigateTo({url:"/pages/register/index"})},getRequest:function(){var t=window.location.href;console.log("kk",t);var n=new Object;if(-1!=t.indexOf("?")){var e=t.substr(1);strs=e.split("&");for(var i=0;i<strs.length;i++)n[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1])}return n},getInput:function(t){t.detail.value.length>=11?this.subType=!1:this.subType=!0},loginSubmit:o.default.debounce((function(){var t=this;return this.subType?this.$showToast("请输入手机号"):""==this.password?this.$showToast("请输入密码"):(uni.showLoading({title:"登录中"}),void this.$request("POST","Passport/loginAll",{phone:this.phone,password:this.password}).then((function(n){console.log("suc",n),t.$showToast(n.message);var e=n.data.phone.length;n.data.phone=n.data.phone.substring(0,3)+"****"+n.data.phone.substring(e-3,e),n.data.file_path?n.data.file_path=t.$config.ImgUrl+n.data.file_path:n.data.file_path="https://sc.zhongyuansc.net/uploads/headimg/headimg.png",uni.setStorageSync("userinfo",n.data),uni.setStorageSync("token",n.data.token),uni.setStorageSync("login",1),setTimeout((function(){uni.switchTab({url:"../index/index"})}),800)})).catch((function(n){t.$showToast(n.message)})).finally((function(){uni.hideLoading()})))}))},onUnload:function(){this.inter=clearInterval(this.inter)}};n.default=a},f724:function(t,n,e){var i=e("24fb");n=i(!1),n.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.login[data-v-10f46b93]{width:100%;height:100vh;padding:%?150?% %?40?% 0;box-sizing:border-box;background-color:#fff}.login .login-close[data-v-10f46b93]{width:%?50?%;position:absolute;top:%?120?%;left:%?50?%}.login .login-title[data-v-10f46b93]{font-size:%?60?%;color:#fff;letter-spacing:%?5?%}.login .login-phone[data-v-10f46b93]{width:100%;font-size:%?32?%;letter-spacing:%?8?%;color:#000;background-color:#fff;padding:%?10?% %?10?%;border-radius:%?10?%}.login .input-text[data-v-10f46b93]{margin-top:%?30?%;color:#666;letter-spacing:%?6?%;font-size:%?30?%}.login .title[data-v-10f46b93]{margin-top:%?16?%;margin-bottom:%?16?%}.login .input-texts[data-v-10f46b93]{margin-top:%?30?%;color:#f23636;letter-spacing:%?6?%;font-size:%?30?%;text-align:right;border-bottom:%?2?% solid #f23636;display:inline-block}.login .login-submit[data-v-10f46b93]{border-radius:%?30?%;background-color:#000;height:%?80?%;line-height:%?80?%;text-align:center;margin:%?120?% auto 0;font-size:%?32?%;color:#fff}.login .login-sub[data-v-10f46b93]{background-color:#000;color:#fff}.logo[data-v-10f46b93]{width:%?160?%;height:%?160?%;margin:0 auto;border-radius:%?20?%;overflow:hidden}.logo uni-image[data-v-10f46b93]{width:100%;height:100%}.name[data-v-10f46b93]{text-align:center;margin-top:%?20?%;margin-bottom:%?60?%}.box[data-v-10f46b93]{box-sizing:border-box;margin-bottom:33px;padding:27px 17px 32px;background:#f9f9f9;border:1px solid #f4f2f2;border-radius:11px;width:100%}.code_box[data-v-10f46b93]{display:flex;justify-content:space-between;margin-top:14px;padding:%?10?% %?10?%;background-color:#fff;border-radius:%?10?%}.code_box .ipt[data-v-10f46b93]{font-size:16px;color:grey}.code_box .code[data-v-10f46b93]{padding:2px 6px;background-color:grey;border-radius:4px;color:#e2d6d6}',""]),t.exports=n}}]);