(window.webpackJsonp=window.webpackJsonp||[]).push([[29],{205:function(A,t,e){},206:function(A,t,e){},218:function(A,t,e){"use strict";var s=function(){var A=this,t=A.$createElement,e=A._self._c||t;return e("div",{staticClass:"order-list-item"},[1==A.dataset.length?A._l(A.dataset,(function(t,s){return e("order-item-base",{key:s,staticClass:"el-mt-15",attrs:{status:t.status,title:t.title,time:"shop"==A.type?t.time:"",weight:t.weight?t.weight:"",number:t.number?t.number:"",unit:t.unit?t.unit:"",price:t.price,isbaozhi:t.isbaozhi}})})):[A._l(A.dataset,(function(t,s){return[0==s&&t.weight?e("order-item-base",{key:s,class:{"el-mt-10":!0,"oli-item":A.stylePlan},attrs:{title:t.title,time:"shop"==A.type?t.time:"",price:t.price,status:t.status,weight:t.weight?t.weight:"",number:t.number?t.number:"",isbaozhi:t.isbaozhi,unit:t.unit?t.unit:""}}):e("order-item-base",{key:s,class:{"el-mt-15":!0,"oli-item":A.stylePlan},attrs:{title:t.title,price:t.price,status:t.status,weight:t.weight?t.weight:"",number:t.number?t.number:"",isbaozhi:t.isbaozhi,unit:t.unit?t.unit:""}})]}))]],2)};s._withStripped=!0;var a={components:{OrderItemBase:e(221).a},props:["ibase","dataset","type"],computed:{stylePlan:function(){return!!this.ibase.style}}},i=(e(224),e(16)),n=Object(i.a)(a,s,[],!1,null,"0f864609",null);n.options.__file="src/components/currency/order-item/normal-list-base.vue";t.a=n.exports},221:function(A,t,e){"use strict";var s=function(){var A=this,t=A.$createElement,e=A._self._c||t;return e("dl",{staticClass:"order-item-base"},[e("dt",[e("p",{staticClass:"title"},[A._v(A._s(A.title))]),A._v(" "),A.time?e("p",{staticClass:"time"},[A._v(A._s(A.timeFomate))]):A._e()]),A._v(" "),e("dd",[A.unit?e("p",[A._v(A._s(A.unit)+"："+A._s(A.number))]):A.weight?e("p",[A._v("重量："+A._s(A.weight)+"kg")]):A.number?e("p",[A._v("数量："+A._s(A.number)+"个")]):A._e(),A._v(" "),A.price?e("p",{staticClass:"price"},[1==A.status?e("span",[A._v("预估报价")]):4==A.status?e("span",[A._v("回收报价")]):A._e(),A._v(" "),2!=A.status?e("span",[A._v("￥"+A._s(A.price))]):A._e(),A._v(" "),1==A.isbaozhi&&2!=A.status?[e("br"),e("span",{staticClass:"price-protect"},[A._v("(价格保护已开启)")])]:A._e()],2):A._e()])])};s._withStripped=!0;var a={props:["title","unit","time","weight","number","price","status","isbaozhi"],computed:{timeFomate:function(){return this.$moment(this.time).format("YYYY-MM-DD")}}},i=(e(223),e(16)),n=Object(i.a)(a,s,[],!1,null,"3c466ea0",null);n.options.__file="src/components/currency/order-item/base.vue";t.a=n.exports},223:function(A,t,e){"use strict";var s=e(205);e.n(s).a},224:function(A,t,e){"use strict";var s=e(206);e.n(s).a},225:function(A,t,e){},231:function(A,t){A.exports="data:image/png;base64,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"},243:function(A,t,e){"use strict";var s=function(){var A=this,t=A.$createElement,s=A._self._c||t;return s("div",{staticClass:"order-list-container"},[s("div",{staticClass:"olc-head"},[s("span",[A._v("订单编号："+A._s(A.ibase.number))]),A._v(" "),s("span",[A._v(A._s(A.buildStateText))])]),A._v(" "),A.ibase.name?s("div",{staticClass:"olc-shop"},[s("div",{staticClass:"olc-shopname"},[s("img",{attrs:{src:e(231)}}),A._v(" "),s("span",[A._v(A._s(A.ibase.name))])]),A._v(" "),s("div",{staticClass:"olc-time"},[A._v(A._s(A.ibase.time))])]):A._e(),A._v(" "),s("order-list-item",{attrs:{ibase:A.ibase,dataset:A.dataset,type:"salesman"}}),A._v(" "),s("div",{staticClass:"olc-price",class:6==this.$store.state.options.user.oda.groupid?"paddingBottom":""},[s("p",[A._v("订单合计："),s("span",[A._v("￥")]),s("span",[A._v(A._s(parseFloat(A.ibase.amount).toFixed(2)))])]),A._v(" "),A.ibase.protect?s("p",{staticClass:"tip-footer"},[A._v("(价格保护已开启)")]):A._e()]),A._v(" "),"unbutton"!=A.mode&&6!=this.$store.state.options.user.oda.groupid?s("div",{class:{"olc-footer":!A.stylePlan,"olc-footer-qun":A.stylePlan}},[s("van-button",{staticClass:"normal-diy-button",attrs:{slot:"button",size:"small",type:"primary",round:""},on:{click:A.eventClickButtonOperate},slot:"button"},[A._v(A._s(A.buildHostButtonText))]),A._v(" "),"salesman-confirm"==A.textType?s("van-button",{staticClass:"order-assist-button",attrs:{slot:"button",size:"small",type:"primary",round:""},on:{click:A.eventClickButtonOperateDetail},slot:"button"},[A._v("取消订单")]):A._e()],1):A._e()],1)};s._withStripped=!0;e(204);var a,i=e(199),n=e(218);function r(A,t,e){return t in A?Object.defineProperty(A,t,{value:e,enumerable:!0,configurable:!0,writable:!0}):A[t]=e,A}var o={components:(a={},r(a,i.a.name,i.a),r(a,"OrderListItem",n.a),a),props:["textType","ibase","dataset","mode","index"],computed:{buildStateText:function(){var A="";switch(this.textType){case"collect":A="待收取";break;case"confirm":case"salesman-confirm":case"zhuguan-confirm":A="待确认";break;case"complete":A="已完成";break;case"cancel":A="已取消";break;case"stockOut":A=1==this.ibase.status?"待确认":"已出库";break;case"stockEntry":A="待确认"}return A},buildHostButtonText:function(){return this.textType,"查看详情"},stylePlan:function(){return!!this.ibase.style}},methods:{eventClickButtonOperate:function(){switch(this.textType){case"collect":this.$router.push({path:"/salesman/collect-change",query:{ordernumber:this.ibase.number}});break;case"complete":case"confirm":case"handin":this.$router.push({path:"/salesman/collect-details",query:{ordernumber:this.ibase.number,barState:1}});break;case"cancel":case"salesman-confirm":case"wait-confirm":this.$router.push({path:"/salesman/collect-details",query:{ordernumber:this.ibase.number,barState:0}});break;case"zhuguan-confirm":this.$router.push({path:"/salesman/collect-details",query:{ordernumber:this.ibase.number,barState:0,mode:"zg"}})}},eventClickButtonOperateDetail:function(){var A=this;this.ibase.number;this.$util.postRequestInterface("/api/home/order/cancel",param,(function(t){A.$emit("onCancelOrder")}))}}},c=(e(251),e(16)),u=Object(c.a)(o,s,[],!1,null,"e36cccf4",null);u.options.__file="src/components/currency/order-item/normal-list-salesman.vue";t.a=u.exports},251:function(A,t,e){"use strict";var s=e(225);e.n(s).a},359:function(A,t,e){},494:function(A,t,e){"use strict";var s=e(359);e.n(s).a},547:function(A,t,e){"use strict";e.r(t);var s=function(){var A=this,t=A.$createElement,e=A._self._c||t;return e("van-tabs",{staticClass:"order-list-items",attrs:{color:"#00CC00"},model:{value:A.tabs.index,callback:function(t){A.$set(A.tabs,"index",t)},expression:"tabs.index"}},[e("van-tab",{attrs:{title:"出库申请"}},[e("van-list",{attrs:{finished:A.stockOut.finished,"finished-text":"没有更多了"},on:{load:function(t){return A.onLoad(A.stockOut.textType)}},model:{value:A.stockOut.loading,callback:function(t){A.$set(A.stockOut,"loading",t)},expression:"stockOut.loading"}},A._l(A.stockOut.dataset,(function(t,s){return e("normal-order-list",{key:s,staticClass:"el-item",attrs:{ibase:t.base,dataset:t.items,textType:A.stockOut.textType}})})),1)],1),A._v(" "),e("van-tab",{attrs:{title:"入库申请"}},[e("van-list",{attrs:{finished:A.stockEntry.finished,"finished-text":"没有更多了"},on:{load:function(t){return A.onLoad(A.stockEntry.textType)}},model:{value:A.stockEntry.loading,callback:function(t){A.$set(A.stockEntry,"loading",t)},expression:"stockEntry.loading"}},A._l(A.stockEntry.dataset,(function(t,s){return e("normal-order-list",{key:s,staticClass:"el-item",attrs:{ibase:t.base,dataset:t.items,textType:A.stockEntry.textType}})})),1)],1)],1)};s._withStripped=!0;e(197);var a,i=e(198),n=(e(246),e(249)),r=(e(247),e(248)),o=e(243);function c(A,t,e){return t in A?Object.defineProperty(A,t,{value:e,enumerable:!0,configurable:!0,writable:!0}):A[t]=e,A}var u={name:"record",components:(a={},c(a,r.a.name,r.a),c(a,n.a.name,n.a),c(a,i.a.name,i.a),c(a,"NormalOrderList",o.a),a),data:function(){return{tabs:{index:0},stockOut:{loading:!1,finished:!1,textType:"stockOut",page:0,list_rows:10,dataset:[]},stockEntry:{page:0,list_rows:10,loading:!1,finished:!1,textType:"stockEntry",dataset:[]}}},mounted:function(){this.tabs.index=this.$route.query.index},methods:{getOrderList:function(A){var t=this;switch(A.textType){case"stockOut":case"stockEntry":1}var e={page:A.page,list_rows:A.list_rows,token:t.$store.state.options.user.token};"stockOut"==A.textType?e.otype=1:e.status=1;var s=[];this.$util.postRequestInterface("/api/home/order/getlist",e,(function(e){if(A.page=A.page+1,""==e.data)A.loading=!1,A.finished=!0;else{var a=!0,i=!1,n=void 0;try{for(var r,o=e.data.data[Symbol.iterator]();!(a=(r=o.next()).done);a=!0){var c=r.value,u=!0,l=!1,m=void 0;try{for(var d,p=c.detail[Symbol.iterator]();!(u=(d=p.next()).done);u=!0){var b=d.value;s.push({title:b.garbagename,number:1==b.weighting_method?b.weighting_num:"",weight:0==b.weighting_method?b.weighting_num:"",time:t.$moment(1e3*c.create_time).format("YYYY-MM-DD HH:mm:ss")})}}catch(A){l=!0,m=A}finally{try{!u&&p.return&&p.return()}finally{if(l)throw m}}A.dataset.push({base:{status:c.status,id:c.id,number:c.ordernumber,amount:c.price,price:c.price},items:s}),s=[]}}catch(A){i=!0,n=A}finally{try{!a&&o.return&&o.return()}finally{if(i)throw n}}A.loading=!1,A.dataset.length>=e.data.count&&(A.finished=!0)}}))},onLoad:function(A){switch(A){case"stockOut":this.getOrderList(this.stockOut);break;case"stockEntry":this.getOrderList(this.stockEntry)}}}},l=(e(494),e(16)),m=Object(l.a)(u,s,[],!1,null,"1c6264e1",null);m.options.__file="src/views/accounting/record.vue";t.default=m.exports}}]);