var ajax = new XMLHttpRequest();
var url = '/api/access/log?app_key=&url=' + window.location.href + '&title=' + document.title;
ajax.open('get', url);
ajax.send();