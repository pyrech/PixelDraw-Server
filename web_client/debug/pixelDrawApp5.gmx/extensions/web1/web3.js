/* start of autobahn script */
(function(a){a(function(){function a(c,b,e,j){return d(c).then(b,e,j)}function d(a){return a instanceof c?a:i(a)?e(a):f(a)}function e(a){var c=k();try{a.then(function(a){c.resolve(a)},function(a){c.reject(a)},function(a){c.progress(a)})}catch(b){c.reject(b)}return c.promise}function c(a){this.then=a}function f(a){return new c(function(c){try{return d("function"==typeof c?c(a):a)}catch(b){return g(b)}})}function g(a){return new c(function(c,b){try{return d("function"==typeof b?b(a):g(a))}catch(e){return g(e)}})}
function k(){function a(c,b,d){return i(c,b,d)}function b(a){return l(d(a))}function e(a){return l(g(a))}function f(a){return n(a)}var p,s,h,i,n,l;p=new c(a);p={then:a,resolve:b,reject:e,progress:f,notify:f,promise:p,resolver:{resolve:b,reject:e,progress:f,notify:f}};s=[];h=[];i=function(a,c,b){var d,e;d=k();e="function"===typeof b?function(a){try{d.notify(b(a))}catch(c){d.notify(c)}}:function(a){d.notify(a)};s.push(function(b){b.then(a,c).then(d.resolve,d.reject,e)});h.push(e);return d.promise};
n=function(a){j(h,a);return a};l=function(a){i=a.then;l=d;n=m;j(s,a);h=s=r;return a};return p}function i(a){return a&&"function"===typeof a.then}function l(c,d,e,j,f){p(2,arguments);return a(c,function(c){function p(a){q(a)}function g(a){l(a)}var h,m,i,o,n,l,q,r,t,w;t=c.length>>>0;h=Math.max(0,Math.min(d,t));i=[];m=t-h+1;o=[];n=k();if(h){r=n.notify;q=function(a){o.push(a);--m||(l=q=s,n.reject(o))};l=function(a){i.push(a);--h||(l=q=s,n.resolve(i))};for(w=0;w<t;++w)w in c&&a(c[w],g,p,r)}else n.resolve(i);
return n.promise.then(e,j,f)})}function h(a,c,b,d){p(1,arguments);return n(a,m).then(c,b,d)}function n(c,d){return a(c,function(c){var e,j,f,p,h,g;f=j=c.length>>>0;e=[];g=k();if(f){p=function(c,j){a(c,d).then(function(a){e[j]=a;--f||g.resolve(e)},g.reject)};for(h=0;h<j;h++)h in c?p(c[h],h):--f}else g.resolve(e);return g.promise})}function j(a,c){for(var b,d=0;b=a[d++];)b(c)}function p(a,c){for(var b,d=c.length;d>a;)if(b=c[--d],null!=b&&"function"!=typeof b)throw Error("arg "+d+" must be a function");
}function s(){}function m(a){return a}var q,t,r;a.defer=k;a.resolve=d;a.reject=function(c){return a(c,g)};a.join=function(){return n(arguments,m)};a.all=h;a.map=n;a.reduce=function(c,d){var e=t.call(arguments,1);return a(c,function(c){var j;j=c.length;e[0]=function(c,e,f){return a(c,function(c){return a(e,function(a){return d(c,a,f,j)})})};return q.apply(c,e)})};a.any=function(a,c,b,d){return l(a,1,function(a){return c?c(a[0]):a[0]},b,d)};a.some=l;a.chain=function(c,d,e){var j=2<arguments.length;
return a(c,function(a){a=j?e:a;d.resolve(a);return a},function(a){d.reject(a);return g(a)},function(a){"function"===typeof d.notify&&d.notify(a);return a})};a.isPromise=i;c.prototype={always:function(a,c){return this.then(a,a,c)},otherwise:function(a){return this.then(r,a)},yield:function(a){return this.then(function(){return a})},spread:function(a){return this.then(function(c){return h(c,function(c){return a.apply(r,c)})})}};t=[].slice;q=[].reduce||function(a){var c,b,d,e,j;j=0;c=Object(this);e=
c.length>>>0;b=arguments;if(1>=b.length)for(;;){if(j in c){d=c[j++];break}if(++j>=e)throw new TypeError;}else d=b[1];for(;j<e;++j)j in c&&(d=a(d,c[j],j,c));return d};return a})})("function"==typeof define&&define.amd?define:function(a){"object"===typeof exports?module.exports=a():this.when=a()});var CryptoJS=CryptoJS||function(a,b){var d={},e=d.lib={},c=e.Base=function(){function a(){}return{extend:function(c){a.prototype=this;var b=new a;c&&b.mixIn(c);b.hasOwnProperty("init")||(b.init=function(){b.$super.init.apply(this,arguments)});b.init.prototype=b;b.$super=this;return b},create:function(){var a=this.extend();a.init.apply(a,arguments);return a},init:function(){},mixIn:function(a){for(var c in a)a.hasOwnProperty(c)&&(this[c]=a[c]);a.hasOwnProperty("toString")&&(this.toString=a.toString)},
clone:function(){return this.init.prototype.extend(this)}}}(),f=e.WordArray=c.extend({init:function(a,c){a=this.words=a||[];this.sigBytes=c!=b?c:4*a.length},toString:function(a){return(a||k).stringify(this)},concat:function(a){var c=this.words,b=a.words,d=this.sigBytes,a=a.sigBytes;this.clamp();if(d%4)for(var e=0;e<a;e++)c[d+e>>>2]|=(b[e>>>2]>>>24-8*(e%4)&255)<<24-8*((d+e)%4);else if(65535<b.length)for(e=0;e<a;e+=4)c[d+e>>>2]=b[e>>>2];else c.push.apply(c,b);this.sigBytes+=a;return this},clamp:function(){var c=
this.words,b=this.sigBytes;c[b>>>2]&=4294967295<<32-8*(b%4);c.length=a.ceil(b/4)},clone:function(){var a=c.clone.call(this);a.words=this.words.slice(0);return a},random:function(c){for(var b=[],d=0;d<c;d+=4)b.push(4294967296*a.random()|0);return new f.init(b,c)}}),g=d.enc={},k=g.Hex={stringify:function(a){for(var c=a.words,a=a.sigBytes,b=[],d=0;d<a;d++){var e=c[d>>>2]>>>24-8*(d%4)&255;b.push((e>>>4).toString(16));b.push((e&15).toString(16))}return b.join("")},parse:function(a){for(var c=a.length,
b=[],d=0;d<c;d+=2)b[d>>>3]|=parseInt(a.substr(d,2),16)<<24-4*(d%8);return new f.init(b,c/2)}},i=g.Latin1={stringify:function(a){for(var c=a.words,a=a.sigBytes,b=[],d=0;d<a;d++)b.push(String.fromCharCode(c[d>>>2]>>>24-8*(d%4)&255));return b.join("")},parse:function(a){for(var c=a.length,b=[],d=0;d<c;d++)b[d>>>2]|=(a.charCodeAt(d)&255)<<24-8*(d%4);return new f.init(b,c)}},l=g.Utf8={stringify:function(a){try{return decodeURIComponent(escape(i.stringify(a)))}catch(c){throw Error("Malformed UTF-8 data");
}},parse:function(a){return i.parse(unescape(encodeURIComponent(a)))}},h=e.BufferedBlockAlgorithm=c.extend({reset:function(){this._data=new f.init;this._nDataBytes=0},_append:function(a){"string"==typeof a&&(a=l.parse(a));this._data.concat(a);this._nDataBytes+=a.sigBytes},_process:function(c){var b=this._data,d=b.words,e=b.sigBytes,h=this.blockSize,g=e/(4*h),g=c?a.ceil(g):a.max((g|0)-this._minBufferSize,0),c=g*h,e=a.min(4*c,e);if(c){for(var i=0;i<c;i+=h)this._doProcessBlock(d,i);i=d.splice(0,c);b.sigBytes-=
e}return new f.init(i,e)},clone:function(){var a=c.clone.call(this);a._data=this._data.clone();return a},_minBufferSize:0});e.Hasher=h.extend({cfg:c.extend(),init:function(a){this.cfg=this.cfg.extend(a);this.reset()},reset:function(){h.reset.call(this);this._doReset()},update:function(a){this._append(a);this._process();return this},finalize:function(a){a&&this._append(a);return this._doFinalize()},blockSize:16,_createHelper:function(a){return function(c,b){return(new a.init(b)).finalize(c)}},_createHmacHelper:function(a){return function(c,
b){return(new n.HMAC.init(a,b)).finalize(c)}}});var n=d.algo={};return d}(Math);(function(){var a=CryptoJS,b=a.lib.WordArray;a.enc.Base64={stringify:function(a){var b=a.words,c=a.sigBytes,f=this._map;a.clamp();for(var a=[],g=0;g<c;g+=3)for(var k=(b[g>>>2]>>>24-8*(g%4)&255)<<16|(b[g+1>>>2]>>>24-8*((g+1)%4)&255)<<8|b[g+2>>>2]>>>24-8*((g+2)%4)&255,i=0;4>i&&g+0.75*i<c;i++)a.push(f.charAt(k>>>6*(3-i)&63));if(b=f.charAt(64))for(;a.length%4;)a.push(b);return a.join("")},parse:function(a){var e=a.length,c=this._map,f=c.charAt(64);f&&(f=a.indexOf(f),-1!=f&&(e=f));for(var f=[],g=0,k=0;k<
e;k++)if(k%4){var i=c.indexOf(a.charAt(k-1))<<2*(k%4),l=c.indexOf(a.charAt(k))>>>6-2*(k%4);f[g>>>2]|=(i|l)<<24-8*(g%4);g++}return b.create(f,g)},_map:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="}})();(function(){var a=CryptoJS,b=a.enc.Utf8;a.algo.HMAC=a.lib.Base.extend({init:function(a,e){a=this._hasher=new a.init;"string"==typeof e&&(e=b.parse(e));var c=a.blockSize,f=4*c;e.sigBytes>f&&(e=a.finalize(e));e.clamp();for(var g=this._oKey=e.clone(),k=this._iKey=e.clone(),i=g.words,l=k.words,h=0;h<c;h++)i[h]^=1549556828,l[h]^=909522486;g.sigBytes=k.sigBytes=f;this.reset()},reset:function(){var a=this._hasher;a.reset();a.update(this._iKey)},update:function(a){this._hasher.update(a);return this},finalize:function(a){var b=
this._hasher,a=b.finalize(a);b.reset();return b.finalize(this._oKey.clone().concat(a))}})})();(function(a){var b=CryptoJS,d=b.lib,e=d.WordArray,c=d.Hasher,d=b.algo,f=[],g=[];(function(){function c(b){for(var d=a.sqrt(b),e=2;e<=d;e++)if(!(b%e))return!1;return!0}function b(a){return 4294967296*(a-(a|0))|0}for(var d=2,e=0;64>e;)c(d)&&(8>e&&(f[e]=b(a.pow(d,0.5))),g[e]=b(a.pow(d,1/3)),e++),d++})();var k=[],d=d.SHA256=c.extend({_doReset:function(){this._hash=new e.init(f.slice(0))},_doProcessBlock:function(a,c){for(var b=this._hash.words,d=b[0],e=b[1],f=b[2],s=b[3],m=b[4],q=b[5],t=b[6],r=b[7],o=
0;64>o;o++){if(16>o)k[o]=a[c+o]|0;else{var v=k[o-15],u=k[o-2];k[o]=((v<<25|v>>>7)^(v<<14|v>>>18)^v>>>3)+k[o-7]+((u<<15|u>>>17)^(u<<13|u>>>19)^u>>>10)+k[o-16]}v=r+((m<<26|m>>>6)^(m<<21|m>>>11)^(m<<7|m>>>25))+(m&q^~m&t)+g[o]+k[o];u=((d<<30|d>>>2)^(d<<19|d>>>13)^(d<<10|d>>>22))+(d&e^d&f^e&f);r=t;t=q;q=m;m=s+v|0;s=f;f=e;e=d;d=v+u|0}b[0]=b[0]+d|0;b[1]=b[1]+e|0;b[2]=b[2]+f|0;b[3]=b[3]+s|0;b[4]=b[4]+m|0;b[5]=b[5]+q|0;b[6]=b[6]+t|0;b[7]=b[7]+r|0},_doFinalize:function(){var c=this._data,b=c.words,d=8*this._nDataBytes,
e=8*c.sigBytes;b[e>>>5]|=128<<24-e%32;b[(e+64>>>9<<4)+14]=a.floor(d/4294967296);b[(e+64>>>9<<4)+15]=d;c.sigBytes=4*b.length;this._process();return this._hash},clone:function(){var a=c.clone.call(this);a._hash=this._hash.clone();return a}});b.SHA256=c._createHelper(d);b.HmacSHA256=c._createHmacHelper(d)})(Math);(function(){var a=CryptoJS,b=a.lib,d=b.Base,e=b.WordArray,b=a.algo,c=b.HMAC,f=b.PBKDF2=d.extend({cfg:d.extend({keySize:4,hasher:b.SHA1,iterations:1}),init:function(a){this.cfg=this.cfg.extend(a)},compute:function(a,b){for(var d=this.cfg,f=c.create(d.hasher,a),h=e.create(),n=e.create([1]),j=h.words,p=n.words,s=d.keySize,d=d.iterations;j.length<s;){var m=f.update(b).finalize(n);f.reset();for(var q=m.words,t=q.length,r=m,o=1;o<d;o++){r=f.finalize(r);f.reset();for(var v=r.words,u=0;u<t;u++)q[u]^=v[u]}h.concat(m);
p[0]++}h.sigBytes=4*s;return h}});a.PBKDF2=function(a,c,b){return f.create(b).compute(a,c)}})();/*
 MIT License (c) 2011-2013 Copyright Tavendo GmbH. */
var AUTOBAHNJS_VERSION="0.7.5",AUTOBAHNJS_DEBUG=!1,ab=window.ab={};ab._version=AUTOBAHNJS_VERSION;
(function(){Array.prototype.indexOf||(Array.prototype.indexOf=function(a){if(null===this)throw new TypeError;var b=Object(this),d=b.length>>>0;if(0===d)return-1;var e=0;0<arguments.length&&(e=Number(arguments[1]),e!==e?e=0:0!==e&&Infinity!==e&&-Infinity!==e&&(e=(0<e||-1)*Math.floor(Math.abs(e))));if(e>=d)return-1;for(e=0<=e?e:Math.max(d-Math.abs(e),0);e<d;e++)if(e in b&&b[e]===a)return e;return-1});Array.prototype.forEach||(Array.prototype.forEach=function(a,b){var d,e;if(this===null)throw new TypeError(" this is null or not defined");
var c=Object(this),f=c.length>>>0;if({}.toString.call(a)!=="[object Function]")throw new TypeError(a+" is not a function");b&&(d=b);for(e=0;e<f;){var g;if(e in c){g=c[e];a.call(d,g,e,c)}e++}})})();ab._sliceUserAgent=function(a,b,d){var e=[],c=navigator.userAgent,a=c.indexOf(a),b=c.indexOf(b,a);0>b&&(b=c.length);d=c.slice(a,b).split(d);c=d[1].split(".");for(b=0;b<c.length;++b)e.push(parseInt(c[b],10));return{name:d[0],version:e}};
ab.getBrowser=function(){var a=navigator.userAgent;return-1<a.indexOf("Chrome")?ab._sliceUserAgent("Chrome"," ","/"):-1<a.indexOf("Safari")?ab._sliceUserAgent("Safari"," ","/"):-1<a.indexOf("Firefox")?ab._sliceUserAgent("Firefox"," ","/"):-1<a.indexOf("MSIE")?ab._sliceUserAgent("MSIE",";"," "):null};ab.browserNotSupportedMessage="Browser does not support WebSockets (RFC6455)";
ab.deriveKey=function(a,b){return b&&b.salt?CryptoJS.PBKDF2(a,b.salt,{keySize:(b.keylen||32)/4,iterations:b.iterations||1E4,hasher:CryptoJS.algo.SHA256}).toString(CryptoJS.enc.Base64):a};ab._idchars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";ab._idlen=16;ab._subprotocol="wamp";ab._newid=function(){for(var a="",b=0;b<ab._idlen;b+=1)a+=ab._idchars.charAt(Math.floor(Math.random()*ab._idchars.length));return a};ab._newidFast=function(){return Math.random().toString(36)};
ab.log=function(){if(window.console&&console.log)if(1<arguments.length){console.group&&console.group("Log Item");for(var a=0;a<arguments.length;a+=1)console.log(arguments[a]);console.groupEnd&&console.groupEnd()}else console.log(arguments[0])};ab._debugrpc=!1;ab._debugpubsub=!1;ab._debugws=!1;ab.debug=function(a,b){if("console"in window)ab._debugrpc=a,ab._debugpubsub=a,ab._debugws=b;else throw"browser does not support console object";};ab.version=function(){return ab._version};
ab.PrefixMap=function(){this._index={};this._rindex={}};ab.PrefixMap.prototype.get=function(a){return this._index[a]};ab.PrefixMap.prototype.set=function(a,b){this._index[a]=b;this._rindex[b]=a};ab.PrefixMap.prototype.setDefault=function(a){this._index[""]=a;this._rindex[a]=""};ab.PrefixMap.prototype.remove=function(a){var b=this._index[a];b&&(delete this._index[a],delete this._rindex[b])};
ab.PrefixMap.prototype.resolve=function(a,b){var d=a.indexOf(":");if(0<=d){var e=a.substring(0,d);if(this._index[e])return this._index[e]+a.substring(d+1)}return!0==b?a:null};ab.PrefixMap.prototype.shrink=function(a,b){for(var d=a.length;0<d;d-=1){var e=this._rindex[a.substring(0,d)];if(e)return e+":"+a.substring(d)}return!0==b?a:null};ab._MESSAGE_TYPEID_WELCOME=0;ab._MESSAGE_TYPEID_PREFIX=1;ab._MESSAGE_TYPEID_CALL=2;ab._MESSAGE_TYPEID_CALL_RESULT=3;ab._MESSAGE_TYPEID_CALL_ERROR=4;
ab._MESSAGE_TYPEID_SUBSCRIBE=5;ab._MESSAGE_TYPEID_UNSUBSCRIBE=6;ab._MESSAGE_TYPEID_PUBLISH=7;ab._MESSAGE_TYPEID_EVENT=8;ab.CONNECTION_CLOSED=0;ab.CONNECTION_LOST=1;ab.CONNECTION_RETRIES_EXCEEDED=2;ab.CONNECTION_UNREACHABLE=3;ab.CONNECTION_UNSUPPORTED=4;ab.CONNECTION_UNREACHABLE_SCHEDULED_RECONNECT=5;ab.CONNECTION_LOST_SCHEDULED_RECONNECT=6;ab._Deferred=when.defer;
ab._construct=function(a,b){return"WebSocket"in window?b?new WebSocket(a,b):new WebSocket(a):"MozWebSocket"in window?b?new MozWebSocket(a,b):new MozWebSocket(a):null};
ab.Session=function(a,b,d,e){var c=this;c._wsuri=a;c._options=e;c._websocket_onopen=b;c._websocket_onclose=d;c._websocket=null;c._websocket_connected=!1;c._session_id=null;c._wamp_version=null;c._server=null;c._calls={};c._subscriptions={};c._prefixes=new ab.PrefixMap;c._txcnt=0;c._rxcnt=0;c._websocket=c._options&&c._options.skipSubprotocolAnnounce?ab._construct(c._wsuri):ab._construct(c._wsuri,[ab._subprotocol]);if(!c._websocket){if(void 0!==d){d(ab.CONNECTION_UNSUPPORTED);return}throw ab.browserNotSupportedMessage;
}c._websocket.onmessage=function(a){ab._debugws&&(c._rxcnt+=1,console.group("WS Receive"),console.info(c._wsuri+"  ["+c._session_id+"]"),console.log(c._rxcnt),console.log(a.data),console.groupEnd());a=JSON.parse(a.data);if(a[1]in c._calls){if(a[0]===ab._MESSAGE_TYPEID_CALL_RESULT){var b=c._calls[a[1]],d=a[2];if(ab._debugrpc&&void 0!==b._ab_callobj){console.group("WAMP Call",b._ab_callobj[2]);console.timeEnd(b._ab_tid);console.group("Arguments");for(var e=3;e<b._ab_callobj.length;e+=1){var l=b._ab_callobj[e];
if(void 0!==l)console.log(l);else break}console.groupEnd();console.group("Result");console.log(d);console.groupEnd();console.groupEnd()}b.resolve(d)}else if(a[0]===ab._MESSAGE_TYPEID_CALL_ERROR){b=c._calls[a[1]];d=a[2];e=a[3];l=a[4];if(ab._debugrpc&&void 0!==b._ab_callobj){console.group("WAMP Call",b._ab_callobj[2]);console.timeEnd(b._ab_tid);console.group("Arguments");for(var h=3;h<b._ab_callobj.length;h+=1){var n=b._ab_callobj[h];if(void 0!==n)console.log(n);else break}console.groupEnd();console.group("Error");
console.log(d);console.log(e);void 0!==l&&console.log(l);console.groupEnd();console.groupEnd()}void 0!==l?b.reject({uri:d,desc:e,detail:l}):b.reject({uri:d,desc:e})}delete c._calls[a[1]]}else if(a[0]===ab._MESSAGE_TYPEID_EVENT){if(b=c._prefixes.resolve(a[1],!0),b in c._subscriptions){var j=a[1],p=a[2];ab._debugpubsub&&(console.group("WAMP Event"),console.info(c._wsuri+"  ["+c._session_id+"]"),console.log(j),console.log(p),console.groupEnd());c._subscriptions[b].forEach(function(a){a(j,p)})}}else if(a[0]===
ab._MESSAGE_TYPEID_WELCOME)if(null===c._session_id){c._session_id=a[1];c._wamp_version=a[2];c._server=a[3];if(ab._debugrpc||ab._debugpubsub)console.group("WAMP Welcome"),console.info(c._wsuri+"  ["+c._session_id+"]"),console.log(c._wamp_version),console.log(c._server),console.groupEnd();null!==c._websocket_onopen&&c._websocket_onopen()}else throw"protocol error (welcome message received more than once)";};c._websocket.onopen=function(){if(c._websocket.protocol!==ab._subprotocol)if("undefined"===typeof c._websocket.protocol)ab._debugws&&
(console.group("WS Warning"),console.info(c._wsuri),console.log("WebSocket object has no protocol attribute: WAMP subprotocol check skipped!"),console.groupEnd());else if(c._options&&c._options.skipSubprotocolCheck)ab._debugws&&(console.group("WS Warning"),console.info(c._wsuri),console.log("Server does not speak WAMP, but subprotocol check disabled by option!"),console.log(c._websocket.protocol),console.groupEnd());else throw c._websocket.close(1E3,"server does not speak WAMP"),"server does not speak WAMP (but '"+
c._websocket.protocol+"' !)";ab._debugws&&(console.group("WAMP Connect"),console.info(c._wsuri),console.log(c._websocket.protocol),console.groupEnd());c._websocket_connected=!0};c._websocket.onerror=function(){};c._websocket.onclose=function(a){ab._debugws&&(c._websocket_connected?console.log("Autobahn connection to "+c._wsuri+" lost (code "+a.code+", reason '"+a.reason+"', wasClean "+a.wasClean+")."):console.log("Autobahn could not connect to "+c._wsuri+" (code "+a.code+", reason '"+a.reason+"', wasClean "+
a.wasClean+")."));void 0!==c._websocket_onclose&&(c._websocket_connected?a.wasClean?c._websocket_onclose(ab.CONNECTION_CLOSED,"WS-"+a.code+": "+a.reason):c._websocket_onclose(ab.CONNECTION_LOST):c._websocket_onclose(ab.CONNECTION_UNREACHABLE));c._websocket_connected=!1;c._wsuri=null;c._websocket_onopen=null;c._websocket_onclose=null;c._websocket=null}};
ab.Session.prototype._send=function(a){if(!this._websocket_connected)throw"Autobahn not connected";a=JSON.stringify(a);this._websocket.send(a);this._txcnt+=1;ab._debugws&&(console.group("WS Send"),console.info(this._wsuri+"  ["+this._session_id+"]"),console.log(this._txcnt),console.log(a),console.groupEnd())};ab.Session.prototype.close=function(){this._websocket_connected&&this._websocket.close()};ab.Session.prototype.sessionid=function(){return this._session_id};
ab.Session.prototype.shrink=function(a,b){void 0===b&&(b=!0);return this._prefixes.shrink(a,b)};ab.Session.prototype.resolve=function(a,b){void 0===b&&(b=!0);return this._prefixes.resolve(a,b)};ab.Session.prototype.prefix=function(a,b){this._prefixes.set(a,b);if(ab._debugrpc||ab._debugpubsub)console.group("WAMP Prefix"),console.info(this._wsuri+"  ["+this._session_id+"]"),console.log(a),console.log(b),console.groupEnd();this._send([ab._MESSAGE_TYPEID_PREFIX,a,b])};
ab.Session.prototype.call=function(){for(var a=new ab._Deferred,b;!(b=ab._newidFast(),!(b in this._calls)););this._calls[b]=a;for(var d=this._prefixes.shrink(arguments[0],!0),d=[ab._MESSAGE_TYPEID_CALL,b,d],e=1;e<arguments.length;e+=1)d.push(arguments[e]);this._send(d);ab._debugrpc&&(a._ab_callobj=d,a._ab_tid=this._wsuri+"  ["+this._session_id+"]["+b+"]",console.time(a._ab_tid),console.info());return a};
ab.Session.prototype.subscribe=function(a,b){var d=this._prefixes.resolve(a,!0);d in this._subscriptions||(ab._debugpubsub&&(console.group("WAMP Subscribe"),console.info(this._wsuri+"  ["+this._session_id+"]"),console.log(a),console.log(b),console.groupEnd()),this._send([ab._MESSAGE_TYPEID_SUBSCRIBE,a]),this._subscriptions[d]=[]);if(-1===this._subscriptions[d].indexOf(b))this._subscriptions[d].push(b);else throw"callback "+b+" already subscribed for topic "+d;};
ab.Session.prototype.unsubscribe=function(a,b){var d=this._prefixes.resolve(a,!0);if(d in this._subscriptions){var e;if(void 0!==b){var c=this._subscriptions[d].indexOf(b);if(-1!==c)e=b,this._subscriptions[d].splice(c,1);else throw"no callback "+b+" subscribed on topic "+d;}else e=this._subscriptions[d].slice(),this._subscriptions[d]=[];0===this._subscriptions[d].length&&(delete this._subscriptions[d],ab._debugpubsub&&(console.group("WAMP Unsubscribe"),console.info(this._wsuri+"  ["+this._session_id+
"]"),console.log(a),console.log(e),console.groupEnd()),this._send([ab._MESSAGE_TYPEID_UNSUBSCRIBE,a]))}else throw"not subscribed to topic "+d;};
ab.Session.prototype.publish=function(){var a=arguments[0],b=arguments[1],d=null,e=null,c=null,f=null;if(3<arguments.length){if(!(arguments[2]instanceof Array))throw"invalid argument type(s)";if(!(arguments[3]instanceof Array))throw"invalid argument type(s)";e=arguments[2];c=arguments[3];f=[ab._MESSAGE_TYPEID_PUBLISH,a,b,e,c]}else if(2<arguments.length)if("boolean"===typeof arguments[2])d=arguments[2],f=[ab._MESSAGE_TYPEID_PUBLISH,a,b,d];else if(arguments[2]instanceof Array)e=arguments[2],f=[ab._MESSAGE_TYPEID_PUBLISH,
a,b,e];else throw"invalid argument type(s)";else f=[ab._MESSAGE_TYPEID_PUBLISH,a,b];ab._debugpubsub&&(console.group("WAMP Publish"),console.info(this._wsuri+"  ["+this._session_id+"]"),console.log(a),console.log(b),null!==d?console.log(d):null!==e&&(console.log(e),null!==c&&console.log(c)),console.groupEnd());this._send(f)};ab.Session.prototype.authreq=function(a,b){return this.call("http://api.wamp.ws/procedure#authreq",a,b)};
ab.Session.prototype.authsign=function(a,b){b||(b="");return CryptoJS.HmacSHA256(a,b).toString(CryptoJS.enc.Base64)};ab.Session.prototype.auth=function(a){return this.call("http://api.wamp.ws/procedure#auth",a)};
ab._connect=function(a){var b=new ab.Session(a.wsuri,function(){a.connects+=1;a.retryCount=0;a.onConnect(b)},function(b,e){switch(b){case ab.CONNECTION_CLOSED:a.onHangup(b,"Connection was closed properly ["+e+"]");break;case ab.CONNECTION_UNSUPPORTED:a.onHangup(b,"Browser does not support WebSocket.");break;case ab.CONNECTION_UNREACHABLE:a.retryCount+=1;if(0==a.connects)a.onHangup(b,"Connection could not be established.");else if(a.retryCount<=a.options.maxRetries){var c=a.onHangup(ab.CONNECTION_UNREACHABLE_SCHEDULED_RECONNECT,
"Connection unreachable - scheduled reconnect to occur in "+a.options.retryDelay/1E3+" second(s).",{delay:a.options.retryDelay,retries:a.retryCount,maxretries:a.options.maxRetries});c?(console.log("Connection unreachable - retrying stopped by app"),a.onHangup(ab.CONNECTION_RETRIES_EXCEEDED,"Number of connection retries exceeded.")):(console.log("Connection unreachable - retrying ("+a.retryCount+") .."),window.setTimeout(ab._connect,a.options.retryDelay,a))}else a.onHangup(ab.CONNECTION_RETRIES_EXCEEDED,
"Number of connection retries exceeded.");break;case ab.CONNECTION_LOST:a.retryCount+=1;if(a.retryCount<=a.options.maxRetries)(c=a.onHangup(ab.CONNECTION_LOST_SCHEDULED_RECONNECT,"Connection lost - scheduled reconnect to occur in "+a.options.retryDelay/1E3+" second(s).",{delay:a.options.retryDelay,retries:a.retryCount,maxretries:a.options.maxRetries}))?(console.log("Connection lost - retrying stopped by app"),a.onHangup(ab.CONNECTION_RETRIES_EXCEEDED,"Connection lost.")):(console.log("Connection lost - retrying ("+
a.retryCount+") .."),window.setTimeout(ab._connect,a.options.retryDelay,a));else a.onHangup(ab.CONNECTION_RETRIES_EXCEEDED,"Connection lost.");break;default:throw"unhandled close code in ab._connect";}},a.options)};
ab.connect=function(a,b,d,e){var c={};c.wsuri=a;c.options=e?e:{};void 0==c.options.retryDelay&&(c.options.retryDelay=5E3);void 0==c.options.maxRetries&&(c.options.maxRetries=10);void 0==c.options.skipSubprotocolCheck&&(c.options.skipSubprotocolCheck=!1);void 0==c.options.skipSubprotocolAnnounce&&(c.options.skipSubprotocolAnnounce=!1);if(b)c.onConnect=b;else throw"onConnect handler required!";c.onHangup=d?d:function(a,b){console.log(b)};c.connects=0;c.retryCount=0;ab._connect(c)};ab._UA_FIREFOX=/.*Firefox\/([0-9+]*).*/;ab._UA_CHROME=/.*Chrome\/([0-9+]*).*/;ab._UA_CHROMEFRAME=/.*chromeframe\/([0-9]*).*/;ab._UA_WEBKIT=/.*AppleWebKit\/([0-9+.]*)w*.*/;ab._UA_WEBOS=/.*webOS\/([0-9+.]*)w*.*/;ab._matchRegex=function(a,b){var d=b.exec(a);return d?d[1]:d};
ab.lookupWsSupport=function(){var a=navigator.userAgent;if(-1<a.indexOf("MSIE")){if(-1<a.indexOf("MSIE 10"))return[!0,!0,!0];if(-1<a.indexOf("chromeframe")){var b=parseInt(ab._matchRegex(a,ab._UA_CHROMEFRAME));return 14<=b?[!0,!1,!0]:[!1,!1,!1]}if(-1<a.indexOf("MSIE 8")||-1<a.indexOf("MSIE 9"))return[!0,!0,!0]}else{if(-1<a.indexOf("Firefox")){if(b=parseInt(ab._matchRegex(a,ab._UA_FIREFOX))){if(7<=b)return[!0,!1,!0];if(3<=b)return[!0,!0,!0]}return[!1,!1,!0]}if(-1<a.indexOf("Safari")&&-1==a.indexOf("Chrome")){if(b=
ab._matchRegex(a,ab._UA_WEBKIT))return-1<a.indexOf("Windows")&&"534+"==b||-1<a.indexOf("Macintosh")&&(b=b.replace("+","").split("."),535==parseInt(b[0])&&24<=parseInt(b[1])||535<parseInt(b[0]))?[!0,!1,!0]:-1<a.indexOf("webOS")?(b=ab._matchRegex(a,ab._UA_WEBOS).split("."),2==parseInt(b[0])?[!1,!0,!0]:[!1,!1,!1]):[!0,!0,!0]}else if(-1<a.indexOf("Chrome")){if(b=parseInt(ab._matchRegex(a,ab._UA_CHROME)))return 14<=b?[!0,!1,!0]:4<=b?[!0,!0,!0]:[!1,!1,!0]}else if(-1<a.indexOf("Android")){if(-1<a.indexOf("Firefox")||
-1<a.indexOf("CrMo"))return[!0,!1,!0];if(-1<a.indexOf("Opera"))return[!1,!1,!0];if(-1<a.indexOf("CrMo"))return[!0,!0,!0]}else if(-1<a.indexOf("iPhone")||-1<a.indexOf("iPad")||-1<a.indexOf("iPod"))return[!1,!1,!0]}return[!1,!1,!1]};
/* end of autobahn script */

/* GM API: end of prototype

  HTML5_is_category_list_ready()
  HTML5_get_nb_categories()
  HTML5_get_the_category_id(i)
  HTML5_get_the_category_name(i)
  HTML5_get_current_word()
  HTML5_am_I_admin()
  HTML5_get_admin()
  HTML5_is_room_list_ready()
  HTML5_get_nb_rooms()
  HTML5_get_the_room_id(i)
  HTML5_get_the_room_name(i)
  HTML5_get_the_room_count_player(i)
  HTML5_get_message()
  HTML5_get_my_id()
  HTML5_get_my_name()
  HTML5_room_id()
  HTML5_room_name()
  HTML5_get_nb_players()
  HTML5_get_the_player_id(i)
  HTML5_get_the_player_name(i)
  HTML5_get_the_color(i)
  HTML5_get_current_state()
  HTML5_remove_ios_bar()
  HTML5_browser_support_websockets()
  HTML5_is_connected()
  HTML5_init()
  HTML5_send_message(msg)
  HTML5_login(name)
  HTML5_get_info_player(player_id)
  HTML5_create_room(room_name)
  HTML5_join_room(room_id)
  HTML5_leave_room()
  HTML5_get_room_list()
  HTML5_get_categories()
  HTML5_get_word(category_id)
  HTML5_get_room_players()
*/

var pd_categories = [];
var pd_categories_ready = false;
function HTML5_is_category_list_ready(){if (pd_categories_ready){pd_categories_ready=false;return 1;} else {return 0;}}
function HTML5_get_nb_categories(){return pd_categories.length;} //int
function HTML5_get_the_category_id(i){return pd_categories[i].id;} //String
function HTML5_get_the_category_name(i){return pd_categories[i].name;} //String

var pd_word = "";
function HTML5_get_current_word(){return pd_word;} //String

var pd_current_room = {};
function HTML5_am_I_admin(){if (pd_current_room.drawer_id == pd_my_id) {return 1;} else {return 0;}}//Int
function HTML5_get_admin(){if (pd_current_room.drawer_id) {return pd_current_room.drawer_id;} else {return "";}}//Int
function HTML5_get_state(){if (pd_current_room.state) {return pd_current_room.state;} else {return -1;}}//Int

var pd_rooms= [];
var pd_room_ready = false;
function HTML5_is_room_list_ready(){if (pd_room_ready){pd_room_ready=false;return 1;} else {return 0;}}
function HTML5_get_nb_rooms(){return pd_rooms.length;} //Int
function HTML5_get_the_room_id(i){return pd_rooms[i].id;} //String
function HTML5_get_the_room_name(i){return pd_rooms[i].name;} //String
function HTML5_get_the_room_count_player(i){return pd_rooms[i].count_player;}//Int
function HTML5_get_the_room_max_player(i){return pd_rooms[i].max_player;}//Int /*fixme*/



var pd_messages = []
function HTML5_get_message(){ //String
  if (pd_messages.length>0) {
    return pd_messages.shift();
  } else {
    return "";
  }
}

var pd_my_id = "";   // player id (string)
var pd_my_name = ""; // player name (string)
function HTML5_get_my_id(){return pd_my_id;} //String
function HTML5_get_my_name(){return pd_my_name;} //String

var pd_room_id = ""; // room id (string)
var pd_room_name = ""; // room id (string)
function HTML5_room_id(){return pd_room_id;} //String
function HTML5_room_name(){return pd_room_name;} //String

var pd_current_players = [];
var pd_players_ready = false;
function HTML5_are_players_ready(){if (pd_players_ready){pd_players_ready=false;return 1;} else {return 0;}}
function HTML5_get_nb_players(){return pd_current_players.length;} //Int
function HTML5_get_the_player_id(i){return pd_current_players[i].id;} //String
function HTML5_get_the_player_name(i){return pd_current_players[i].name;} //String
function HTML5_get_the_player_score(i){return pd_current_players[i].score;} //String

var pd_current_colors = [];
var pd_current_colors_ready = false;
function HTML5_get_the_color_ready(){if (pd_current_colors_ready){pd_current_colors_ready=false;return true;}else{return false;}}
function HTML5_get_the_color(i){if (pd_current_colors.length == 256) {return pd_current_colors[i];} else {return 0;} } //Int

var pd_current_state = 0;
function HTML5_get_current_state(){return pd_current_state;}

function HTML5_remove_ios_bar(){
  window.scrollTo(0, 1);
  window.addEventListener("load",function() {
    setTimeout(function(){
      window.scrollTo(0, 1);
      console.log("window scrolled");
    }, 0);
  });
}

function HTML5_browser_support_websockets() {
  if ("WebSocket" in window) { 
    return 1;
  } else {
    return 0;
  }
}

/*========= Wamp ============*/
var sess = null;// WAMP session object
function HTML5_is_connected() {if (sess == null) {return 0;} else {return 1;}}
function HTML5_init() {
  console.log("init called");
  ws_adress = "ws://88.191.157.29:8080"
  ab.connect(ws_adress,
  function (session) {
    sess = session;
    console.log("Connected to "+ws_adress+" !");
  },    
  function (code, reason) {
    console.log("connexion closed");
  });
};

/*========= PubSub ============*/

function subscribe_chat(){
  sess.subscribe(pd_room_id, pd_onEvent);
}

function join_room(data) {
  pd_room_id = data.room.id;
  pd_room_name = data.room.name;
  pd_current_room = data.room;
  
  pd_current_players = data.room.players;
  pd_players_ready=true;

  console.log("you join the room ",pd_room_id, " (",pd_room_name,")");
  subscribe_chat();
}

function leave_room() {
  sess.unsubscribe(pd_room_id);
  pd_room_id = "";
  pd_room_name = "";
  pd_current_room = {}
  pd_word = ""
  console.log("you left your room");
}

function HTML5_send_message(msg){
  console.log(msg,"sent to chatroom");console.log(msg,"sent to chatroom");
  sess.publish(pd_room_id, {"type":0,"event":{"msg":msg}},false);
}

function HTML5_send_picture(msg){
  //console.log(eval(msg),"picture sent");
  sess.publish(pd_room_id, {"type":3,"event":{"picture":eval(msg)}},false);
}

/*========= RCP ============*/
function HTML5_login(name){
  sess.call("login",{"name":name}).then(login_cb, log_call_result_error);
  console.log("login(",name,") called");
}
    function login_cb(data){
      console.log("login callback_received", data);
      pd_my_id = data.player.id;
      pd_my_name = data.player.name;
      document.getElementById('get_info_player_id').value = "{player_id: '"+pd_my_id+"'}";
    }

function HTML5_get_info_player(player_id) {
  sess.call("get_info_player",{"player_id":player_id}).then(get_info_player_cb, log_call_result_error);
  console.log("get_info_player(",player_id,") called");
}
    function get_info_player_cb(data) {
      console.log("get_info_player callback_received: ",data);
    }


function HTML5_create_room(room_name){
  sess.call("create_room",{"room_name": room_name}).then(create_room_cb, log_call_result_error);
  console.log("create_room(",{"room_name": room_name},") called");
}
    function create_room_cb(data){
      console.log("create_room callback_received: ", data);
      join_room(data);
    }

function HTML5_join_room(room_id){
  sess.call("join_room",{"room_id":room_id}).then(join_room_cb, log_call_result_error);
  console.log("join_room(",room_id,") called");
}
    function join_room_cb(data){
      console.log("join_room callback_received: ",data);
      pd_current_room=data.room;

      pd_current_players=data.room.players;
      pd_players_ready=true;
      
      join_room(data);
    }

function HTML5_leave_room(){
  if (pd_room_id != ""){
    sess.call("leave_room",{"room_id":pd_room_id}).then(leave_room_cb, log_call_result_error);
    console.log("join_room(",pd_room_id,") called");
  } else {
    console.log("WARNING: trying to leave room, but not in any room");
  }
}
    function leave_room_cb(data){
      console.log("leave_room callback received: ", data);
      leave_room();
    }

function HTML5_get_room_list(){
  console.log("get_room_list() called");
  sess.call("get_room_list",{}).then(get_room_list_cb, log_call_result_error);
  pd_room_ready = false;
}
    function get_room_list_cb(data){
      console.log("get_room_list callback received: ", data);
      pd_rooms = data.rooms;
      pd_room_ready = true;
    }

function HTML5_get_categories(){
  console.log("get_categories() called");
  sess.call("get_categories",{}).then(get_categories_cb, log_call_result_error);
  pd_categories_ready = false;
}
    function get_categories_cb(data){
      console.log("get_categories_list callback received: ", data);
      pd_categories = data.categories;
      pd_categories_ready = true;
    }

function HTML5_get_word(category_id){
  sess.call("get_word",{"category_id":category_id}).then(get_word_cb, log_call_result_error);
}
    function get_word_cb(data){
      console.log("get_word callback received: ", data);
      pd_word = data.word.name;
      console.log("drawer must draw",pd_word);
    }

function HTML5_get_room_players(){
  sess.call("get_room_players",{"room_id":pd_room_id}).then(get_room_players_cb, log_call_result_error);
  pd_players_ready = false;
}
    function get_room_players_cb(data){
      console.log("get_room_players callback received: ", data);
      pd_current_players = data.players;
      pd_players_ready=true;
    }


/*========= In-Game logic ============*/
function pd_onEvent(topicUri, data) {
  //console.log("game event received for",topicUri, data);
  switch (data.type) {
    case 0: 
      // 0: GamePlayerEvent {player_id:String, msg:String}
      console.log("game event received is:",0,"GamePlayerEvent:",data); 
      pd_messages.push(data.event.msg); 
    break;
    case 1:
      // 1: GameServerEvent{message: String}
      console.log("game event received is:",1,"GameServerEvent:",data); 
      pd_messages.push(data.event.msg); 
    break;
    case 2:
      // 2: GameRoomEvent{room:Room, player_list:[Player]}
      console.log("game event received is:",2,"GameRoomEvent:",data); 
      pd_current_room = data.event.room;
      pd_current_players = data.event.room.players;
      pd_players_ready=true;
    break;
    case 3:
      // 3: GameDrawEvent{picture:[Pixel} (avec Pixel = Int (RGBA))
      console.log("game event received is:",3,"GameDrawEvent:",data); 
      pd_current_colors = data.event.picture;
      pd_current_colors_ready = true;
    break;
  }
}

function log_call_result_error(arg) {
  console.log('ERROR received, data: ',arg);
}