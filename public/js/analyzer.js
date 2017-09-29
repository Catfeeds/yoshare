var ajax = new XMLHttpRequest();
var url = '/api/analyzers/web?app_key=&url=' + window.location.href;
ajax.open('get', url);
ajax.send();