(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-artice-index"],{1029:function(t,e,n){var a=n("3c7d");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var i=n("4f06").default;i("10e3d854",a,!0,{sourceMap:!1,shadowMode:!1})},1177:function(t,e,n){"use strict";var a=n("1029"),i=n.n(a);i.a},3421:function(t,e,n){"use strict";n.r(e);var a=n("5441"),i=n("baba");for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);n("1177");var r,c=n("f0c5"),s=Object(c["a"])(i["default"],a["b"],a["c"],!1,null,"276a29d9",null,!1,a["a"],r);e["default"]=s.exports},"347b":function(t,e,n){"use strict";n("ac1f"),n("5319"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={data:function(){return{detail:"",txt:""}},computed:{changeContent:function(){return function(t){var e="";return t&&(e=t.content.replace(/<img/g,"<img style='width: 100%;'")),e}}},methods:{getDetail:function(t){var e=this;this.$request("get","article/detail",{articleId:t}).then((function(t){console.log("res",t),e.detail=t.data.detail,e.txt="次浏览"})).catch((function(t){console.log("err",t)}))}},onLoad:function(t){console.log(t);var e=t.articleId;this.getDetail(e)}};e.default=a},"3c7d":function(t,e,n){var a=n("24fb");e=a(!1),e.push([t.i,"uni-page-body[data-v-276a29d9]{background-color:#101010;color:#fff;padding:%?22?%}.title[data-v-276a29d9]{font-size:17px;font-weight:700}.flex-space[data-v-276a29d9]{display:flex;justify-content:space-between;align-items:center;margin-top:%?10?%;margin-bottom:%?20?%}.story[data-v-276a29d9]{width:100%;overflow:hidden;color:#fff;margin-bottom:%?30?%;box-sizing:border-box;.story-author{display:flex;align-items:center;justify-content:space-between;padding:%?30?% %?20?% %?20?%;color:#a3a3a3;font-size:%?35?%;.story-author-text{color:#fff}}}body.?%PAGE?%[data-v-276a29d9]{background-color:#101010}",""]),t.exports=e},5441:function(t,e,n){"use strict";var a;n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return a}));var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",[n("v-uni-view",{staticClass:"title"},[t._v(t._s(t.detail.title))]),n("v-uni-view",{staticClass:"flex-space"},[n("v-uni-view",[t._v(t._s(t.detail.show_views)+t._s(t.txt))])],1),n("v-uni-view",{staticClass:"story"},[n("v-uni-view",{staticClass:"story-content"},[n("v-uni-rich-text",{attrs:{img:"width: 100%;",nodes:t.changeContent(t.detail)}})],1)],1)],1)},o=[]},baba:function(t,e,n){"use strict";n.r(e);var a=n("347b"),i=n.n(a);for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);e["default"]=i.a}}]);