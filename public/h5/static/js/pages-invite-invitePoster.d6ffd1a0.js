(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-invite-invitePoster"],{"114d":function(e,t,n){"use strict";n.r(t);var i=n("e6e6"),a=n.n(i);for(var s in i)"default"!==s&&function(e){n.d(t,e,(function(){return i[e]}))}(s);t["default"]=a.a},4189:function(e,t,n){"use strict";n.r(t);var i=n("775c"),a=n("114d");for(var s in a)"default"!==s&&function(e){n.d(t,e,(function(){return a[e]}))}(s);var o,r=n("f0c5"),c=Object(r["a"])(a["default"],i["b"],i["c"],!1,null,"45fdcf4c",null,!1,i["a"],o);t["default"]=c.exports},"775c":function(e,t,n){"use strict";n.d(t,"b",(function(){return a})),n.d(t,"c",(function(){return s})),n.d(t,"a",(function(){return i}));var i={uqrcode:n("e905").default},a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-uni-view",{staticClass:"content"},[n("v-uni-view",{staticStyle:{position:"fixed",top:"-999px",left:"0"}},[n("uqrcode",{ref:"uqrcode"})],1),e.show?n("v-uni-view",[n("v-uni-canvas",{staticStyle:{position:"fixed",top:"-999px",left:"0"},style:{width:e.canvasW+"px",height:e.canvasH+"px"},attrs:{"canvas-id":"myCanvas",id:"myCanvas"}})],1):e._e(),""!=e.img?n("v-uni-image",{staticStyle:{width:"100%"},style:{height:"92vh"},attrs:{crossOrigin:"*",src:e.img,mode:""}}):e._e()],1)},s=[]},e6e6:function(e,t,n){"use strict";(function(e){var i=n("4ea4");n("d3b7"),n("ddb0"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,n("96cf");var a=i(n("1da1")),s=(i(n("11c1")),{data:function(){return{title:"邀请好友",isback:!0,background:"#FFF",txtColor:"#A4A4A4",backColor:"",canvasW:0,canvasH:0,SystemInfo:{},goodsImg:{},ewmImg:{},otherImg:{},ewmW:120,show:!0,img:"",url:"",list:"",urcode:"",tip:!1}},onLoad:function(){var t=this;return(0,a.default)(regeneratorRuntime.mark((function n(){var i;return regeneratorRuntime.wrap((function(n){while(1)switch(n.prev=n.next){case 0:return i=t,n.next=3,i.$request("get","user/poster",{}).then((function(t){e("log","res",t," at pages/invite/invitePoster.vue:44"),i.goodsImg=t.data.values.file_path})).catch((function(t){e("log","err",t," at pages/invite/invitePoster.vue:47")}));case 3:case"end":return n.stop()}}),n)})))()},onReady:function(){var t=this;return(0,a.default)(regeneratorRuntime.mark((function n(){var i;return regeneratorRuntime.wrap((function(n){while(1)switch(n.prev=n.next){case 0:return i=t,n.next=3,i.$refs.uqrcode.make({size:354,text:i.$config.inviteUrl+"?code="+uni.getStorageSync("userinfo").userId}).then((function(t){e("log",t,"res"," at pages/invite/invitePoster.vue:62"),i.urcode=t.tempFilePath}));case 3:return n.next=5,i.getSystemInfo();case 5:i.SystemInfo=n.sent,i.canvasW=i.SystemInfo.windowWidth,i.canvasH=i.SystemInfo.windowHeight-44,e("log","ok"," at pages/invite/invitePoster.vue:78"),uni.showToast({icon:"loading",mask:!0,duration:1e4,title:"海报绘制中"}),setTimeout((function(){var t=uni.createCanvasContext("myCanvas",i);t.setFillStyle("#fff"),t.fillRect(0,0,i.canvasW,i.canvasH),t.drawImage(i.goodsImg,0,0,i.canvasW,i.canvasH),t.drawImage(i.urcode,i.canvasW/2-i.ewmW/2,i.canvasH/2-i.ewmW/2,i.ewmW,i.ewmW);i.canvasW;t.setFontSize(17),t.setFillStyle("#FFF"),t.fillText("推荐码: "+uni.getStorageSync("userinfo").userId,i.canvasW/2.8-20,i.canvasH-60),t.fillText("扫一扫，参与精彩",i.canvasW/2.8-20,i.canvasH-30),t.draw(!0,(function(t){e("log",t," at pages/invite/invitePoster.vue:114"),uni.showToast({icon:"none",title:"绘制完成"}),setTimeout((function(){uni.canvasToTempFilePath({canvasId:"myCanvas",quality:1,fileType:"jpg",success:function(t){e("log","resssss",t," at pages/invite/invitePoster.vue:126"),i.show=!1,i.img=t.tempFilePath},fail:function(e){alert("no",e)}})}),1200)}))}),1500);case 11:case"end":return n.stop()}}),n)})))()},methods:{getImageInfo:function(e){return new Promise((function(t,n){uni.getImageInfo({src:e,success:function(e){t(e)}})}))},getSystemInfo:function(){return new Promise((function(e,t){uni.getSystemInfo({success:function(t){e(t)}})}))}}});t.default=s}).call(this,n("0de9")["log"])}}]);