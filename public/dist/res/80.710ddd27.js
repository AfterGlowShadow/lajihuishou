(window.webpackJsonp=window.webpackJsonp||[]).push([[80],{382:function(e,t,a){},518:function(e,t,a){"use strict";var s=a(382);a.n(s).a},593:function(e,t,a){"use strict";a.r(t);var s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"order-details"},[a("div",{staticClass:"status",class:"已取消"==e.status?"status-cancel":""},[e._v("\n            订单状态： "),a("span",[e._v("待出库")])]),e._v(" "),a("div",{staticClass:"list-base bottom-ash-line"},[a("normal-order-list-base",{attrs:{ibase:e.ibase,dataset:e.dataset}}),e._v(" "),e.dataset.length>1&&e.ibase.amount?a("div",{staticClass:"all-price"},[e._v("订单合计:"),a("span",{staticStyle:{"font-size":"1.3rem"}},[e._v("￥"),a("span",{staticStyle:{"font-size":"1.9rem"}},[e._v(e._s(e.ibase.amount))])])]):e._e()],1),e._v(" "),a("order-details-c",{attrs:{ordernumber:e.ibase.number,createtime:e.item.createtime,deposit:e.item.deposit,collectPeople:e.item.collectPeople,collectTime:e.item.collectTime,cancelTime:e.item.cancelTime,capital:e.item.capital,mode:e.item.mode,completeTime:e.item.completeTime,item:e.item}})],1)};s._withStripped=!0;a(210);var i=a(211),r=(a(204),a(199)),o=a(218),n=a(415);var m,c,u,l={name:"order-details",components:(m={NormalOrderListBase:o.a,OrderDetailsC:n.a},c=r.a.name,u=r.a,c in m?Object.defineProperty(m,c,{value:u,enumerable:!0,configurable:!0,writable:!0}):m[c]=u,m),data:function(){return{item:{},status:"",ordernumber:"",ibase:{},dataset:[]}},mounted:function(){this.ordernumber=this.$route.query.ordernumber,this.getDetailsOrder()},methods:{getDetailsOrder:function(){var e=this,t=this,a={ordernumber:this.ordernumber,token:this.$store.state.options.user.token};this.$util.postRequestInterface("/api/home/order/getone",a,(function(a){switch(a.data.status){case 1:e.status="待收取";break;case 2:e.status="待入库";break;case 3:e.status="待确认";break;case 4:t.status="待确认";break;case 5:6==t.$store.state.options.user.oda.groupid?t.status="已确认":t.status="已完成";break;case 6:5==t.$store.state.options.user.oda.groupid?t.status="已入库":t.status="已取消"}var s=a.data,i=s.detail,r=!0,o=!1,n=void 0;try{for(var m,c=i[Symbol.iterator]();!(r=(m=c.next()).done);r=!0){var u=m.value;t.dataset.push({title:u.garbagename,unit:u.danweiming,number:parseInt(u.weighting_num),time:t.$moment(1e3*s.create_time).format("YYYY-MM-DD HH:mm:ss"),price:parseFloat(u.price).toFixed(2),isbaozhi:s.isbaozhi})}}catch(e){o=!0,n=e}finally{try{!r&&c.return&&c.return()}finally{if(o)throw n}}t.ibase.number=s.ordernumber,2!=a.data.status&&(t.ibase.amount=s.price),t.ibase.time=t.$moment(1e3*s.create_time).format("YYYY-MM-DD"),t.item={id:s.id,status:s.status,createtime:t.$moment(1e3*s.create_time).format("YYYY-MM-DD HH:mm:ss"),deposit:s.zancundian,collectPeople:s.yewuyuan,collectTime:"",cancelTime:s.end_time?t.$moment(1e3*s.end_time).format("YYYY-MM-DD HH:mm:ss"):"",capital:0==s.paytype?"平台交易":"现场交易",mode:0==s.delivery_method?"上门收取":"门店自送",completeTime:s.end_time?t.$moment(1e3*s.end_time).format("YYYY-MM-DD HH:mm:ss"):"",userzhicheng:s.userzhicheng,kuaijitime:s.kuaijitime?t.$moment(1e3*s.kuaijitime).format("YYYY-MM-DD HH:mm:ss"):""}}))},eventClickCancelOrder:function(){var e=this;i.a.confirm({title:"提示",message:"是否确定要取消订单"}).then((function(){var t={ordernumber:e.ordernumber,token:e.$store.state.options.user.token};e.$util.postRequestInterface("/api/home/order/cancel",t,(function(t){200==t.code&&i.a.alert({title:"提示",message:t.msg}).then((function(){e.$router.go(-1)}))}))})).catch((function(){}))},eventClickConfirmOrder:function(){var e=this,t={ordernumber:this.ordernumber,token:this.$store.state.options.user.token};this.$util.postRequestInterface("/api/home/order/confirm",t,(function(t){200==t.code&&i.a.alert({title:"提示",message:t.msg}).then((function(){e.$router.go(-1)}))}))}}},d=(a(518),a(16)),p=Object(d.a)(l,s,[],!1,null,"2cf38bdc",null);p.options.__file="src/views/reservoirManagement/order-details.vue";t.default=p.exports}}]);