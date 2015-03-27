var Ajax = {_init:function() {
  return this.createXmlHttpRequest();
}, createXmlHttpRequest:function() {
  var b;
  try {
    b = new ActiveXObject("Microsoft.XMLHTTP");
  } catch (c) {
    try {
      b = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (a) {
      b = !1;
    }
  }
  b || "undefined" != typeof XMLHttpRequest && (b = new XMLHttpRequest);
  b || (location.href = "http://twosphere.ru/badbrowser");
  return b;
}, post:function(b, c) {
  var a = this._init();
  a && (a.open("POST", b.url, !0), a.setRequestHeader("X-Requested-With", "XMLHttpRequest"), a.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), a.send(Ajax.dataEncode(b.data)), a.onreadystatechange = function() {
    if (4 == a.readyState && 200 == a.status) {
      var b = $.parseJSON(a.responseText);
      c(b);
    }
  });
}, simple_get:function(b, c) {
  var a = this._init();
  a && (a.open("GET", b.url + "?" + Ajax.dataEncode(b.data), !0), a.setRequestHeader("X-Requested-With", "XMLHttpRequest"), a.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), a.send(), a.onreadystatechange = function() {
    4 == a.readyState && 200 == a.status && c(a.responseText);
  });
}, get:function(b, c) {
  var a = this._init();
 
  a && (a.open("GET", b.url + "?" + Ajax.dataEncode(b.data), !0), a.setRequestHeader("X-Requested-With", "XMLHttpRequest"), a.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), a.send(null), a.onreadystatechange = function() {
       if (4 == a.readyState && 200 == a.status) {
       var b = $.parseJSON(a.responseText);
       c(b);
    }
  })
  return a;
},
dataEncode:function(b) {
  var c = "";
  if (b) {
    for (var a in b) {
      b.hasOwnProperty(a) && (c += "&" + a.toString() + "=" + encodeURIComponent(b[a]));
    }
    if ("&" == c.charAt(0)) {
      return c.substring(1, c.length);
    }
  }
  return c;
}};