(window.webpackJsonp=window.webpackJsonp||[]).push([[87],{377:function(e,t,a){},513:function(e,t,a){"use strict";var n=a(377);a.n(n).a},579:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("van-tabs",{staticClass:"order-list-items fix-order-listing",attrs:{color:"#00CC00"},model:{value:e.tabs.index,callback:function(t){e.$set(e.tabs,"index",t)},expression:"tabs.index"}},[a("van-tab",{attrs:{title:"待上交"}},[a("van-list",{attrs:{finished:e.handin.finished,"finished-text":"没有更多了"},on:{load:function(t){return e.onLoad(e.handin.textType)}},model:{value:e.handin.loading,callback:function(t){e.$set(e.handin,"loading",t)},expression:"handin.loading"}},e._l(e.handin.dataset,(function(t,n){return a("normal-order-hanin-list",{key:n,staticClass:"el-item",attrs:{ibase:t.base,dataset:t.items,index:n,allSelect:e.handin.allSelectState},on:{onSelectOperate:e.eventOnItemSelectOperate}})})),1),e._v(" "),a("div",{staticClass:"page-bottom-bar"},[a("div",{staticClass:"flex-item check-all-select"},[a("van-checkbox",{model:{value:e.handin.allSelectState,callback:function(t){e.$set(e.handin,"allSelectState",t)},expression:"handin.allSelectState"}},[e._v("全选")])],1),e._v(" "),a("div",{staticClass:"flex-item tip-message"},[e._v("预估报价："),a("span",[e._v("￥")]),a("span",[e._v(e._s(e.handin.orderPrice))])]),e._v(" "),a("div",{staticClass:"flex-item"},[a("span",{staticClass:"bar-button bar-button-green",on:{click:e.eventClickSubmitHandin}},[e._v("去上交")])])])],1),e._v(" "),a("van-tab",{attrs:{title:"待确认"}},[a("van-list",{attrs:{finished:e.confirm.finished,"finished-text":"没有更多了"},on:{load:function(t){return e.onLoad(e.confirm.textType)}},model:{value:e.confirm.loading,callback:function(t){e.$set(e.confirm,"loading",t)},expression:"confirm.loading"}},e._l(e.confirm.dataset,(function(t,n){return a("normal-order-list",{key:n,staticClass:"el-item",attrs:{ibase:t.base,dataset:t.items,textType:e.confirm.textType,index:n},on:{onCancelOrder:e.eventOnCancelOrderItem}})})),1)],1),e._v(" "),a("van-tab",{attrs:{title:"已上交"}},[a("van-list",{attrs:{finished:e.complete.finished,"finished-text":"没有更多了"},on:{load:function(t){return e.onLoad(e.complete.textType)}},model:{value:e.complete.loading,callback:function(t){e.$set(e.complete,"loading",t)},expression:"complete.loading"}},e._l(e.complete.dataset,(function(t,n){return a("normal-order-list",{key:n,staticClass:"el-item",attrs:{ibase:t.base,dataset:t.items,textType:e.complete.textType}})})),1)],1),e._v(" "),a("van-tab",{attrs:{title:"已取消"}},[a("van-list",{attrs:{finished:e.cancel.finished,"finished-text":"没有更多了"},on:{load:function(t){return e.onLoad(e.cancel.textType)}},model:{value:e.cancel.loading,callback:function(t){e.$set(e.cancel,"loading",t)},expression:"cancel.loading"}},e._l(e.cancel.dataset,(function(t,n){return a("normal-order-list",{key:n,staticClass:"el-item",attrs:{ibase:t.base,dataset:t.items,textType:e.cancel.textType}})})),1)],1)],1)};n._withStripped=!0;a(241);var i,s=a(242),r=(a(197),a(198)),l=(a(246),a(249)),o=(a(247),a(248)),c=a(243),d=a(400);function m(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}var u={name:"TemOrderListing",components:(i={},m(i,o.a.name,o.a),m(i,l.a.name,l.a),m(i,r.a.name,r.a),m(i,s.a.name,s.a),m(i,"NormalOrderList",c.a),m(i,"NormalOrderHaninList",d.a),i),data:function(){return{tabs:{index:0},handin:{allSelectState:!1,selectItems:[],orderPrice:0,page:0,list_rows:10,loading:!1,finished:!1,textType:"handin",dataset:[]},confirm:{page:0,list_rows:10,loading:!1,finished:!1,textType:"zhuguan-confirm",dataset:[]},complete:{page:0,list_rows:10,loading:!1,finished:!1,textType:"complete",dataset:[]},cancel:{page:0,list_rows:10,loading:!1,finished:!1,textType:"cancel",dataset:[]}}},mounted:function(){this.$route.query.index&&(this.tabs.index=this.$route.query.index)},methods:{getOrderList:function(e){var t=this,a="",n={page:e.page,list_rows:e.list_rows,token:t.$store.state.options.user.token};switch(e.textType){case"zhuguan-confirm":a=1,n.otype=1;break;case"complete":a=2,n.otype=1;break;case"cancel":a=3,n.otype=1;break;default:a=6}4==this.$store.state.options.user.oda.groupid&&(n.outorder=""),a&&(n.status=a),"handin"==e.textType&&(n.is_shangjiao=0);var i=[];this.$util.postRequestInterface("/api/home/order/getlist",n,(function(a){if(e.page=e.page+1,""==a.data)e.loading=!1,e.finished=!0;else{var n=!0,s=!1,r=void 0;try{for(var l,o=a.data.data[Symbol.iterator]();!(n=(l=o.next()).done);n=!0){var c=l.value,d=!0,m=!1,u=void 0;try{for(var h,f=c.detail[Symbol.iterator]();!(d=(h=f.next()).done);d=!0){var p=h.value;i.push({title:p.garbagename,number:p.weighting_num,unit:p.danweiming,time:t.$moment(1e3*c.create_time).format("YYYY-MM-DD")})}}catch(e){m=!0,u=e}finally{try{!d&&f.return&&f.return()}finally{if(m)throw u}}e.dataset.push({base:{id:c.id,number:c.ordernumber,amount:c.price},items:i}),i=[]}}catch(e){s=!0,r=e}finally{try{!n&&o.return&&o.return()}finally{if(s)throw r}}e.loading=!1,e.dataset.length>=a.data.count&&(e.finished=!0)}}))},onLoad:function(e){switch(e){case"handin":this.getOrderList(this.handin);break;case"zhuguan-confirm":this.getOrderList(this.confirm);break;case"complete":this.getOrderList(this.complete);break;case"cancel":this.getOrderList(this.cancel)}},eventClickSubmitHandin:function(){this.$router.push({name:"SalesmanHandInOrderConfirm",params:{items:this.handin.selectItems}})},eventOnItemSelectOperate:function(e){if(e.select)this.handin.selectItems.push(this.handin.dataset[e.index]);else for(var t in this.handin.selectItems)if(this.handin.selectItems[t].base.number==this.handin.dataset[e.index].base.number){this.handin.selectItems.splice(t,1);break}var a=0,n=!0,i=!1,s=void 0;try{for(var r,l=this.handin.selectItems[Symbol.iterator]();!(n=(r=l.next()).done);n=!0){var o=r.value;a+=parseFloat(o.base.amount)}}catch(e){i=!0,s=e}finally{try{!n&&l.return&&l.return()}finally{if(i)throw s}}this.handin.orderPrice=a.toFixed(2)},eventOnCancelOrderItem:function(e){this.confirm.dataset.splice(e,1)}}},h=(a(513),a(16)),f=Object(h.a)(u,n,[],!1,null,"44adb4c9",null);f.options.__file="src/views/temporaryDirector/order-listing.vue";t.default=f.exports}}]);