(window.webpackJsonp=window.webpackJsonp||[]).push([[52],{197:function(A,t,e){"use strict";e(167),e(202)},198:function(A,t,e){"use strict";var z=e(9),i=e(201),n=e(20),s=e(19),a=e(18),o=Object(z.a)("list"),r=o[0],g=o[1],c=o[2];t.a=r({mixins:[Object(n.a)((function(A){this.scroller||(this.scroller=Object(s.c)(this.$el)),A(this.scroller,"scroll",this.check)}))],model:{prop:"loading"},props:{error:Boolean,loading:Boolean,finished:Boolean,errorText:String,loadingText:String,finishedText:String,immediateCheck:{type:Boolean,default:!0},offset:{type:Number,default:300},direction:{type:String,default:"down"}},data:function(){return{innerLoading:this.loading}},mounted:function(){this.immediateCheck&&this.check()},watch:{finished:"check",loading:function(A){this.innerLoading=A,this.check()}},methods:{check:function(){var A=this;this.$nextTick((function(){if(!(A.innerLoading||A.finished||A.error)){var t,e=A.$el,z=A.scroller,n=A.offset,s=A.direction;if(!((t=z.getBoundingClientRect?z.getBoundingClientRect():{top:0,bottom:z.innerHeight}).bottom-t.top)||Object(i.a)(e))return!1;var a=A.$refs.placeholder.getBoundingClientRect();("up"===s?a.top-t.top<=n:a.bottom-t.bottom<=n)&&(A.innerLoading=!0,A.$emit("input",!0),A.$emit("load"))}}))},clickErrorText:function(){this.$emit("update:error",!1),this.check()},genLoading:function(){var A=this.$createElement;if(this.innerLoading)return A("div",{class:g("loading"),key:"loading"},[this.slots("loading")||A(a.a,{attrs:{size:"16"}},[this.loadingText||c("loading")])])},genFinishedText:function(){var A=this.$createElement;if(this.finished&&this.finishedText)return A("div",{class:g("finished-text")},[this.finishedText])},genErrorText:function(){var A=this.$createElement;if(this.error&&this.errorText)return A("div",{on:{click:this.clickErrorText},class:g("error-text")},[this.errorText])}},render:function(){var A=arguments[0],t=A("div",{ref:"placeholder",class:g("placeholder")});return A("div",{class:g(),attrs:{role:"feed","aria-busy":this.innerLoading}},["down"===this.direction?this.slots():t,this.genLoading(),this.genFinishedText(),this.genErrorText(),"up"===this.direction?this.slots():t])}})},201:function(A,t,e){"use strict";function z(A){return"none"===window.getComputedStyle(A).display||null===A.offsetParent}e.d(t,"a",(function(){return z}))},202:function(A,t,e){},228:function(A,t){A.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAMAAABHPGVmAAABUFBMVEVHcEwAzAAAzAAAyAAAzAAA1QAAzAAAqgAAzQAAzAAA/wAAzAAAzAAAywAAvwAAywAAyAAAzAAAzQAAzAAAzAAAzAAAzAAAzAAAzQAAzAAAywAAywAAzQAAzAAAzAAAzQAAzgAAzAAAzAAAzAAAzQAAywAAzAAAzAAAzAAAzAAAzAAAzAAAzAAAzAAAzAAAzAAAzQAAzAAAzAAAzQAAzAAAzAAAywAAzAAAywAAywAAxgAAzAAAzAAAygAAzQAAzAAAzAAAywAA0QAAzAAAzAAAzAAAzAAAzAAAzQAA/wAAzAAAzwAAzQAAywAAzAAAyQAAzAAAxAAAzAAAxgAAzQAAzAAAzQAAywAAzAAAzQAAzAAAzQAAzAAAzAAAzAAAzAAAzAAAzgAAzAAAzgAAzAAAzAAAzAAAzgAAzQAAywAAzAAAzQAAzAAAzAAAzAAAzACLEGkIAAAAb3RSTlMA/Q8OiAbiAyS4Adz0xgRsHChIaXfP0IFS5DtUrF/dejTZvppMivX20dQjg23a/oaEwsdl4fqUaqMiCa4tOo6rtbIWVezl4OqiAm4gsUCXJpwNghK7aCln8jjePfz3w+ceH7QV+L+gOXWF05PbdJL5pdehAAAC0klEQVRo3u3aV1PyQBQG4ICRbgEUwYIUKSoi9t577733r5//f/eNIxExbc/mJHrBe5fMTh4m2052EIRKKvkO8Y/sXt/4AHw317sjfjOEY9EBZXGIx8REsxsU4m4mJDbuQSX3G0SEZz0DqsmseyiMqlbQTGuVcWM+CzrJzhs16r2gG2+9McPeCQzptBsxnE/AlCenAWQfGLNvYAraWBEb97R0ZoE52VpOJAiIBPmMagcGcVRzIW2AShsX0o9D+nmMLkDGxYE0YJEGDsSNRdx4o9aHRXz4qeICML9TOvBIBxqpwyN1aKQdj7SjkV480otGflqBWPK6LOl4S4awJZPRkmXFkgXSmqUevWl18WyNNTij5vsWEpaURMIRBjniLVMb2Y1G7rp+ir3gnuIv65tYkSYjH0EJNiNh5CNIsP8y/3POmg9TQXgI6RmhBwP9UZxde3qHBXvFucvRL7nMozRkcml1Ip2TBuJjBr39Fl6fIBYv7ubUjLm7YhPx9aobZ/x+e8a29ArOlI+izqRXu/124x/GEKXHzL4PTr84WC4Miu+Hd/ZZ6abIbvwoPSoaK93eCSb7lk4BTpf6ksGd0v1YtNT+L6vR8vEHX6bGtVuPpy4/NM+3sBmTnwaTd0Hj3Myz8Gm2pieZDtH+yNfxsMoccIblu0EPyyHbqNI4ihQUKjdXIaLUdhTZIWW/cDg8tia1WhsLD/eotdTtloMVrSXkKnQ48fw8cRi60mq1cqCDJIEgSW0jlqdA8jFNJAEkSWgZASBKgK4y5apZt4AsW6rIJh2yqWbc2ugQ260K0g2EUdklPeeUyLny0v0CpHlRRKZpkWlFJE6LxIlOB/BnBwPUyIACskqNrCogUWokqlA/AHnkFcUFPXIhQ07okRMZkqJHUjJkhh6ZMW9T1Noe4/SIfGFZpEcWZcgQPTIkQ8CEfA2yTG8sy4vHCLURCVT+hVOJ+fkPd34OOSpx6hkAAAAASUVORK5CYII="},234:function(A,t){A.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAMAAABg3Am1AAABgFBMVEVHcEwAzAAAzAAAywAAzAAAzQAAzQAAzAAAzAAAzgAAzAAAzAAAzAAAzAAAygAAzAAAzAAA0gAAywAAzAAAzwAAygAA0QAAywAAzAAAyAAAzAAAzAAAzAAAzQAAzAAAywAAzAAAzQAAzQAAywAAywAAzAAAzAAAzAAAywAAygAAzAAAzAAAyQAAzAAAzAAAzAAAzAAAzQAAzAAAzAAAzAAAzgAAzQAAzAAAzAAAywAAzAAAzAAAzAAAzQAAzgAAywAAzAAAzQAAzAAAzAAAywAAzQAAzAAAzAAAzQAAzAAAywAAxgAA1QAA/wAAzQAAzAAAzAAAzAAAywAAzQAAzAAAzwAAzQAAzQAAzAAAzAAAzAAAzAAAzAAAzQAAzQAAywAAzAAAywAAzQAAzQAAywAAzQAAzQAAzAAAzAAAzAAAzgAAzAAAzAAAzAAAyQAA/wAAzAAAzAAAzgAAywAAzAAAzAAAvwAAzAAAzAAAyAAAxgAAxAAA0QAAzAAAywAAzAB9Cq1OAAAAf3RSTlMAm2QnyXpw89Ev6Cg80yuLhxHG7xAdC8uSHPn99qz8shRCYTZ7+kb+TjXeChOf6dLULu2I6xWJbaS8pnT7xUNs4YTmx5lgN2qOUK0JDAGi5dvqQLGDID1m4trPyOxrUYUyilxIZzM4tKrjGvTQfSYCloIfY7W6CLCpDhINFvKeC12aOAAAAdZJREFUSMeVlmV3wjAUhgPDhgwYMB9swIAN5sDc3d3d3V3z10dTRpKWpOX90nNv3+e0sXsDgFQFbe2N21BQS1OnoRnw5TQUQlq6rm+2/cPhgnIZ7TGG378As8t1nM1eVAnZWnLK/JY6yNPgvsQfKIMKqqb8+eVQUaOEv8qo7Ic1pRgwQTWaz/hboTrdp/15UK1OREBHpM4i2gY2YEN+K5FZTsUJM5s4EoAOHNvF/coGilOvPTicSw/qgE1YANDj6CUNxNjLuAdAD/1BQVNezrDd3TiqiIpAPWdmQS8ZRQT73QNvKUAfGfnQweCunWRbeFLATCEXoP93UvjELRc4p+NagdDygHc6XkfT5OAAv5KEBhElbCApzfgRMa5uWpEWESHuGJNmi7twSOFTREyYxf87pM87tTX+ZUXE9I0ePVf6KYDcfBntUjXosQK/uaa2N1aQIt7wiwHqABF6JooQWYXy6CNK6Gon4w/i7JC0CJBq0r4i/xiRC8nKDCXz7FN1aJhIbOZayP47XlKlvyQzrEZV/o1cy/0l2VC8ykCAWsxapZYVXs2tKV7ky9polNd2fe5snXqE1Rla1hhXga+sV4cf+2dul5OEwoUmdf0xxZE3bus0yMb6BwWBItx4HNu4AAAAAElFTkSuQmCC"},239:function(A,t){A.exports="data:image/png;base64,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"},361:function(A,t,e){},496:function(A,t,e){"use strict";var z=e(361);e.n(z).a},545:function(A,t,e){"use strict";e.r(t);var z=function(){var A=this,t=A.$createElement,z=A._self._c||t;return z("div",{staticClass:"OrderShop"},[z("div",{staticClass:"shop_navbar"},[z("div",{staticClass:"shop_navbar_item",on:{click:function(t){A.type=1}}},[z("span",{class:{active:1==A.type}},[A._v("总库暂存点("),z("span",{class:{active_span:1==A.type}},[A._v(A._s(A.count))]),A._v(")")])]),A._v(" "),z("div",{staticClass:"shop_navbar_item",on:{click:function(t){A.type=2}}},[z("span",{class:{active:2==A.type}},[A._v("代理暂存点("),z("span",{class:{active_span:2==A.type}},[A._v(A._s(A.count2))]),A._v(")")])])]),A._v(" "),1==A.type?z("div",{staticClass:"shop_container_through"},[z("van-list",{attrs:{finished:A.finished,"finished-text":"没有更多了"},on:{load:A.getOrderShop},model:{value:A.loading,callback:function(t){A.loading=t},expression:"loading"}},A._l(A.orderShop,(function(t,i){return z("div",{key:i,staticClass:"shop_container_through_cycle",on:{click:function(e){return A.eventClickJumpDetails(t)}}},[z("div",{staticClass:"stores_top_tit"},[z("span",[A._v(A._s(t.zhicheng))])]),A._v(" "),z("div",{staticClass:"stores_top_container"},[z("div",{staticClass:"stores_top_container_left"},[z("span",[z("img",{attrs:{src:e(228),alt:""}}),A._v(" "+A._s(t.realname))]),A._v(" "),z("span",[z("img",{attrs:{src:e(234),alt:""}}),A._v(" "+A._s(t.phone))])]),A._v(" "),z("div",{staticClass:"stores_top_container_right"},[z("img",{attrs:{src:e(239),alt:""}}),A._v(" "),z("span",[A._v(A._s(t.address))])])])])})),0)],1):A._e(),A._v(" "),2==A.type?z("div",{staticClass:"shop_container_through"},[z("van-list",{attrs:{finished:A.finished2,"finished-text":"没有更多了"},on:{load:A.getOrderShop2},model:{value:A.loading2,callback:function(t){A.loading2=t},expression:"loading2"}},A._l(A.orderShop2,(function(t,i){return z("div",{key:i,staticClass:"shop_container_through_cycle",attrs:{"data-token":t.token},on:{click:function(e){return A.$router.push("/salesman/OrderAudit?id="+t.token)}}},[z("div",{staticClass:"stores_top_tit"},[z("span",[A._v(A._s(t.zhicheng))]),A._v(" "),z("span",{staticStyle:{"font-size":"1.4rem",color:"#FF0000","font-weight":"500"}},[A._v("待审核")])]),A._v(" "),z("div",{staticClass:"stores_top_container"},[z("div",{staticClass:"stores_top_container_left"},[z("span",[z("img",{attrs:{src:e(228),alt:""}}),A._v(" "+A._s(t.realname))]),A._v(" "),z("span",[z("img",{attrs:{src:e(234),alt:""}}),A._v(" "+A._s(t.phone))])]),A._v(" "),z("div",{staticClass:"stores_top_container_right"},[z("img",{attrs:{src:e(239),alt:""}}),A._v(" "),z("span",[A._v(A._s(t.address))])])])])})),0)],1):A._e()])};z._withStripped=!0;e(197);var i=e(198);var n,s,a,o={name:"superAdminOrderShop",components:(n={},s=i.a.name,a=i.a,s in n?Object.defineProperty(n,s,{value:a,enumerable:!0,configurable:!0,writable:!0}):n[s]=a,n),data:function(){return{type:1,page:0,list_rows:10,loading:!1,finished:!1,orderShop:[],count:0,count2:0,page2:0,list_rows2:10,loading2:!1,finished2:!1,orderShop2:[]}},mounted:function(){this.count2=this.$route.query.count},methods:{getOrderShop:function(){var A=this,t={page:this.page,list_rows:this.list_rows,status:2};this.$util.postRequestInterface("/api/home/user/getlist",t,(function(t){if(A.page=A.page+1,""==t.data)A.loading=!1,A.finished=!0;else{var e=!0,z=!1,i=void 0;try{for(var n,s=t.data.data[Symbol.iterator]();!(e=(n=s.next()).done);e=!0){var a=n.value;A.orderShop.push({id:a.id,token:a.token,zhicheng:a.zhicheng,realname:a.realname,address:a.address,phone:a.phone})}}catch(A){z=!0,i=A}finally{try{!e&&s.return&&s.return()}finally{if(z)throw i}}}A.count=t.data.count,A.loading=!1,A.orderShop.length>=t.data.count&&(A.finished=!0)}))},getOrderShop2:function(){var A=this,t={page:this.page2,list_rows:this.list_rows2,status:2,groupid:3,daili:1};this.$util.postRequestInterface("/api/home/user/getlist",t,(function(t){if(A.page2=A.page2+1,""==t.data)A.loading2=!1,A.finished2=!0;else{var e=!0,z=!1,i=void 0;try{for(var n,s=t.data.data[Symbol.iterator]();!(e=(n=s.next()).done);e=!0){var a=n.value;A.orderShop2.push({id:a.id,token:a.token,zhicheng:a.zhicheng,realname:a.realname,address:a.address,phone:a.phone})}}catch(A){z=!0,i=A}finally{try{!e&&s.return&&s.return()}finally{if(z)throw i}}}A.count2=t.data.count,A.loading2=!1,A.orderShop2.length>=t.data.count&&(A.finished2=!0)}))},eventClickJumpDetails:function(A){this.$router.push({path:"/salesman/order-stores",query:{id:A.id,token:A.token}})}}},r=(e(496),e(16)),g=Object(r.a)(o,z,[],!1,null,"067adcc6",null);g.options.__file="src/views/superAdmin/order-shop.vue";t.default=g.exports}}]);