(window.webpackJsonp=window.webpackJsonp||[]).push([[19],{195:function(t,A,e){"use strict";e.d(A,"a",(function(){return n})),e.d(A,"i",(function(){return i})),e.d(A,"h",(function(){return s})),e.d(A,"e",(function(){return r})),e.d(A,"c",(function(){return o})),e.d(A,"b",(function(){return l})),e.d(A,"d",(function(){return c})),e.d(A,"f",(function(){return u})),e.d(A,"g",(function(){return d}));var n="#1989fa",i="#fff",s="#969799",a="van-hairline",r=a+"--top",o=a+"--left",l=a+"--bottom",c=a+"--surround",u=a+"--top-bottom",d=a+"-unset--top-bottom"},196:function(t,A,e){"use strict";function n(t,A){var e=A.to,n=A.url,i=A.replace;if(e&&t){var s=t[i?"replace":"push"](e);s&&s.catch&&s.catch((function(t){if(t&&"NavigationDuplicated"!==t.name)throw t}))}else n&&(i?location.replace(n):location.href=n)}function i(t){n(t.parent&&t.parent.$router,t.props)}e.d(A,"b",(function(){return n})),e.d(A,"a",(function(){return i})),e.d(A,"c",(function(){return s}));var s={url:String,replace:Boolean,to:[String,Object]}},197:function(t,A,e){"use strict";e(167),e(202)},198:function(t,A,e){"use strict";var n=e(9),i=e(201),s=e(20),a=e(19),r=e(18),o=Object(n.a)("list"),l=o[0],c=o[1],u=o[2];A.a=l({mixins:[Object(s.a)((function(t){this.scroller||(this.scroller=Object(a.c)(this.$el)),t(this.scroller,"scroll",this.check)}))],model:{prop:"loading"},props:{error:Boolean,loading:Boolean,finished:Boolean,errorText:String,loadingText:String,finishedText:String,immediateCheck:{type:Boolean,default:!0},offset:{type:Number,default:300},direction:{type:String,default:"down"}},data:function(){return{innerLoading:this.loading}},mounted:function(){this.immediateCheck&&this.check()},watch:{finished:"check",loading:function(t){this.innerLoading=t,this.check()}},methods:{check:function(){var t=this;this.$nextTick((function(){if(!(t.innerLoading||t.finished||t.error)){var A,e=t.$el,n=t.scroller,s=t.offset,a=t.direction;if(!((A=n.getBoundingClientRect?n.getBoundingClientRect():{top:0,bottom:n.innerHeight}).bottom-A.top)||Object(i.a)(e))return!1;var r=t.$refs.placeholder.getBoundingClientRect();("up"===a?r.top-A.top<=s:r.bottom-A.bottom<=s)&&(t.innerLoading=!0,t.$emit("input",!0),t.$emit("load"))}}))},clickErrorText:function(){this.$emit("update:error",!1),this.check()},genLoading:function(){var t=this.$createElement;if(this.innerLoading)return t("div",{class:c("loading"),key:"loading"},[this.slots("loading")||t(r.a,{attrs:{size:"16"}},[this.loadingText||u("loading")])])},genFinishedText:function(){var t=this.$createElement;if(this.finished&&this.finishedText)return t("div",{class:c("finished-text")},[this.finishedText])},genErrorText:function(){var t=this.$createElement;if(this.error&&this.errorText)return t("div",{on:{click:this.clickErrorText},class:c("error-text")},[this.errorText])}},render:function(){var t=arguments[0],A=t("div",{ref:"placeholder",class:c("placeholder")});return t("div",{class:c(),attrs:{role:"feed","aria-busy":this.innerLoading}},["down"===this.direction?this.slots():A,this.genLoading(),this.genFinishedText(),this.genErrorText(),"up"===this.direction?this.slots():A])}})},199:function(t,A,e){"use strict";var n=e(3),i=e(7),s=e.n(i),a=e(9),r=e(8),o=e(195),l=e(196),c=e(17),u=e(18),d=Object(a.a)("button"),m=d[0],h=d[1];function p(t,A,e,n){var i,a=A.tag,d=A.icon,m=A.type,p=A.color,f=A.plain,b=A.disabled,v=A.loading,g=A.hairline,C=A.loadingText,y={};p&&(y.color=f?p:o.i,f||(y.background=p),-1!==p.indexOf("gradient")?y.border=0:y.borderColor=p);var x,k,_=[h([m,A.size,{plain:f,disabled:b,hairline:g,block:A.block,round:A.round,square:A.square}]),(i={},i[o.d]=g,i)];return t(a,s()([{style:y,class:_,attrs:{type:A.nativeType,disabled:b},on:{click:function(t){v||b||(Object(r.a)(n,"click",t),Object(l.a)(n))},touchstart:function(t){Object(r.a)(n,"touchstart",t)}}},Object(r.b)(n)]),[(k=[],v?k.push(t(u.a,{class:h("loading"),attrs:{size:A.loadingSize,type:A.loadingType,color:"currentColor"}})):d&&k.push(t(c.a,{attrs:{name:d},class:h("icon")})),(x=v?C:e.default?e.default():A.text)&&k.push(t("span",{class:h("text")},[x])),k)])}p.props=Object(n.a)({},l.c,{text:String,icon:String,color:String,block:Boolean,plain:Boolean,round:Boolean,square:Boolean,loading:Boolean,hairline:Boolean,disabled:Boolean,nativeType:String,loadingText:String,loadingType:String,tag:{type:String,default:"button"},type:{type:String,default:"default"},size:{type:String,default:"normal"},loadingSize:{type:String,default:"20px"}}),A.a=m(p)},201:function(t,A,e){"use strict";function n(t){return"none"===window.getComputedStyle(t).display||null===t.offsetParent}e.d(A,"a",(function(){return n}))},202:function(t,A,e){},204:function(t,A,e){"use strict";e(167),e(168)},205:function(t,A,e){},206:function(t,A,e){},210:function(t,A,e){"use strict";e(167),e(169),e(168),e(212)},211:function(t,A,e){"use strict";var n,i=e(3),s=e(2),a=e.n(s),r=e(9),o=e(14),l=e(195),c=e(21),u=e(199),d=Object(r.a)("dialog"),m=d[0],h=d[1],p=d[2],f=m({mixins:[c.a],props:{title:String,width:[Number,String],message:String,className:null,callback:Function,beforeClose:Function,messageAlign:String,cancelButtonText:String,cancelButtonColor:String,confirmButtonText:String,confirmButtonColor:String,showCancelButton:Boolean,transition:{type:String,default:"van-dialog-bounce"},showConfirmButton:{type:Boolean,default:!0},overlay:{type:Boolean,default:!0},closeOnClickOverlay:{type:Boolean,default:!1}},data:function(){return{loading:{confirm:!1,cancel:!1}}},methods:{onClickOverlay:function(){this.handleAction("overlay")},handleAction:function(t){var A=this;this.$emit(t),this.beforeClose?(this.loading[t]=!0,this.beforeClose(t,(function(e){!1!==e&&A.loading[t]&&A.onClose(t),A.loading.confirm=!1,A.loading.cancel=!1}))):this.onClose(t)},onClose:function(t){this.close(),this.callback&&this.callback(t)},onOpened:function(){this.$emit("opened")},onClosed:function(){this.$emit("closed")}},render:function(){var t,A,e=this,n=arguments[0];if(this.shouldRender){var i=this.message,s=this.messageAlign,a=this.slots(),r=this.slots("title")||this.title,c=r&&n("div",{class:h("header",{isolated:!i&&!a})},[r]),d=(a||i)&&n("div",{class:h("content")},[a||n("div",{domProps:{innerHTML:i},class:h("message",(t={"has-title":r},t[s]=s,t))})]),m=this.showCancelButton&&this.showConfirmButton,f=n("div",{class:[l.e,h("footer",{buttons:m})]},[this.showCancelButton&&n(u.a,{attrs:{size:"large",loading:this.loading.cancel,text:this.cancelButtonText||p("cancel")},class:h("cancel"),style:{color:this.cancelButtonColor},on:{click:function(){e.handleAction("cancel")}}}),this.showConfirmButton&&n(u.a,{attrs:{size:"large",loading:this.loading.confirm,text:this.confirmButtonText||p("confirm")},class:[h("confirm"),(A={},A[l.c]=m,A)],style:{color:this.confirmButtonColor},on:{click:function(){e.handleAction("confirm")}}})]);return n("transition",{attrs:{name:this.transition},on:{afterEnter:this.onOpened,afterLeave:this.onClosed}},[n("div",{directives:[{name:"show",value:this.value}],attrs:{role:"dialog","aria-labelledby":this.title||i},class:[h(),this.className],style:{width:Object(o.a)(this.width)}},[c,d,f])])}}}),b=e(1);function v(t){return b.d?Promise.resolve():new Promise((function(A,e){var s;n&&(s=n.$el,document.body.contains(s))||(n&&n.$destroy(),(n=new(a.a.extend(f))({el:document.createElement("div"),propsData:{lazyRender:!1}})).$on("input",(function(t){n.value=t}))),Object(i.a)(n,v.currentOptions,t,{resolve:A,reject:e})}))}v.defaultOptions={value:!0,title:"",width:"",message:"",overlay:!0,className:"",lockScroll:!0,transition:"van-dialog-bounce",beforeClose:null,overlayClass:"",overlayStyle:null,messageAlign:"",getContainer:"body",cancelButtonText:"",cancelButtonColor:null,confirmButtonText:"",confirmButtonColor:null,showConfirmButton:!0,showCancelButton:!1,closeOnPopstate:!1,closeOnClickOverlay:!1,callback:function(t){n["confirm"===t?"resolve":"reject"](t)}},v.alert=v,v.confirm=function(t){return v(Object(i.a)({showCancelButton:!0},t))},v.close=function(){n&&(n.value=!1)},v.setDefaultOptions=function(t){Object(i.a)(v.currentOptions,t)},v.resetDefaultOptions=function(){v.currentOptions=Object(i.a)({},v.defaultOptions)},v.resetDefaultOptions(),v.install=function(){a.a.use(f)},v.Component=f,a.a.prototype.$dialog=v;A.a=v},212:function(t,A,e){},218:function(t,A,e){"use strict";var n=function(){var t=this,A=t.$createElement,e=t._self._c||A;return e("div",{staticClass:"order-list-item"},[1==t.dataset.length?t._l(t.dataset,(function(A,n){return e("order-item-base",{key:n,staticClass:"el-mt-15",attrs:{status:A.status,title:A.title,time:"shop"==t.type?A.time:"",weight:A.weight?A.weight:"",number:A.number?A.number:"",unit:A.unit?A.unit:"",price:A.price,isbaozhi:A.isbaozhi}})})):[t._l(t.dataset,(function(A,n){return[0==n&&A.weight?e("order-item-base",{key:n,class:{"el-mt-10":!0,"oli-item":t.stylePlan},attrs:{title:A.title,time:"shop"==t.type?A.time:"",price:A.price,status:A.status,weight:A.weight?A.weight:"",number:A.number?A.number:"",isbaozhi:A.isbaozhi,unit:A.unit?A.unit:""}}):e("order-item-base",{key:n,class:{"el-mt-15":!0,"oli-item":t.stylePlan},attrs:{title:A.title,price:A.price,status:A.status,weight:A.weight?A.weight:"",number:A.number?A.number:"",isbaozhi:A.isbaozhi,unit:A.unit?A.unit:""}})]}))]],2)};n._withStripped=!0;var i={components:{OrderItemBase:e(221).a},props:["ibase","dataset","type"],computed:{stylePlan:function(){return!!this.ibase.style}}},s=(e(224),e(16)),a=Object(s.a)(i,n,[],!1,null,"0f864609",null);a.options.__file="src/components/currency/order-item/normal-list-base.vue";A.a=a.exports},221:function(t,A,e){"use strict";var n=function(){var t=this,A=t.$createElement,e=t._self._c||A;return e("dl",{staticClass:"order-item-base"},[e("dt",[e("p",{staticClass:"title"},[t._v(t._s(t.title))]),t._v(" "),t.time?e("p",{staticClass:"time"},[t._v(t._s(t.timeFomate))]):t._e()]),t._v(" "),e("dd",[t.unit?e("p",[t._v(t._s(t.unit)+"："+t._s(t.number))]):t.weight?e("p",[t._v("重量："+t._s(t.weight)+"kg")]):t.number?e("p",[t._v("数量："+t._s(t.number)+"个")]):t._e(),t._v(" "),t.price?e("p",{staticClass:"price"},[1==t.status?e("span",[t._v("预估报价")]):4==t.status?e("span",[t._v("回收报价")]):t._e(),t._v(" "),2!=t.status?e("span",[t._v("￥"+t._s(t.price))]):t._e(),t._v(" "),1==t.isbaozhi&&2!=t.status?[e("br"),e("span",{staticClass:"price-protect"},[t._v("(价格保护已开启)")])]:t._e()],2):t._e()])])};n._withStripped=!0;var i={props:["title","unit","time","weight","number","price","status","isbaozhi"],computed:{timeFomate:function(){return this.$moment(this.time).format("YYYY-MM-DD")}}},s=(e(223),e(16)),a=Object(s.a)(i,n,[],!1,null,"3c466ea0",null);a.options.__file="src/components/currency/order-item/base.vue";A.a=a.exports},223:function(t,A,e){"use strict";var n=e(205);e.n(n).a},224:function(t,A,e){"use strict";var n=e(206);e.n(n).a},225:function(t,A,e){},231:function(t,A){t.exports="data:image/png;base64,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"},243:function(t,A,e){"use strict";var n=function(){var t=this,A=t.$createElement,n=t._self._c||A;return n("div",{staticClass:"order-list-container"},[n("div",{staticClass:"olc-head"},[n("span",[t._v("订单编号："+t._s(t.ibase.number))]),t._v(" "),n("span",[t._v(t._s(t.buildStateText))])]),t._v(" "),t.ibase.name?n("div",{staticClass:"olc-shop"},[n("div",{staticClass:"olc-shopname"},[n("img",{attrs:{src:e(231)}}),t._v(" "),n("span",[t._v(t._s(t.ibase.name))])]),t._v(" "),n("div",{staticClass:"olc-time"},[t._v(t._s(t.ibase.time))])]):t._e(),t._v(" "),n("order-list-item",{attrs:{ibase:t.ibase,dataset:t.dataset,type:"salesman"}}),t._v(" "),n("div",{staticClass:"olc-price",class:6==this.$store.state.options.user.oda.groupid?"paddingBottom":""},[n("p",[t._v("订单合计："),n("span",[t._v("￥")]),n("span",[t._v(t._s(parseFloat(t.ibase.amount).toFixed(2)))])]),t._v(" "),t.ibase.protect?n("p",{staticClass:"tip-footer"},[t._v("(价格保护已开启)")]):t._e()]),t._v(" "),"unbutton"!=t.mode&&6!=this.$store.state.options.user.oda.groupid?n("div",{class:{"olc-footer":!t.stylePlan,"olc-footer-qun":t.stylePlan}},[n("van-button",{staticClass:"normal-diy-button",attrs:{slot:"button",size:"small",type:"primary",round:""},on:{click:t.eventClickButtonOperate},slot:"button"},[t._v(t._s(t.buildHostButtonText))]),t._v(" "),"salesman-confirm"==t.textType?n("van-button",{staticClass:"order-assist-button",attrs:{slot:"button",size:"small",type:"primary",round:""},on:{click:t.eventClickButtonOperateDetail},slot:"button"},[t._v("取消订单")]):t._e()],1):t._e()],1)};n._withStripped=!0;e(204);var i,s=e(199),a=e(218);function r(t,A,e){return A in t?Object.defineProperty(t,A,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[A]=e,t}var o={components:(i={},r(i,s.a.name,s.a),r(i,"OrderListItem",a.a),i),props:["textType","ibase","dataset","mode","index"],computed:{buildStateText:function(){var t="";switch(this.textType){case"collect":t="待收取";break;case"confirm":case"salesman-confirm":case"zhuguan-confirm":t="待确认";break;case"complete":t="已完成";break;case"cancel":t="已取消";break;case"stockOut":t=1==this.ibase.status?"待确认":"已出库";break;case"stockEntry":t="待确认"}return t},buildHostButtonText:function(){return this.textType,"查看详情"},stylePlan:function(){return!!this.ibase.style}},methods:{eventClickButtonOperate:function(){switch(this.textType){case"collect":this.$router.push({path:"/salesman/collect-change",query:{ordernumber:this.ibase.number}});break;case"complete":case"confirm":case"handin":this.$router.push({path:"/salesman/collect-details",query:{ordernumber:this.ibase.number,barState:1}});break;case"cancel":case"salesman-confirm":case"wait-confirm":this.$router.push({path:"/salesman/collect-details",query:{ordernumber:this.ibase.number,barState:0}});break;case"zhuguan-confirm":this.$router.push({path:"/salesman/collect-details",query:{ordernumber:this.ibase.number,barState:0,mode:"zg"}})}},eventClickButtonOperateDetail:function(){var t=this;this.ibase.number;this.$util.postRequestInterface("/api/home/order/cancel",param,(function(A){t.$emit("onCancelOrder")}))}}},l=(e(251),e(16)),c=Object(l.a)(o,n,[],!1,null,"e36cccf4",null);c.options.__file="src/components/currency/order-item/normal-list-salesman.vue";A.a=c.exports},251:function(t,A,e){"use strict";var n=e(225);e.n(n).a},376:function(t,A,e){},512:function(t,A,e){"use strict";var n=e(376);e.n(n).a},578:function(t,A,e){"use strict";e.r(A);var n=function(){var t=this,A=t.$createElement,e=t._self._c||A;return e("div",{staticClass:"order-list-items fix-order-listing"},[t._l(t.$route.params.items,(function(t,A){return e("normal-order-list",{key:A,staticClass:"el-item",attrs:{ibase:t.base,dataset:t.items,index:A,mode:"unbutton"}})})),t._v(" "),e("div",{staticClass:"page-bottom-bar"},[e("div",{staticClass:"flex-item tip-message"},[t._v("预估报价："),e("span",[t._v("￥")]),e("span",[t._v(t._s(t.orderPrice))])]),t._v(" "),e("div",{staticClass:"flex-item"},[e("span",{staticClass:"bar-button bar-button-green",on:{click:t.eventClickSubmitHandin}},[t._v("确认上交")])])])],2)};n._withStripped=!0;e(210);var i,s=e(211),a=(e(197),e(198)),r=e(243);function o(t,A,e){return A in t?Object.defineProperty(t,A,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[A]=e,t}var l={name:"SalesmanOrderHandInConfirm",components:(i={},o(i,a.a.name,a.a),o(i,"NormalOrderList",r.a),i),data:function(){return{orderPrice:0}},mounted:function(){this.$route.params.items||this.$router.push("/salesman/order-listing?index=0");var t=0,A=!0,e=!1,n=void 0;try{for(var i,s=this.$route.params.items[Symbol.iterator]();!(A=(i=s.next()).done);A=!0){var a=i.value;t+=parseFloat(a.base.amount)}}catch(t){e=!0,n=t}finally{try{!A&&s.return&&s.return()}finally{if(e)throw n}}this.orderPrice=t.toFixed(2)},methods:{buildConfirmOrderDataset:function(){var t=[],A=!0,e=!1,n=void 0;try{for(var i,s=this.$route.params.items[Symbol.iterator]();!(A=(i=s.next()).done);A=!0){var a=i.value;t.push({id:a.base.id})}}catch(t){e=!0,n=t}finally{try{!A&&s.return&&s.return()}finally{if(e)throw n}}return t},eventClickSubmitHandin:function(){var t=this,A={garbagelist:JSON.stringify(this.buildConfirmOrderDataset()),token:this.$store.state.options.user.token};this.$util.postRequestInterface("/api/home/order/shopadd",A,(function(A){s.a.alert({title:"提示",message:"订单已提交"}).then((function(){t.$router.push({path:"/salesman/order-listing",query:{index:0}})}))}))}}},c=(e(512),e(16)),u=Object(c.a)(l,n,[],!1,null,"58ab0e60",null);u.options.__file="src/views/salesman/order-hand-in-confirm.vue";A.default=u.exports}}]);