(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-invite-invitePoster"],{"114d":function(e,n,t){"use strict";t.r(n);var a=t("e6e6"),o=t.n(a);for(var i in a)"default"!==i&&function(e){t.d(n,e,(function(){return a[e]}))}(i);n["default"]=o.a},4189:function(e,n,t){"use strict";t.r(n);var a=t("775c"),o=t("114d");for(var i in o)"default"!==i&&function(e){t.d(n,e,(function(){return o[e]}))}(i);var s,r=t("f0c5"),c=Object(r["a"])(o["default"],a["b"],a["c"],!1,null,"45fdcf4c",null,!1,a["a"],s);n["default"]=c.exports},"775c":function(e,n,t){"use strict";t.d(n,"b",(function(){return o})),t.d(n,"c",(function(){return i})),t.d(n,"a",(function(){return a}));var a={uqrcode:t("e905").default},o=function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("v-uni-view",{staticClass:"content"},[t("v-uni-view",{staticStyle:{position:"fixed",top:"-999px",left:"0"}},[t("uqrcode",{ref:"uqrcode"})],1),e.show?t("v-uni-view",[t("v-uni-canvas",{staticStyle:{position:"fixed",top:"-999px",left:"0"},style:{width:e.canvasW+"px",height:e.canvasH+"px"},attrs:{"canvas-id":"myCanvas",id:"myCanvas"}})],1):e._e(),""!=e.img?t("v-uni-image",{staticStyle:{width:"100%"},style:{height:"92vh"},attrs:{crossOrigin:"*",src:e.img,mode:""}}):e._e()],1)},i=[]},e6e6:function(e,n,t){"use strict";var a=t("4ea4");t("d3b7"),t("ddb0"),Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0,t("96cf");var o=a(t("1da1")),i=(a(t("11c1")),{data:function(){return{title:"邀请好友",isback:!0,background:"#FFF",txtColor:"#A4A4A4",backColor:"",canvasW:0,canvasH:0,SystemInfo:{},goodsImg:{},ewmImg:{},otherImg:{},ewmW:120,show:!0,img:"",url:"",list:"",urcode:"",tip:!1}},onLoad:function(){var e=this;return(0,o.default)(regeneratorRuntime.mark((function n(){var t;return regeneratorRuntime.wrap((function(n){while(1)switch(n.prev=n.next){case 0:return t=e,n.next=3,t.$request("get","user/poster",{}).then((function(e){console.log("res",e),t.goodsImg=e.data.values.file_path})).catch((function(e){console.log("err",e)}));case 3:case"end":return n.stop()}}),n)})))()},onReady:function(){var e=this;return(0,o.default)(regeneratorRuntime.mark((function n(){var t;return regeneratorRuntime.wrap((function(n){while(1)switch(n.prev=n.next){case 0:return t=e,n.next=3,t.$refs.uqrcode.make({size:354,text:t.$config.inviteUrl+"?code="+uni.getStorageSync("userinfo").userId}).then((function(e){console.log(e,"res"),t.urcode=e.tempFilePath}));case 3:return n.next=5,t.getSystemInfo();case 5:t.SystemInfo=n.sent,t.canvasW=t.SystemInfo.windowWidth,t.canvasH=t.SystemInfo.windowHeight-44,console.log("ok"),uni.showToast({icon:"loading",mask:!0,duration:1e4,title:"海报绘制中"}),setTimeout((function(){var e=uni.createCanvasContext("myCanvas",t);e.setFillStyle("#fff"),e.fillRect(0,0,t.canvasW,t.canvasH),e.drawImage(t.goodsImg,0,0,t.canvasW,t.canvasH),e.drawImage(t.urcode,t.canvasW/2-t.ewmW/2,t.canvasH/2-t.ewmW/2,t.ewmW,t.ewmW);t.canvasW;e.setFontSize(17),e.setFillStyle("#FFF"),e.fillText("推荐码: "+uni.getStorageSync("userinfo").userId,t.canvasW/2.8-20,t.canvasH-60),e.fillText("扫一扫，参与精彩",t.canvasW/2.8-20,t.canvasH-30),e.draw(!0,(function(e){console.log(e),uni.showToast({icon:"none",title:"绘制完成"}),setTimeout((function(){uni.canvasToTempFilePath({canvasId:"myCanvas",quality:1,fileType:"jpg",success:function(e){console.log("resssss",e),t.show=!1,t.img=e.tempFilePath},fail:function(e){alert("no",e)}})}),1200)}))}),1500);case 11:case"end":return n.stop()}}),n)})))()},methods:{getImageInfo:function(e){return new Promise((function(n,t){uni.getImageInfo({src:e,success:function(e){n(e)}})}))},getSystemInfo:function(){return new Promise((function(e,n){uni.getSystemInfo({success:function(n){e(n)}})}))}}});n.default=i}}]);