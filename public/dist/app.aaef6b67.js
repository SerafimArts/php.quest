(self.webpackChunk=self.webpackChunk||[]).push([[143],{699:function(t,e,n){"use strict";var r=n(527),i=n.n(r);function o(t,e,n){return function(t,e){if(t!==e)throw new TypeError("Private static access of wrong provenance")}(t,e),n}class a{static boot(){"loading"===document.readyState?document.addEventListener("DOMContentLoaded",(()=>o(this,a,u).call(this))):o(this,a,u).call(this)}}function u(){window.ko||(window.ko=o(a,a,c).call(a))}function c(){const t=document.querySelectorAll("[data-vm]");for(let e of t){let t=n(269)(`./${e.getAttribute("data-vm")}.js`).default;i().applyBindings(new t(e),e)}return i()}a.boot()},99:function(t,e,n){"use strict";n.r(e),n.d(e,{default:function(){return d}});var r=n(527),i=n.n(r);function o(t,e,n){return(e=function(t){var e=function(t,e){if("object"!=typeof t||null===t)return t;var n=t[Symbol.toPrimitive];if(void 0!==n){var r=n.call(t,e||"default");if("object"!=typeof r)return r;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(t,"string");return"symbol"==typeof e?e:String(e)}(e))in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}class a{constructor(t,e,n){o(this,"active",i().observable(!1)),this.title=t,this.slug=e,this.node=n}}function u(t,e){!function(t,e){if(e.has(t))throw new TypeError("Cannot initialize the same private elements twice on an object")}(t,e),e.add(t)}function c(t,e,n){return(e=function(t){var e=function(t,e){if("object"!=typeof t||null===t)return t;var n=t[Symbol.toPrimitive];if(void 0!==n){var r=n.call(t,e||"default");if("object"!=typeof r)return r;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(t,"string");return"symbol"==typeof e?e:String(e)}(e))in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function l(t,e,n){if(!e.has(t))throw new TypeError("attempted to get private field on non-instance");return n}var s=new WeakSet,f=new WeakSet;class d{constructor(t){u(this,f),u(this,s),c(this,"anchors",i().observableArray([])),l(this,f,v).call(this,t),l(this,s,h).call(this,window.scrollY),document.addEventListener("scroll",(()=>{window.requestAnimationFrame((()=>{l(this,s,h).call(this,window.scrollY)}))}))}}function h(t){let e=Number.MAX_VALUE,n=null;for(let r of this.anchors()){let i=t-r.node.offsetTop;i>-40&&e>i&&(e=i,n=r)}if(null!==n){for(let t of this.anchors())t!==n&&t.active(!1);n.active(!0)}}function v(t){for(let e of t.querySelectorAll("[data-anchor]")){let t=new a(e.textContent.trim(),e.getAttribute("data-anchor"),e);this.anchors.push(t)}}},269:function(t,e,n){var r={"./DocumentationViewModel.js":99};function i(t){var e=o(t);return n(e)}function o(t){if(!n.o(r,t)){var e=new Error("Cannot find module '"+t+"'");throw e.code="MODULE_NOT_FOUND",e}return r[t]}i.keys=function(){return Object.keys(r)},i.resolve=o,t.exports=i,i.id=269}},function(t){t.O(0,[527],(function(){return e=699,t(t.s=e);var e}));t.O()}]);