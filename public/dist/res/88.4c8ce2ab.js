(window.webpackJsonp=window.webpackJsonp||[]).push([[88],{326:function(t,e,o){},468:function(t,e,o){"use strict";var n=o(326);o.n(n).a},577:function(t,e,o){"use strict";o.r(e);var n=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"waiting-point-map"},[o("div",{attrs:{id:"container"}}),t._v(" "),o("div",{staticClass:"address"},[o("p",{},[t._v(t._s(t.neighborhood))]),t._v(" "),o("p",[t._v(t._s(t.formattedAddress))])]),t._v(" "),o("tab-group",{attrs:{dataset:t.navButtonGroup,index:"1"}})],1)};n._withStripped=!0;var r={name:"waiting-point-map",components:{TabGroup:o(219).a},data:function(){return{navButtonGroup:[{name:"订单",route:{path:"/temporaryDirector/index"}},{name:"待取点",route:{path:"/temporaryDirector/waiting-point-map"}},{name:"查询报价",route:{path:"/temporaryDirector/enquiry-quotation"}},{name:"个人中心",route:{path:"/temporaryDirector/temporary-center"}}],neighborhood:"暂无名称",formattedAddress:"暂无详细地址，请点击门店显示",map:null,geocoder:null}},mounted:function(){this.initializationLoadAmapSourceOperate().then(this.reqeustShopWaitingPointData).then(this.settingWaitingPointOperate).catch((function(t){console.log(t)}))},methods:{initializationLoadAmapSourceOperate:function(){var t=this;return new Promise((function(e,o){t.$util.loadSourceUrl({mode:"script",url:"https://webapi.amap.com/maps?v=1.4.15&key=1ee27ce137e72c3a50fdfa27cb2214f4&plugin=AMap.Geocoder",name:"gaode-map",resolve:e,reject:o})}))},reqeustShopWaitingPointData:function(){var t=this;return new Promise((function(e,o){var n={token:t.$store.state.options.user.token};t.$util.postRequestInterface("/api/home/order/ordermap",n,{success:function(t){e(t)},fail:o})}))},settingWaitingPointOperate:function(t){if(this.map||(this.map=new AMap.Map("container",{resizeEnable:!0,zoom:15})),""!=t.data){var e=t.data,o=e[0],n=o.longitude,r=o.latitude,a=!0,i=!1,s=void 0;try{for(var c,d=e[Symbol.iterator]();!(a=(c=d.next()).done);a=!0){var p=c.value,u='<p class="map-sign-title">'+p.username+"</p>",l='<p class="map-sign-number">重量：'+p.zweight+"</p>",m='<p class="map-sign-number">数量：'+p.znumber+"</p>",g=Number(p.znumber)>0?m:l,h=new AMap.Text({text:u+g,anchor:"center",draggable:!1,cursor:"pointer",angle:0,style:{padding:"1rem","margin-bottom":"1rem","border-radius":".25rem","background-color":"white","border-width":0,"box-shadow":"0 2px 6px 0 rgba(114, 124, 245, .5)","text-align":"center","font-size":"1.3rem",color:"#333333"},position:new AMap.LngLat(p.longitude,p.latitude)});h.on("click",this.controllerSetAddresDetails),h.setMap(this.map)}}catch(t){i=!0,s=t}finally{try{!a&&d.return&&d.return()}finally{if(i)throw s}}e.length>0&&this.map.setCenter(new AMap.LngLat(n,r))}else this.$toast(t.msg)},requestGetUserLocationData:function(t){var e=this;return new Promise((function(o,n){e.geocoder||(e.geocoder=new AMap.Geocoder({radius:100,extensions:"all"})),e.geocoder.getAddress([t.longitude,t.latitude],(function(t,e){"complete"==t&&"OK"==e.info&&e.regeocode?o(e):n()}))}))},controllerSetAddresDetails:function(t){var e=this,o=t.lnglat,n=o.lat,r=o.lng;this.requestGetUserLocationData({longitude:r,latitude:n}).then((function(t){e.neighborhood=t.regeocode.addressComponent.building||t.regeocode.addressComponent.neighborhood,e.formattedAddress=t.regeocode.formattedAddress})).catch((function(t){e.$toast("获取位置信息失败")}))}}},a=(o(468),o(16)),i=Object(a.a)(r,n,[],!1,null,null,null);i.options.__file="src/views/temporaryDirector/waiting-point-map.vue";e.default=i.exports}}]);