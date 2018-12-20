(function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    var scheme = (document.location.href.indexOf('https://') === 0) ? 'https://' : 'http://';
    s1.src = scheme + chats_parameters.server + "/chats/s/" + chats_parameters.site_key + '.js?' + chats_parameters.create_time;
    s1.charset="UTF-8";
    s0.parentNode.insertBefore(s1,s0);
})();
