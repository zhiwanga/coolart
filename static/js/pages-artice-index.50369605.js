(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-artice-index"],{"0627":function(t,e,n){"use strict";n.r(e);var a=n("7139"),i=n.n(a);for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);e["default"]=i.a},"484c":function(t,e,n){"use strict";var a=n("e3e8"),i=n.n(a);i.a},6866:function(t,e,n){"use strict";var a;n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return a}));var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",[n("v-uni-view",{staticClass:"title"},[t._v(t._s(t.detail.title))]),n("v-uni-view",{staticClass:"flex-space"},[n("v-uni-view",[t._v(t._s(t.detail.show_views)+"次浏览")])],1),n("v-uni-rich-text",{attrs:{nodes:t.detail.content}})],1)},o=[]},"6ba3":function(t,e,n){var a=n("24fb");e=a(!1),e.push([t.i,"uni-page-body[data-v-53eff29e]{background-color:#fff;color:#333;padding:%?22?%}.title[data-v-53eff29e]{font-size:17px;font-weight:700}.flex-space[data-v-53eff29e]{display:flex;justify-content:space-between;align-items:center;margin-top:%?10?%;margin-bottom:%?20?%}body.?%PAGE?%[data-v-53eff29e]{background-color:#fff}",""]),t.exports=e},"6dab":function(t,e,n){"use strict";n.r(e);var a=n("6866"),i=n("0627");for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);n("484c");var r,c=n("f0c5"),f=Object(c["a"])(i["default"],a["b"],a["c"],!1,null,"53eff29e",null,!1,a["a"],r);e["default"]=f.exports},7139:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={data:function(){return{detail:""}},methods:{getDetail:function(t){var e=this;this.$request("get","article/detail",{articleId:t}).then((function(t){console.log("res",t),e.detail=t.data.detail})).catch((function(t){console.log("err",t)}))}},onLoad:function(t){var e=t.id;this.getDetail(e)}};e.default=a},e3e8:function(t,e,n){var a=n("6ba3");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var i=n("4f06").default;i("6f11804f",a,!0,{sourceMap:!1,shadowMode:!1})}}]);