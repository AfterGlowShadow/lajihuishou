(window.webpackJsonp=window.webpackJsonp||[]).push([[46],{220:function(t,e,n){"use strict";var i=n(9),o=n(1),s=n(21),a=n(17),r=Object(i.a)("popup"),u=r[0],c=r[1];e.a=u({mixins:[s.a],props:{round:Boolean,duration:Number,closeable:Boolean,transition:String,safeAreaInsetBottom:Boolean,closeIcon:{type:String,default:"cross"},closeIconPosition:{type:String,default:"top-right"},position:{type:String,default:"center"},overlay:{type:Boolean,default:!0},closeOnClickOverlay:{type:Boolean,default:!0}},beforeCreate:function(){var t=this,e=function(e){return function(n){return t.$emit(e,n)}};this.onClick=e("click"),this.onOpened=e("opened"),this.onClosed=e("closed")},render:function(){var t,e=arguments[0];if(this.shouldRender){var n=this.round,i=this.position,s=this.duration,r=this.transition||("center"===i?"van-fade":"van-popup-slide-"+i),u={};return Object(o.b)(s)&&(u.transitionDuration=s+"s"),e("transition",{attrs:{name:r},on:{afterEnter:this.onOpened,afterLeave:this.onClosed}},[e("div",{directives:[{name:"show",value:this.value}],style:u,class:c((t={round:n},t[i]=i,t["safe-area-inset-bottom"]=this.safeAreaInsetBottom,t)),on:{click:this.onClick}},[this.slots(),this.closeable&&e(a.a,{attrs:{role:"button",tabindex:"0",name:this.closeIcon},class:c("close-icon",this.closeIconPosition),on:{click:this.close}})])])}}})},222:function(t,e,n){"use strict";n(167),n(168),n(169)},240:function(t,e,n){"use strict";n.d(e,"a",(function(){return i}));var i={title:String,loading:Boolean,showToolbar:Boolean,cancelButtonText:String,confirmButtonText:String,allowHtml:{type:Boolean,default:!0},visibleItemCount:{type:Number,default:5},itemHeight:{type:Number,default:44},swipeDuration:{type:Number,default:1e3}}},244:function(t,e,n){"use strict";function i(t,e,n){return Math.min(Math.max(t,e),n)}n.d(e,"a",(function(){return i}))},250:function(t,e,n){"use strict";var i=n(280),o=function(){function t(t,e){for(var n=0;n<e.length;n++){var i=e[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}return function(e,n,i){return n&&t(e.prototype,n),i&&t(e,i),e}}();var s=function(){function t(e,n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.setData(n),this.setRules(e)}return o(t,[{key:"setData",value:function(t){this.data=t}},{key:"setRules",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},n=e.cover;for(var o in(void 0===n||n)&&(this.validators={}),t){var s={};s[o]=t[o],this.validators[o]=new i.a(s)}}},{key:"validate",value:function(t,e){var n=this,i=void 0,o=void 0;"function"==typeof t?(i=t,o=e):"function"==typeof e&&(i=e,o=t);var s=o;this.data&&(o?"string"==typeof o?(s={})[o]=this.data[o]:Array.isArray(o)&&(s={},o.forEach((function(t){s[t]=n.data[t]}))):s=this.data);var a=[];if(s)for(var r in s)if(this.validators[r]){var u={};u[r]=s[r],this.validators[r].validate(u,(function(t){t&&a.push(t[0])}))}i&&i(a.length>0,a)}}]),t}();e.a=function(t,e){return new s(t,e)}},254:function(t,e,n){},255:function(t,e,n){"use strict";var i=n(3),o=n(9),s=n(4),a=n(24);function r(t){return Array.isArray(t)?t.map((function(t){return r(t)})):"object"==typeof t?Object(a.a)({},t):t}var u=n(240),c=n(195),l=n(18),h=n(7),f=n.n(h),d=n(1),m=n(244),p=n(23),v=Object(o.a)("picker-column"),g=v[0],b=v[1];function y(t){return Object(d.c)(t)&&t.disabled}var x=g({mixins:[p.a],props:{valueKey:String,allowHtml:Boolean,className:String,itemHeight:Number,defaultIndex:Number,swipeDuration:Number,visibleItemCount:Number,initialOptions:{type:Array,default:function(){return[]}}},data:function(){return{offset:0,duration:0,options:r(this.initialOptions),currentIndex:this.defaultIndex}},created:function(){this.$parent.children&&this.$parent.children.push(this),this.setIndex(this.currentIndex)},destroyed:function(){var t=this.$parent.children;t&&t.splice(t.indexOf(this),1)},watch:{defaultIndex:function(){this.setIndex(this.defaultIndex)}},computed:{count:function(){return this.options.length},baseOffset:function(){return this.itemHeight*(this.visibleItemCount-1)/2}},methods:{onTouchStart:function(t){if(this.touchStart(t),this.moving){var e=function(t){var e=window.getComputedStyle(t),n=e.transform||e.webkitTransform,i=n.slice(7,n.length-1).split(", ")[5];return Number(i)}(this.$refs.wrapper);this.offset=Math.min(0,e-this.baseOffset),this.startOffset=this.offset}else this.startOffset=this.offset;this.duration=0,this.transitionEndTrigger=null,this.touchStartTime=Date.now(),this.momentumOffset=this.startOffset},onTouchMove:function(t){this.moving=!0,this.touchMove(t),"vertical"===this.direction&&Object(s.c)(t,!0),this.offset=Object(m.a)(this.startOffset+this.deltaY,-this.count*this.itemHeight,this.itemHeight);var e=Date.now();e-this.touchStartTime>300&&(this.touchStartTime=e,this.momentumOffset=this.offset)},onTouchEnd:function(){var t=this.offset-this.momentumOffset,e=Date.now()-this.touchStartTime;if(e<300&&Math.abs(t)>15)this.momentum(t,e);else{var n=this.getIndexByOffset(this.offset);this.moving=!1,this.duration=200,this.setIndex(n,!0)}},onTransitionEnd:function(){this.stopMomentum()},onClickItem:function(t){this.moving||(this.duration=200,this.setIndex(t,!0))},adjustIndex:function(t){for(var e=t=Object(m.a)(t,0,this.count);e<this.count;e++)if(!y(this.options[e]))return e;for(var n=t-1;n>=0;n--)if(!y(this.options[n]))return n},getOptionText:function(t){return Object(d.c)(t)&&this.valueKey in t?t[this.valueKey]:t},setIndex:function(t,e){var n=this;t=this.adjustIndex(t)||0,this.offset=-t*this.itemHeight;var i=function(){t!==n.currentIndex&&(n.currentIndex=t,e&&n.$emit("change",t))};this.moving?this.transitionEndTrigger=i:i()},setValue:function(t){for(var e=this.options,n=0;n<e.length;n++)if(this.getOptionText(e[n])===t)return this.setIndex(n)},getValue:function(){return this.options[this.currentIndex]},getIndexByOffset:function(t){return Object(m.a)(Math.round(-t/this.itemHeight),0,this.count-1)},momentum:function(t,e){var n=Math.abs(t/e);t=this.offset+n/.002*(t<0?-1:1);var i=this.getIndexByOffset(t);this.duration=this.swipeDuration,this.setIndex(i,!0)},stopMomentum:function(){this.moving=!1,this.duration=0,this.transitionEndTrigger&&(this.transitionEndTrigger(),this.transitionEndTrigger=null)},genOptions:function(){var t=this,e=this.$createElement,n={height:this.itemHeight+"px"};return this.options.map((function(i,o){var s=t.getOptionText(i),a=y(i),r={style:n,attrs:{role:"button",tabindex:a?-1:0},class:["van-ellipsis",b("item",{disabled:a,selected:o===t.currentIndex})],on:{click:function(){t.onClickItem(o)}}};return t.allowHtml&&(r.domProps={innerHTML:s}),e("li",f()([{},r]),[t.allowHtml?"":s])}))}},render:function(){var t=arguments[0],e={transform:"translate3d(0, "+(this.offset+this.baseOffset)+"px, 0)",transitionDuration:this.duration+"ms",transitionProperty:this.duration?"all":"none",lineHeight:this.itemHeight+"px"};return t("div",{class:[b(),this.className],on:{touchstart:this.onTouchStart,touchmove:this.onTouchMove,touchend:this.onTouchEnd,touchcancel:this.onTouchEnd}},[t("ul",{ref:"wrapper",style:e,class:b("wrapper"),on:{transitionend:this.onTransitionEnd}},[this.genOptions()])])}}),w=Object(o.a)("picker"),I=w[0],C=w[1],O=w[2];e.a=I({props:Object(i.a)({},u.a,{defaultIndex:{type:Number,default:0},columns:{type:Array,default:function(){return[]}},toolbarPosition:{type:String,default:"top"},valueKey:{type:String,default:"text"}}),data:function(){return{children:[]}},computed:{simple:function(){return this.columns.length&&!this.columns[0].values}},watch:{columns:"setColumns"},methods:{setColumns:function(){var t=this;(this.simple?[{values:this.columns}]:this.columns).forEach((function(e,n){t.setColumnValues(n,r(e.values))}))},emit:function(t){this.simple?this.$emit(t,this.getColumnValue(0),this.getColumnIndex(0)):this.$emit(t,this.getValues(),this.getIndexes())},onChange:function(t){this.simple?this.$emit("change",this,this.getColumnValue(0),this.getColumnIndex(0)):this.$emit("change",this,this.getValues(),t)},getColumn:function(t){return this.children[t]},getColumnValue:function(t){var e=this.getColumn(t);return e&&e.getValue()},setColumnValue:function(t,e){var n=this.getColumn(t);n&&n.setValue(e)},getColumnIndex:function(t){return(this.getColumn(t)||{}).currentIndex},setColumnIndex:function(t,e){var n=this.getColumn(t);n&&n.setIndex(e)},getColumnValues:function(t){return(this.children[t]||{}).options},setColumnValues:function(t,e){var n=this.children[t];n&&JSON.stringify(n.options)!==JSON.stringify(e)&&(n.options=e,n.setIndex(0))},getValues:function(){return this.children.map((function(t){return t.getValue()}))},setValues:function(t){var e=this;t.forEach((function(t,n){e.setColumnValue(n,t)}))},getIndexes:function(){return this.children.map((function(t){return t.currentIndex}))},setIndexes:function(t){var e=this;t.forEach((function(t,n){e.setColumnIndex(n,t)}))},onConfirm:function(){this.children.map((function(t){return t.stopMomentum()})),this.emit("confirm")},onCancel:function(){this.emit("cancel")}},render:function(t){var e=this,n=this.itemHeight,i=n*this.visibleItemCount,o=this.simple?[this.columns]:this.columns,a={height:n+"px"},r={height:i+"px"},u={backgroundSize:"100% "+(i-n)/2+"px"},h=this.showToolbar&&t("div",{class:[c.f,C("toolbar")]},[this.slots()||[t("button",{class:C("cancel"),on:{click:this.onCancel}},[this.cancelButtonText||O("cancel")]),this.slots("title")||this.title&&t("div",{class:["van-ellipsis",C("title")]},[this.title]),t("button",{class:C("confirm"),on:{click:this.onConfirm}},[this.confirmButtonText||O("confirm")])]]);return t("div",{class:C()},["top"===this.toolbarPosition?h:t(),this.loading?t(l.a,{class:C("loading")}):t(),this.slots("columns-top"),t("div",{class:C("columns"),style:r,on:{touchmove:s.c}},[o.map((function(n,i){return t(x,{attrs:{valueKey:e.valueKey,allowHtml:e.allowHtml,className:n.className,itemHeight:e.itemHeight,defaultIndex:n.defaultIndex||e.defaultIndex,swipeDuration:e.swipeDuration,visibleItemCount:e.visibleItemCount,initialOptions:e.simple?n:n.values},on:{change:function(){e.onChange(i)}}})})),t("div",{class:C("mask"),style:u}),t("div",{class:[c.g,C("frame")],style:a})]),this.slots("columns-bottom"),"bottom"===this.toolbarPosition?h:t()])}})},341:function(t,e,n){},483:function(t,e,n){"use strict";var i=n(341);n.n(i).a},554:function(t,e,n){"use strict";n.r(e);var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"container-full"},[t._m(0),t._v(" "),n("div",{staticClass:"normal-form-container bottom-ash-line"},[n("van-field",{attrs:{type:"tel",placeholder:"请输入手机号（登陆账号）","error-message":t.errorMsg.phone},model:{value:t.forma.phone,callback:function(e){t.$set(t.forma,"phone",e)},expression:"forma.phone"}}),t._v(" "),n("van-field",{attrs:{"error-message":t.errorMsg.password,placeholder:"请输入登录密码"},model:{value:t.forma.password,callback:function(e){t.$set(t.forma,"password",e)},expression:"forma.password"}})],1),t._v(" "),n("div",{staticClass:"normal-form-container"},[n("van-field",{staticClass:"el-mt-10",attrs:{placeholder:"请输入联系人姓名","error-message":t.errorMsg.realname},model:{value:t.forma.realname,callback:function(e){t.$set(t.forma,"realname",e)},expression:"forma.realname"}}),t._v(" "),n("van-field",{attrs:{placeholder:"请输入暂存点名称","error-message":t.errorMsg.shopName},model:{value:t.forma.shopName,callback:function(e){t.$set(t.forma,"shopName",e)},expression:"forma.shopName"}}),t._v(" "),n("van-field",{attrs:{placeholder:"点击选择暂存点所在地区（省/市/区县）","error-message":t.errorMsg.area},on:{click:function(e){t.showArea=!0}},model:{value:t.forma.area,callback:function(e){t.$set(t.forma,"area",e)},expression:"forma.area"}}),t._v(" "),n("van-field",{attrs:{"error-message":t.errorMsg.address,placeholder:"请输入暂存点所在详细地址"},model:{value:t.forma.address,callback:function(e){t.$set(t.forma,"address",e)},expression:"forma.address"}})],1),t._v(" "),n("div",{staticClass:"normal-form-button-container el-mt-30"},[n("van-button",{staticClass:"green-background font-size-17 edge-angle-normal",attrs:{type:"primary",size:"large"},on:{click:t.onClickRegeditShop}},[t._v("立即添加")])],1),t._v(" "),n("van-popup",{attrs:{position:"bottom"},model:{value:t.showArea,callback:function(e){t.showArea=e},expression:"showArea"}},[n("van-area",{attrs:{value:t.regionCode,"area-list":t.areaList},on:{confirm:t.confirmArea,cancel:t.cancelArea}})],1)],1)};i._withStripped=!0;n(22);var o,s=n(5),a=(n(222),n(220)),r=(n(279),n(282)),u=(n(204),n(199)),c=(n(207),n(209)),l=(n(213),n(214)),h=n(250),f=n(281);function d(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var m={name:"ShopIndex",components:(o={},d(o,l.a.name,l.a),d(o,c.a.name,c.a),d(o,u.a.name,u.a),d(o,r.a.name,r.a),d(o,a.a.name,a.a),o),data:function(){return{countdown:0,countdownText:"验证码",showArea:!1,areaList:f.a,province:"",city:"",area:"",regionCode:"",forma:{phone:"",password:"",realname:"",name:"",shopName:"",area:"",address:"",longitude:"",latitude:""},errorMsg:{phone:"",password:"",realname:"",name:"",shopName:"",area:"",address:""},rules:{phone:[{validator:function(t,e,n){e?/^[1][0-9]{10}$/.test(e)?n():n("请输入正确的手机号码"):n("请输入手机号码")}}],password:[{required:!0,message:"请输入登录密码"}],realname:[{required:!0,message:"请输入联系人姓名"}],shopName:[{required:!0,message:"请输入暂存点名称"}],area:[{required:!0,message:"请选择暂存点所在地区"}],address:[{required:!0,message:"请输入暂存点所在详细地址"}]}}},created:function(){this.validator=Object(h.a)(this.rules,this.forma);var t=JSON.parse(sessionStorage.getItem("userRegedit"));t&&(this.forma=t,sessionStorage.removeItem("userRegedit"))},mounted:function(){var t=this;if(this.getAddress(),this.$route.params.name){this.forma.address=this.$route.params.name;var e=String(this.$route.params.location).split(",");this.forma.longitude=e[0],this.forma.latitude=e[1],this.initializationLoadAmapSourceOperate({longitude:e[0],latitude:e[1]}).then(this.requestGetUserLocationData).then((function(e){var n=e.result.regeocode.addressComponent,i=n.city,o=n.district,s=n.province,a=n.adcode;t.forma.area=s+"-"+i+"-"+o,t.regionCode=a})).catch((function(t){console.log(t)}))}},methods:{getAddress:function(){var t=this;this.$util.postRequestInterface("/api/citylist",{},(function(e){t.areaList=e.data}))},confirmArea:function(t){this.showArea=!1,this.province=t[0].code,this.city=t[1].code,this.area=t[2].code,this.forma.area=t[0].name+"-"+t[1].name+"-"+t[2].name},cancelArea:function(){this.showArea=!1},resetField:function(t){var e=this;(t=t?Array.isArray(t)?t:[t]:Object.keys(this.errorMsg)).forEach((function(t){e.errorMsg[t]=""}))},validate:function(t,e){var n=this;this.validator.validate((function(e,i){n.resetField(),e&&i.forEach((function(t){n.errorMsg[t.field]=t.message})),t&&t(e,i)}),e)},onClickRegeditShop:function(){var t=this;this.validate((function(e,n){if(!e){var i={name:t.forma.phone,realname:t.forma.realname,phone:t.forma.phone,zhicheng:t.forma.shopName,province:t.province,city:t.city,county:t.area,address:t.forma.address,longitude:1,latitude:1,pwd:t.forma.password,openid:"shadow",region:t.province,token:t.$store.state.options.user.token};t.$util.postRequestInterface("/api/home/user/zaddzc",i,(function(e){200==e.code?(s.a.fail("添加成功"),t.$router.push("/GeneralRepository/order-shop")):s.a.fail("添加失败")}))}}))},initializationLoadAmapSourceOperate:function(t){var e=this;return new Promise((function(n,i){e.$util.loadSourceUrl({mode:"script",url:"https://webapi.amap.com/maps?v=1.4.15&key=1ee27ce137e72c3a50fdfa27cb2214f4&plugin=AMap.Geocoder",name:"gaode-map",resolve:function(){n(t)},reject:i})}))},requestGetUserLocationData:function(t){var e=this;return new Promise((function(n,i){e.geocoder||(e.geocoder=new AMap.Geocoder({radius:100,extensions:"all"})),e.geocoder.getAddress([t.longitude,t.latitude],(function(e,o){"complete"===e&&"OK"===o.info&&o.regeocode?n({res:t,result:o}):i()}))}))},eventClickMapSelectAddress:function(){sessionStorage.setItem("userRegedit",JSON.stringify(this.forma)),this.$router.push("/public/map")}}},p=(n(483),n(16)),v=Object(p.a)(m,i,[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"regedit-head"},[e("h1",{staticClass:"title"},[this._v("添加暂存点")])])}],!1,null,"75a84052",null);v.options.__file="src/views/GeneralRepository/add-temp.vue";e.default=v.exports}}]);