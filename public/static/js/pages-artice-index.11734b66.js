(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-artice-index"],{"0428":function(t,e,a){"use strict";a.r(e);var n=a("d0da"),i=a.n(n);for(var c in n)"default"!==c&&function(t){a.d(e,t,(function(){return n[t]}))}(c);e["default"]=i.a},"16c9":function(t,e,a){var n=a("24fb");e=n(!1),e.push([t.i,"uni-page-body[data-v-ce6261ea]{background-color:#fff;color:#333;padding:%?22?%}.title[data-v-ce6261ea]{font-size:17px;font-weight:700}.flex-space[data-v-ce6261ea]{display:flex;justify-content:space-between;align-items:center;margin-top:%?10?%;margin-bottom:%?20?%}body.?%PAGE?%[data-v-ce6261ea]{background-color:#fff}",""]),t.exports=e},"4a02":function(t,e,a){"use strict";var n;a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return c})),a.d(e,"a",(function(){return n}));var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",[a("v-uni-view",{staticClass:"title"},[t._v(t._s(t.detail.title))]),a("v-uni-view",{staticClass:"flex-space"},[a("v-uni-view",[t._v(t._s(t.detail.show_views)+t._s(t.txt))])],1),a("v-uni-rich-text",{attrs:{nodes:t.detail.content}})],1)},c=[]},"6cf9":function(t,e,a){"use strict";var n=a("bed9"),i=a.n(n);i.a},bed9:function(t,e,a){var n=a("16c9");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("78eed28c",n,!0,{sourceMap:!1,shadowMode:!1})},c7fb:function(t,e,a){"use strict";a.r(e);var n=a("4a02"),i=a("0428");for(var c in i)"default"!==c&&function(t){a.d(e,t,(function(){return i[t]}))}(c);a("6cf9");var o,r=a("f0c5"),u=Object(r["a"])(i["default"],n["b"],n["c"],!1,null,"ce6261ea",null,!1,n["a"],o);e["default"]=u.exports},d0da:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={data:function(){return{detail:"",txt:""}},methods:{getDetail:function(t){var e=this;this.$request("get","article/detail",{articleId:t}).then((function(t){console.log("res",t),e.detail=t.data.detail,e.txt="次浏览"})).catch((function(t){console.log("err",t)}))}},onLoad:function(t){var e=t.id;this.getDetail(e)}};e.default=n}}]);