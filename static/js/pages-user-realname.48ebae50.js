(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-realname"],{"0bc4":function(e,a,t){"use strict";t.r(a);var i=t("2cdb"),r=t.n(i);for(var n in i)"default"!==n&&function(e){t.d(a,e,(function(){return i[e]}))}(n);a["default"]=r.a},"2cdb":function(e,a,t){"use strict";var i=t("4ea4");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var r=i(t("7b57")),n={data:function(){return{name:"",id:null,realType:!1,realName:"",realID:""}},onLoad:function(e){if(uni.getStorageSync("userinfo").idcar){var a=uni.getStorageSync("userinfo");this.realName=a.idname,this.realID=a.idcar,this.realType=!0}},methods:{submit:r.default.debounce((function(){var e=this;if(!r.default.checkName(this.name))return uni.showToast({title:"请输入真实姓名",icon:"none"});if(!r.default.checkIdCard(this.id))return uni.showToast({title:"请输入正确的身份证号码",icon:"none"});uni.showLoading({title:"认证中..."});var a=uni.getStorageSync("userinfo");this.$request("post","User/idcar",{idcar_name:this.name,idcar:this.id,user_id:a.userId}).then((function(t){uni.hideLoading(),e.realName=e.userCheck(1,e.name),e.realID=e.userCheck(2,e.id),a.idname=e.realName,a.idcar=e.realID,uni.setStorageSync("userinfo",a),e.realType=!0})).catch((function(a){console.log("err",a),e.$showToast(a.message)}))})),userCheck:function(e,a){return 1==e?"**"+a.substring(a.length-1,a.length):2==e?a.substring(0,1)+"****************"+a.substring(a.length-1,a.length):void 0}}};a.default=n},3266:function(e,a,t){var i=t("6512");"string"===typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);var r=t("4f06").default;r("529a6876",i,!0,{sourceMap:!1,shadowMode:!1})},"64fd":function(e,a,t){"use strict";var i;t.d(a,"b",(function(){return r})),t.d(a,"c",(function(){return n})),t.d(a,"a",(function(){return i}));var r=function(){var e=this,a=e.$createElement,i=e._self._c||a;return i("v-uni-view",{staticClass:"realname"},[e.realType?e._e():i("v-uni-view",{staticClass:"real-fail"},[i("v-uni-view",{staticClass:"real-item"},[i("v-uni-view",{staticClass:"real-title real-name"},[e._v("姓名 :")]),i("v-uni-input",{staticClass:"real-input",attrs:{focus:!0,type:"text",placeholder:"请输入姓名"},model:{value:e.name,callback:function(a){e.name=a},expression:"name"}})],1),i("v-uni-view",{staticClass:"real-item"},[i("v-uni-view",{staticClass:"real-title"},[e._v("身份证号码 :")]),i("v-uni-input",{staticClass:"real-input",attrs:{type:"text",placeholder:"请输入身份证号码"},model:{value:e.id,callback:function(a){e.id=a},expression:"id"}})],1),i("v-uni-view",{staticClass:"real-submit",on:{click:function(a){arguments[0]=a=e.$handleEvent(a),e.submit.apply(void 0,arguments)}}},[e._v("立即提交")])],1),e.realType?i("v-uni-view",{staticClass:"real-suc"},[i("v-uni-icon",{staticClass:"icon-a",attrs:{type:"success",size:"70",color:"#9CE480"}}),i("v-uni-view",{staticClass:"real-suc-title"},[e._v("已实名认证")]),i("v-uni-view",{staticClass:"real-suc-id"},[i("v-uni-image",{staticClass:"real-suc-id-img",attrs:{src:t("796a"),mode:"widthFix"}}),i("v-uni-view",{staticClass:"real-suc-id-name"},[e._v(e._s(e.realName))]),i("v-uni-view",{staticClass:"real-suc-id-idNum"},[e._v(e._s(e.realID))])],1)],1):e._e()],1)},n=[]},6512:function(e,a,t){var i=t("24fb");a=i(!1),a.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-8c75896c]{background-color:#0c0d0d}.real-fail[data-v-8c75896c]{padding:%?30?% %?20?%}.real-fail .real-item[data-v-8c75896c]{display:flex;align-items:center;color:#fff;font-size:%?30?%;background:#292b2b;border-radius:%?20?%;padding:%?20?% %?30?%;margin-bottom:%?50?%}.real-fail .real-item .real-title[data-v-8c75896c]{width:27%;text-align:center}.real-fail .real-item .real-name[data-v-8c75896c]{letter-spacing:%?10?%}.real-fail .real-item .real-input[data-v-8c75896c]{margin-left:%?20?%}.real-fail .real-submit[data-v-8c75896c]{width:80%;padding:%?20?% %?50?%;text-align:center;margin:%?100?% auto 0;background:#2b2d2d;color:#fff;border-radius:%?50?%;font-size:%?36?%;font-weight:700}.real-suc[data-v-8c75896c]{padding:%?120?% %?50?%;text-align:center;color:#fff;font-size:%?30?%}.real-suc .real-suc-title[data-v-8c75896c]{font-size:%?50?%;margin:%?40?% 0 %?80?%}.real-suc .real-suc-id[data-v-8c75896c]{width:100%;position:relative;color:#000}.real-suc .real-suc-id .real-suc-id-img[data-v-8c75896c]{width:100%}.real-suc .real-suc-id .real-suc-id-name[data-v-8c75896c]{position:absolute;top:%?100?%;left:%?50?%}.real-suc .real-suc-id .real-suc-id-idNum[data-v-8c75896c]{position:absolute;top:%?250?%;left:%?50?%}body.?%PAGE?%[data-v-8c75896c]{background-color:#0c0d0d}',""]),e.exports=a},"796a":function(e,a,t){e.exports=t.p+"static/img/userinfo.7a5b2f2e.png"},"7b57":function(e,a,t){"use strict";function i(e){var a;return function(){var t=this,i=arguments;a&&clearTimeout(a),a=setTimeout((function(){e.apply(t,i)}),a)}}function r(e){return/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(e)}function n(e){return/^([\u4e00-\u9fa5]{1,20}|[a-zA-Z\.\s]{1,20})$/.test(e)}function c(e){var a=e.replace(/<img[^>]*>/gi,(function(e,a){return e=e.replace(/style="[^"]+"/gi,"").replace(/style='[^']+'/gi,""),e=e.replace(/width="[^"]+"/gi,"").replace(/width='[^']+'/gi,""),e=e.replace(/height="[^"]+"/gi,"").replace(/height='[^']+'/gi,""),e}));return a=a.replace(/style="[^"]+"/gi,(function(e,a){return e=e.replace(/width:[^;]+;/gi,"max-width:100%;").replace(/width:[^;]+;/gi,"max-width:100%;"),e})),a=a.replace(/<br[^>]*\/>/gi,""),a=a.replace(/\<img/gi,'<img style="max-width:100%;height:auto;display:block;margin-top:0;margin-bottom:0;"'),a}t("ac1f"),t("5319"),Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var s={debounce:i,checkIdCard:r,checkName:n,formatRichText:c};a.default=s},a884:function(e,a,t){"use strict";var i=t("3266"),r=t.n(i);r.a},dbd8:function(e,a,t){"use strict";t.r(a);var i=t("64fd"),r=t("0bc4");for(var n in r)"default"!==n&&function(e){t.d(a,e,(function(){return r[e]}))}(n);t("a884");var c,s=t("f0c5"),l=Object(s["a"])(r["default"],i["b"],i["c"],!1,null,"8c75896c",null,!1,i["a"],c);a["default"]=l.exports}}]);