(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["order"],{"3a10":function(e,t,a){"use strict";a.d(t,"b",(function(){return i})),a.d(t,"a",(function(){return n}));var r=a("b775"),s={list:"/order/list",detail:"/order/detail"};function i(e){return Object(r["b"])({url:s.list,method:"get",params:e})}function n(e){return Object(r["b"])({url:s.detail,method:"get",params:e})}},"4a95":function(e,t,a){"use strict";a.d(t,"a",(function(){return s})),a.d(t,"b",(function(){return i})),a.d(t,"c",(function(){return n})),a.d(t,"d",(function(){return o})),a.d(t,"e",(function(){return l})),a.d(t,"f",(function(){return d})),a.d(t,"g",(function(){return c}));var r=a("5c06"),s=new r["a"]([{key:"NOT_DELIVERED",name:"未发货",value:10},{key:"DELIVERED",name:"已发货",value:20}]),i=new r["a"]([{key:"EXPRESS",name:"快递配送",value:10}]),n=new r["a"]([{key:"MASTER",name:"普通订单",value:10}]),o=new r["a"]([{key:"NORMAL",name:"进行中",value:10},{key:"CANCELLED",name:"已取消",value:20},{key:"APPLY_CANCEL",name:"待取消",value:21},{key:"COMPLETED",name:"已完成",value:30}]),l=new r["a"]([{key:"PENDING",name:"待支付",value:10},{key:"SUCCESS",name:"已支付",value:20}]),d=new r["a"]([{key:"BALANCE",name:"余额支付",value:10},{key:"WECHAT",name:"微信支付",value:20},{key:"APALY",name:"支付宝支付",value:30},{key:"INTERGRAL",name:"积分支付",value:40}]),c=new r["a"]([{key:"NOT_RECEIVED",name:"未收货",value:10},{key:"RECEIVED",name:"已收货",value:20}])},"5e82":function(e,t,a){"use strict";var r=a("f459"),s=a.n(r);s.a},"758b":function(e,t,a){"use strict";var r=a("fbf8"),s=a.n(r);s.a},"884a":function(e,t,a){"use strict";a.d(t,"c",(function(){return i})),a.d(t,"d",(function(){return n})),a.d(t,"a",(function(){return o})),a.d(t,"b",(function(){return l}));var r=a("b775"),s={delivery:"/order.event/delivery",updatePrice:"/order.event/updatePrice",confirmCancel:"/order.event/confirmCancel",delete:"/order.event/delete"};function i(e){return Object(r["b"])({url:s.delivery,method:"post",data:e})}function n(e){return Object(r["b"])({url:s.updatePrice,method:"post",data:e})}function o(e){return Object(r["b"])({url:s.confirmCancel,method:"post",data:e})}function l(e){return Object(r["b"])({url:s.delete,method:"post",data:{orderId:e}})}},ab06:function(e,t,a){"use strict";a.r(t);var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("a-spin",{attrs:{spinning:e.isLoading}}),e.isLoading?e._e():a("div",{staticClass:"order-content"},[a("a-card",{attrs:{bordered:!1}},[a("div",{staticClass:"order-progress",class:"progress-"+e.progress},[a("ul",[a("li",[a("span",[e._v("下单时间")]),a("div",{staticClass:"tip"},[e._v(e._s(e.record.create_time))])]),a("li",[a("span",[e._v("付款")]),e.record.pay_status==e.PayStatusEnum.SUCCESS.value?a("div",{staticClass:"tip"},[e._v("付款于 "+e._s(e.record.pay_time))]):e._e()]),a("li",[a("span",[e._v("发货")]),e.record.delivery_status==e.DeliveryStatusEnum.DELIVERED.value?a("div",{staticClass:"tip"},[e._v("发货于 "+e._s(e.record.delivery_time))]):e._e()]),a("li",[a("span",[e._v("收货")]),e.record.receipt_status==e.ReceiptStatusEnum.RECEIVED.value?a("div",{staticClass:"tip"},[e._v("收货于 "+e._s(e.record.receipt_time))]):e._e()]),a("li",[a("span",[e._v("完成")]),e.record.order_status==e.OrderStatusEnum.COMPLETED.value?a("div",{staticClass:"tip"},[e._v("完成于 "+e._s(e.record.receipt_time))]):e._e()])])])]),a("a-card",{staticClass:"mt-20",attrs:{bordered:!1}},[e.record.order_status!=e.OrderStatusEnum.CANCELLED.value?[a("div",{staticClass:"ant-descriptions-title"},[e._v("订单操作")]),a("div",{staticClass:"alerts mt-10 mb-15"},[e.record.order_status==e.OrderStatusEnum.APPLY_CANCEL.value?a("a-alert",{attrs:{message:"当前买家已付款并申请取消订单，请审核是否同意，如同意则自动退回付款金额（原路退款）并关闭订单。",banner:""}}):e._e()],1),a("div",{staticClass:"actions clearfix mt-10"},[e.$auth("/order/detail.updatePrice")?a("div",{staticClass:"action-item"},[e.record.pay_status==e.PayStatusEnum.PENDING.value?a("a-button",{on:{click:e.handleUpdatePrice}},[e._v("订单改价")]):e._e()],1):e._e(),e.$auth("/order/list/all.deliver")?a("div",{staticClass:"action-item"},[e.record.pay_status!=e.PayStatusEnum.SUCCESS.value||e.record.delivery_type!=e.DeliveryTypeEnum.EXPRESS.value||e.record.delivery_status!=e.DeliveryStatusEnum.NOT_DELIVERED.value||e.inArray(e.record.order_status,[e.OrderStatusEnum.CANCELLED.value,e.OrderStatusEnum.APPLY_CANCEL.value])?e._e():a("a-button",{attrs:{type:"primary"},on:{click:e.handleDelivery}},[e._v("发货")])],1):e._e(),e.$auth("/order/list/all.cancel")?a("div",{staticClass:"action-item"},[e.record.order_status==e.OrderStatusEnum.APPLY_CANCEL.value?a("a-button",{attrs:{type:"primary"},on:{click:e.handleCancel}},[e._v("审核取消订单")]):e._e()],1):e._e()]),a("a-divider",{staticClass:"o-divider"})]:e._e(),a("a-descriptions",{attrs:{title:"订单信息"}},[a("a-descriptions-item",{attrs:{label:"订单号"}},[e._v(e._s(e.record.order_no))]),a("a-descriptions-item",{attrs:{label:"实付款金额"}},[e._v("￥"+e._s(e.record.pay_price))]),a("a-descriptions-item",{attrs:{label:"支付方式"}},[a("a-tag",{attrs:{color:"green"}},[e._v(e._s(e.PayTypeEnum[e.record.pay_type].name))])],1),a("a-descriptions-item",{attrs:{label:"配送方式"}},[a("a-tag",{attrs:{color:"green"}},[e._v(e._s(e.DeliveryTypeEnum[e.record.delivery_type].name))])],1),a("a-descriptions-item",{attrs:{label:"运费金额"}},[e._v("￥"+e._s(e.record.express_price))]),a("a-descriptions-item",{attrs:{label:"订单状态"}},[a("a-tag",{attrs:{color:e.renderOrderStatusColor(e.record.order_status)}},[e._v(e._s(e.OrderStatusEnum[e.record.order_status].name))])],1),a("a-descriptions-item",{attrs:{label:"买家信息"}},[a("a-tooltip",[a("template",{slot:"title"},[e._v("会员ID: "+e._s(e.record.user.user_id))]),a("span",{staticClass:"c-p"},[e._v(e._s(e.record.user.nick_name))])],2)],1),a("a-descriptions-item",{attrs:{label:"买家留言"}},[e.record.buyer_remark?a("strong",[e._v(e._s(e.record.buyer_remark))]):a("span",[e._v("--")])])],1)],2),a("a-card",{staticClass:"mt-20",attrs:{bordered:!1}},[a("div",{staticClass:"ant-descriptions-title"},[e._v("订单商品")]),a("div",{staticClass:"goods-list"},[a("a-table",{attrs:{rowKey:"order_goods_id",columns:e.goodsColumns,dataSource:e.record.goods,pagination:!1},scopedSlots:e._u([{key:"goodsInfo",fn:function(e,t){return[a("GoodsItem",{attrs:{data:{image:t.goods_image,imageAlt:"商品图片",title:t.goods_name,goodsProps:t.goods_props}}})]}},{key:"goods_no",fn:function(t){return a("span",{},[e._v(e._s(t||"--"))])}},{key:"goods_price",fn:function(t,r){return[a("p",{class:{"f-through":r.is_user_grade}},[e._v("￥"+e._s(t))]),r.is_user_grade?a("p",[a("a-tooltip",[a("template",{slot:"title"},[a("span",{staticClass:"f-13"},[e._v("会员等级折扣价")])]),a("strong",[e._v("会员价：")]),a("span",[e._v("￥"+e._s(r.grade_goods_price))])],2)],1):e._e()]}},{key:"total_num",fn:function(t){return a("span",{},[e._v("x"+e._s(t))])}},{key:"total_price",fn:function(t){return a("span",{},[e._v("￥"+e._s(t))])}}],null,!1,369901077)}),a("div",{staticClass:"order-price"},[a("table",{staticClass:"fl-r"},[a("tbody",[a("tr",[a("td",[e._v("订单总额：")]),a("td",[e._v("￥"+e._s(e.record.total_price))])]),e.record.coupon_money>0?a("tr",[a("td",[e._v("优惠券抵扣：")]),a("td",[e._v("-￥"+e._s(e.record.coupon_money))])]):e._e(),e.record.points_money>0?a("tr",[a("td",[e._v("积分抵扣：")]),a("td",[e._v("-￥"+e._s(e.record.points_money))])]):e._e(),0!=e.record.update_price.value?a("tr",[a("td",[e._v("商家改价：")]),a("td",[e._v(e._s(e.record.update_price.symbol)+"￥"+e._s(e.record.update_price.value))])]):e._e(),a("tr",[a("td",[e._v("运费金额：")]),a("td",[e._v("+￥"+e._s(e.record.express_price))])]),a("tr",[a("td",[e._v("实付款金额：")]),a("td",[a("strong",[e._v("￥"+e._s(e.record.pay_price))])])])])])])],1)]),e.record.delivery_type==e.DeliveryTypeEnum.EXPRESS.value?a("a-card",{staticClass:"mt-20",attrs:{bordered:!1}},[a("a-descriptions",{attrs:{title:"收货信息"}},[a("a-descriptions-item",{attrs:{label:"收货人姓名"}},[e._v(e._s(e.record.address.name))]),a("a-descriptions-item",{attrs:{label:"联系电话"}},[e._v(e._s(e.record.address.phone))]),a("a-descriptions-item",{attrs:{label:"收货地区"}},[e._v(" "+e._s(e.record.address.region.province)+" "+e._s(e.record.address.region.city)+" "+e._s(e.record.address.region.region)+" ")]),a("a-descriptions-item",{attrs:{label:"详细地址"}},[e._v(e._s(e.record.address.detail))])],1),e.record.pay_status!=e.PayStatusEnum.SUCCESS.value||e.record.delivery_status!=e.DeliveryStatusEnum.DELIVERED.value||e.inArray(e.record.order_status,[e.OrderStatusEnum.CANCELLED.value,e.OrderStatusEnum.APPLY_CANCEL.value])?e._e():[a("a-divider",{staticClass:"o-divider"}),a("a-descriptions",{attrs:{title:"发货信息"}},[a("a-descriptions-item",{attrs:{label:"物流公司"}},[e._v(e._s(e.record.express.express_name))]),a("a-descriptions-item",{attrs:{label:"物流单号"}},[e._v(e._s(e.record.express_no))]),a("a-descriptions-item",{attrs:{label:"发货状态"}},[a("a-tag",{attrs:{color:e.record.delivery_status==e.DeliveryStatusEnum.DELIVERED.value?"green":""}},[e._v(e._s(e.DeliveryStatusEnum[e.record.delivery_status].name))])],1),a("a-descriptions-item",{attrs:{label:"发货时间"}},[e._v(e._s(e.record.delivery_time))])],1)]],2):e._e()],1),a("DeliveryForm",{ref:"DeliveryForm",on:{handleSubmit:e.handleRefresh}}),a("CancelForm",{ref:"CancelForm",on:{handleSubmit:e.handleRefresh}}),a("PriceForm",{ref:"PriceForm",on:{handleSubmit:e.handleRefresh}})],1)},s=[],i=(a("d3b7"),a("ade3")),n=a("ca00"),o=a("3a10"),l=a("ab09"),d=a("ac82"),c=a("4a95"),u=[{title:"商品信息",scopedSlots:{customRender:"goodsInfo"}},{title:"商品编码",dataIndex:"goods_no",scopedSlots:{customRender:"goods_no"}},{title:"重量(Kg)",dataIndex:"goods_weight",scopedSlots:{customRender:"goods_weight"}},{title:"单价",dataIndex:"goods_price",scopedSlots:{customRender:"goods_price"}},{title:"购买数量",dataIndex:"total_num",scopedSlots:{customRender:"total_num"}},{title:"商品总价",dataIndex:"total_price",scopedSlots:{customRender:"total_price"}}],m={name:"Index",components:{GoodsItem:l["a"],UserItem:l["c"],DeliveryForm:d["b"],CancelForm:d["a"],PriceForm:d["c"]},data:function(){return{DeliveryStatusEnum:c["a"],DeliveryTypeEnum:c["b"],OrderSourceEnum:c["c"],OrderStatusEnum:c["d"],PayStatusEnum:c["e"],PayTypeEnum:c["f"],ReceiptStatusEnum:c["g"],inArray:n["d"],isLoading:!0,orderId:null,record:{},progress:2,goodsColumns:u}},created:function(){this.orderId=this.$route.query.orderId,this.handleRefresh()},methods:{handleRefresh:function(){this.getDetail()},getDetail:function(){var e=this,t=this.orderId;this.isLoading=!0,o["a"]({orderId:t}).then((function(t){e.record=t.data.detail,e.initData()})).finally((function(){e.isLoading=!1}))},initData:function(){this.initProgress()},initProgress:function(){var e=this.record;this.progress=2,e.pay_status===c["e"].SUCCESS.value&&(this.progress+=1),e.delivery_status===c["a"].DELIVERED.value&&(this.progress+=1),e.receipt_status===c["g"].RECEIVED.value&&(this.progress+=1)},renderOrderStatusColor:function(e){var t,a=this.OrderStatusEnum,r=(t={},Object(i["a"])(t,a.NORMAL.value,""),Object(i["a"])(t,a.CANCELLED.value,"red"),Object(i["a"])(t,a.APPLY_CANCEL.value,"red"),Object(i["a"])(t,a.COMPLETED.value,"green"),t);return r[e]},handleDelivery:function(){var e=this.record;this.$refs.DeliveryForm.show(e)},handleCancel:function(){var e=this.record;this.$refs.CancelForm.show(e)},handleUpdatePrice:function(){var e=this.record;this.$refs.PriceForm.show(e)}}},p=m,v=(a("758b"),a("2877")),_=Object(v["a"])(p,r,s,!1,null,"28d6a36f",null);t["default"]=_.exports},ac82:function(e,t,a){"use strict";a.d(t,"b",(function(){return p})),a.d(t,"a",(function(){return g})),a.d(t,"c",(function(){return x}));var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("a-modal",{attrs:{title:e.title,width:580,visible:e.visible,isLoading:e.isLoading,maskClosable:!1},on:{ok:e.handleSubmit,cancel:e.handleCancel}},[a("a-spin",{attrs:{spinning:e.isLoading}},[a("a-form",{attrs:{form:e.form}},[a("a-form-item",{attrs:{label:"物流公司",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[a("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["express_id",{rules:[{required:!0,message:"请选择物流公司"}]}],expression:"['express_id', { rules: [{ required: true, message: '请选择物流公司' }] }]"}],attrs:{placeholder:"请选择物流公司"}},e._l(e.expressList,(function(t,r){return a("a-select-option",{key:r,attrs:{value:t.express_id}},[e._v(e._s(t.express_name))])})),1),a("div",{staticClass:"form-item-help"},[a("router-link",{attrs:{target:"_blank",to:{path:"/setting/delivery/express/index"}}},[e._v("物流公司管理")])],1)],1),a("a-form-item",{attrs:{label:"物流单号",labelCol:e.labelCol,wrapperCol:e.wrapperCol,extra:"请手动录入物流单号或快递单号"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["express_no",{rules:[{required:!0,message:"请输入物流单号"}]}],expression:"['express_no', { rules: [{ required: true, message: '请输入物流单号' }] }]"}]})],1)],1)],1)],1)},s=[],i=(a("d3b7"),a("884a")),n=a("b775"),o={list:"/setting.express/list",all:"/setting.express/all",add:"/setting.express/add",edit:"/setting.express/edit",delete:"/setting.express/delete"};function l(e){return Object(n["b"])({url:o.all,method:"get",params:e})}var d={data:function(){return{title:"订单发货",labelCol:{span:7},wrapperCol:{span:13},visible:!1,isLoading:!1,form:this.$form.createForm(this),expressList:[],record:{}}},created:function(){this.getExpressList()},methods:{show:function(e){this.visible=!0,this.record=e},getExpressList:function(){var e=this;this.isLoading=!0,l().then((function(t){return e.expressList=t.data.list})).finally((function(){return e.isLoading=!1}))},handleSubmit:function(e){var t=this;e.preventDefault();var a=this.form.validateFields;a((function(e,a){!e&&t.onFormSubmit(a)}))},handleCancel:function(){this.visible=!1,this.form.resetFields()},onFormSubmit:function(e){var t=this;this.isLoading=!0,i["c"]({orderId:this.record.order_id,form:e}).then((function(a){t.$message.success(a.message,1.5),t.handleCancel(),t.$emit("handleSubmit",e)})).finally((function(){return t.isLoading=!1}))}}},c=d,u=a("2877"),m=Object(u["a"])(c,r,s,!1,null,null,null),p=m.exports,v=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("a-modal",{attrs:{title:e.title,width:560,visible:e.visible,isLoading:e.isLoading,maskClosable:!1},on:{ok:e.handleSubmit,cancel:e.handleCancel}},[a("a-spin",{attrs:{spinning:e.isLoading}},[a("a-form",{attrs:{form:e.form}},[a("a-form-item",{attrs:{label:"实付款金额",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[a("span",[e._v("￥"+e._s(e.record.pay_price))])]),a("a-form-item",{attrs:{label:"审核状态",labelCol:e.labelCol,wrapperCol:e.wrapperCol,extra:"同意后将退回付款金额并关闭订单"}},[a("a-radio-group",{directives:[{name:"decorator",rawName:"v-decorator",value:["status",{initialValue:1,rules:[{required:!0}]}],expression:"['status', { initialValue: 1, rules: [{ required: true }] }]"}]},[a("a-radio",{attrs:{value:1}},[e._v("同意")]),a("a-radio",{attrs:{value:0}},[e._v("拒绝")])],1)],1)],1)],1)],1)},_=[],f={data:function(){return{title:"审核取消订单",labelCol:{span:7},wrapperCol:{span:13},visible:!1,isLoading:!1,form:this.$form.createForm(this),record:{}}},created:function(){},methods:{show:function(e){this.visible=!0,this.record=e},handleSubmit:function(e){var t=this;e.preventDefault();var a=this.form.validateFields;a((function(e,a){!e&&t.onFormSubmit(a)}))},handleCancel:function(){this.visible=!1,this.form.resetFields()},onFormSubmit:function(e){var t=this;this.isLoading=!0,i["a"]({orderId:this.record.order_id,form:e}).then((function(a){t.$message.success(a.message,1.5),t.handleCancel(),t.$emit("handleSubmit",e)})).finally((function(){t.isLoading=!1}))}}},h=f,b=Object(u["a"])(h,v,_,!1,null,null,null),g=b.exports,y=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("a-modal",{attrs:{title:e.title,width:560,visible:e.visible,isLoading:e.isLoading,maskClosable:!1},on:{ok:e.handleSubmit,cancel:e.handleCancel}},[a("a-spin",{attrs:{spinning:e.isLoading}},[a("a-form",{attrs:{form:e.form}},[a("a-form-item",{attrs:{label:"订单金额",labelCol:e.labelCol,wrapperCol:e.wrapperCol,extra:"最终付款金额 = 订单金额 + 运费金额"}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["order_price",{rules:[{required:!0,message:"请输入订单金额"}]}],expression:"['order_price', { rules: [{ required: true, message: '请输入订单金额' }] }]"}],attrs:{min:.01,precision:2}}),a("span",{staticClass:"ml-10"},[e._v("元")])],1),a("a-form-item",{attrs:{label:"运费金额",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[a("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["express_price",{rules:[{required:!0,message:"请输入运费金额"}]}],expression:"['express_price', { rules: [{ required: true, message: '请输入运费金额' }] }]"}],attrs:{min:0,precision:2}}),a("span",{staticClass:"ml-10"},[e._v("元")])],1)],1)],1)],1)},C=[],E=(a("a9e3"),a("2ef0")),S=a.n(E),L={data:function(){return{title:"修改订单金额",labelCol:{span:7},wrapperCol:{span:13},visible:!1,isLoading:!1,form:this.$form.createForm(this),record:{}}},created:function(){},methods:{show:function(e){this.visible=!0,this.record=e,this.setFieldsValue()},setFieldsValue:function(){var e=this.record,t=this.$nextTick,a=this.form.setFieldsValue;t((function(){var t=Number(e.update_price.value);"-"===e.update_price.symbol&&(t=-t),a({order_price:S.a.add(Number(e.order_price),t),express_price:Number(e.express_price)})}))},handleSubmit:function(e){var t=this;e.preventDefault();var a=this.form.validateFields;a((function(e,a){!e&&t.onFormSubmit(a)}))},handleCancel:function(){this.visible=!1,this.form.resetFields()},onFormSubmit:function(e){var t=this;this.isLoading=!0,i["d"]({orderId:this.record.order_id,form:e}).then((function(a){t.$message.success(a.message,1.5),t.handleCancel(),t.$emit("handleSubmit",e)})).finally((function(){t.isLoading=!1}))}}},D=L,w=Object(u["a"])(D,y,C,!1,null,null,null),x=w.exports},f459:function(e,t,a){},fbf8:function(e,t,a){},fecbd:function(e,t,a){"use strict";a.r(t);var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("a-card",{attrs:{bordered:!1}},[a("a-spin",{attrs:{spinning:e.isLoading}},[a("div",{staticClass:"card-title"},[e._v(e._s(e.$route.meta.title))]),a("div",{staticClass:"table-operator"},[a("a-row",{staticClass:"row-item-search"},[a("a-form",{staticClass:"search-form",attrs:{form:e.searchForm,layout:"inline"},on:{submit:e.handleSearch}},[a("a-form-item",{attrs:{label:"订单查询"}},[a("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["searchValue"],expression:"['searchValue']"}],staticStyle:{width:"342px"},attrs:{placeholder:"请输入关键词"}},[a("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["searchType",{initialValue:10}],expression:"['searchType', { initialValue: 10 }]"}],staticStyle:{width:"100px"},attrs:{slot:"addonBefore"},slot:"addonBefore"},e._l(e.SearchTypeEnum,(function(t,r){return a("a-select-option",{key:r,attrs:{value:t.value}},[e._v(e._s(t.name))])})),1)],1)],1),a("a-form-item",{attrs:{label:"订单来源"}},[a("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["orderSource",{initialValue:-1}],expression:"['orderSource', { initialValue: -1 }]"}]},[a("a-select-option",{attrs:{value:-1}},[e._v("全部")]),e._l(e.OrderSourceEnum.data,(function(t,r){return a("a-select-option",{key:r,attrs:{value:t.value}},[e._v(e._s(t.name))])}))],2)],1),a("a-form-item",{attrs:{label:"支付方式"}},[a("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["payType",{initialValue:-1}],expression:"['payType', { initialValue: -1 }]"}]},[a("a-select-option",{attrs:{value:-1}},[e._v("全部")]),e._l(e.PayTypeEnum.data,(function(t,r){return a("a-select-option",{key:r,attrs:{value:t.value}},[e._v(e._s(t.name))])}))],2)],1),a("a-form-item",{attrs:{label:"下单时间"}},[a("a-range-picker",{directives:[{name:"decorator",rawName:"v-decorator",value:["betweenTime"],expression:"['betweenTime']"}],attrs:{format:"YYYY-MM-DD"}})],1),a("a-form-item",{staticClass:"search-btn"},[a("a-button",{attrs:{type:"primary",icon:"search","html-type":"submit"}},[e._v("搜索")])],1),a("a-form-item",{staticClass:"search-btn"},[a("a-button",{on:{click:e.handleReset}},[e._v("重置")])],1)],1)],1)],1),a("div",{staticClass:"ant-table ant-table-scroll-position-left ant-table-default ant-table-bordered"},[a("div",{staticClass:"ant-table-content"},[a("div",{staticClass:"ant-table-body"},[a("table",[a("thead",{staticClass:"ant-table-thead"},[a("tr",e._l(e.columns,(function(t,r){return a("th",{key:r},[a("span",{staticClass:"ant-table-header-column"},[a("div",[a("span",{staticClass:"ant-table-column-title"},[e._v(e._s(t.title))])])])])})),0)]),a("tbody",{staticClass:"ant-table-tbody"},[e._l(e.orderList.data,(function(t){return[a("tr",{key:"order_"+t.order_id+"_1",staticClass:"order-empty"},[a("td",{attrs:{colspan:"8"}})]),a("tr",{key:"order_"+t.order_id+"_2"},[a("td",{attrs:{colspan:"8"}},[a("span",{staticClass:"mr-20"},[e._v(e._s(t.create_time))]),a("span",{staticClass:"mr-20"},[e._v("订单号："+e._s(t.order_no))]),a("platform-icon",{attrs:{name:t.platform,showTips:!0}})],1)]),e._l(t.goods,(function(r,s){return a("tr",{key:"orderGoods_"+t.order_id+"_"+s},[a("td",[a("GoodsItem",{attrs:{data:{image:r.goods_image,imageAlt:"商品图片",title:r.goods_name,goodsProps:r.goods_props}}})],1),a("td",[a("p",[e._v("￥"+e._s(r.goods_price))]),a("p",[e._v("×"+e._s(r.total_num))])]),0===s?[a("td",{attrs:{rowspan:t.goods.length}},[a("p",[e._v("￥"+e._s(t.pay_price))])]),a("td",{attrs:{rowspan:t.goods.length}},[a("UserItem",{attrs:{user:t.user}})],1),a("td",{attrs:{rowspan:t.goods.length}},[a("a-tag",[e._v(e._s(e.PayTypeEnum[t.pay_type].name))])],1),a("td",{attrs:{rowspan:t.goods.length}},[a("p",{staticClass:"mtb-2"},[a("span",{staticClass:"f-13"},[e._v("付款状态：")]),a("a-tag",{attrs:{color:t.pay_status==e.PayStatusEnum.SUCCESS.value?"green":""}},[e._v(e._s(e.PayStatusEnum[t.pay_status].name))])],1),a("p",{staticClass:"mtb-2"},[a("span",{staticClass:"f-13"},[e._v("订单类型：")]),a("a-tag",{attrs:{color:0==t.is_box?"green":""}},[e._v(e._s(0==t.is_box?"普通藏品":"盲盒获取"))])],1)]),a("td",{attrs:{rowspan:t.goods.length}},[a("div",{staticClass:"actions"},[t.order_status==e.OrderStatusEnum.APPLY_CANCEL.value?a("a",{directives:[{name:"action",rawName:"v-action:cancel",arg:"cancel"}],on:{click:function(a){return e.handleCancel(t)}}},[e._v("审核取消")]):e._e(),a("a",{directives:[{name:"action",rawName:"v-action:delete",arg:"delete"}],on:{click:function(a){return e.handleDelete(t)}}},[e._v("删除")])])])]:e._e()],2)}))]}))],2)])]),e.orderList.data.length?e._e():a("a-empty",{attrs:{image:e.simpleImage}})],1)]),e.orderList.data.length?a("div",{staticClass:"pagination"},[a("a-pagination",{attrs:{current:e.page,pageSize:e.orderList.per_page,total:e.orderList.total},on:{change:e.onChangePage}})],1):e._e(),a("DeliveryForm",{ref:"DeliveryForm",on:{handleSubmit:e.handleRefresh}}),a("CancelForm",{ref:"CancelForm",on:{handleSubmit:e.handleRefresh}})],1)],1)},s=[],i=(a("d3b7"),a("ac1f"),a("1276"),a("ade3")),n=a("5530"),o=(a("06f4"),a("fc25")),l=a("ca00"),d=a("3a10"),c=a("884a"),u=a("8d5f"),m=a("ab09"),p=a("4a95"),v=a("ac82"),_=[{title:"藏品信息",align:"center",dataIndex:"goods",scopedSlots:{customRender:"goods"}},{title:"单价/数量",align:"center",scopedSlots:{customRender:"unit_price"}},{title:"实付款",align:"center",dataIndex:"total_price",scopedSlots:{customRender:"total_price"}},{title:"买家",dataIndex:"user",scopedSlots:{customRender:"user"}},{title:"支付方式",dataIndex:"pay_type",scopedSlots:{customRender:"pay_type"}},{title:"交易状态",dataIndex:"status",scopedSlots:{customRender:"status"}},{title:"操作",dataIndex:"action",width:"180px",scopedSlots:{customRender:"action"}}],f=[{name:"订单号",value:10},{name:"会员昵称",value:20},{name:"会员ID",value:30}],h={name:"Index",components:{PlatformIcon:u["a"],GoodsItem:m["a"],UserItem:m["c"],DeliveryForm:v["b"],CancelForm:v["a"]},data:function(){return{dataType:this.getDataType(),searchForm:this.$form.createForm(this),queryParam:{},isLoading:!1,columns:_,page:1,orderList:{data:[],total:0,per_page:10}}},beforeCreate:function(){Object(l["a"])(this,{inArray:l["d"],DeliveryStatusEnum:p["a"],DeliveryTypeEnum:p["b"],OrderSourceEnum:p["c"],OrderStatusEnum:p["d"],PayStatusEnum:p["e"],PayTypeEnum:p["f"],ReceiptStatusEnum:p["g"],SearchTypeEnum:f,simpleImage:o["a"].PRESENTED_IMAGE_SIMPLE})},created:function(){this.init()},watch:{$route:function(){this.init()}},methods:{init:function(){this.dataType=this.getDataType(),this.searchForm.resetFields(),this.queryParam={},this.handleRefresh(!0)},getDataType:function(){return this.$route.path.split("/")[3]},getList:function(){var e=this,t=this.dataType,a=this.queryParam,r=this.page;return this.isLoading=!0,d["b"](Object(n["a"])(Object(n["a"])({dataType:t},a),{},{page:r})).then((function(t){e.orderList=t.data.list})).finally((function(){e.isLoading=!1}))},renderOrderStatusColor:function(e){var t,a=this.OrderStatusEnum,r=(t={},Object(i["a"])(t,a.NORMAL.value,""),Object(i["a"])(t,a.CANCELLED.value,"red"),Object(i["a"])(t,a.APPLY_CANCEL.value,"red"),Object(i["a"])(t,a.COMPLETED.value,"green"),t);return r[e]},handleRefresh:function(){var e=arguments.length>0&&void 0!==arguments[0]&&arguments[0];e&&(this.page=1),this.getList()},handleSearch:function(e){var t=this;e.preventDefault(),this.searchForm.validateFields((function(e,a){e||(t.queryParam=Object(n["a"])(Object(n["a"])({},t.queryParam),a),t.handleRefresh(!0))}))},handleReset:function(){this.searchForm.resetFields()},onChangePage:function(e){this.page=e,this.handleRefresh()},handleDelete:function(e){var t=this,a=t.$confirm({title:"您确定要删除该订单记录吗?",content:"删除后不可恢复，请谨慎操作",onOk:function(){return c["b"](e.order_id).then((function(e){t.$message.success(e.message,1.5),t.handleRefresh()})).finally((function(e){a.destroy()}))}})},handleDelivery:function(e){this.$refs.DeliveryForm.show(e)},handleCancel:function(e){this.$refs.CancelForm.show(e)}}},b=h,g=(a("5e82"),a("2877")),y=Object(g["a"])(b,r,s,!1,null,"6b4117f8",null);t["default"]=y.exports}}]);