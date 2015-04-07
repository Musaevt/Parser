$(document).ready(function(){
var community=$('#community_gid').text();
var a=Ajax.get({url:"/search/start",data:{community_id:community}});
//setTimeout(function(){a.abort()},7000);
});
