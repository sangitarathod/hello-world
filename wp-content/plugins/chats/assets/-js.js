
(function(){
    var filepath;
    var scripts = document.getElementsByTagName('script');

    //var site_key = '47a3c39cb53d24adeb6434e2d3c7db40';
    var site_key = '';
    var site = '';

    for(i in scripts) {
        if(undefined !== scripts[i]['src'] && scripts[i]['src']) {
            if(found = scripts[i].src.match(/\/chats\/s\/(.*)\.js\?s=(.*)$/)) {
                site_key = found[1];
                site = decodeURIComponent(found[2]);
            }
        }
    }

    window['activateChat'] = function() {
        document.location.href = 'http://secure.wp-chat.com/chat?chat_site=' + site;
    }


    var max_width = '300' - 0 + 50;

    var style = document.createElement("style");
    style.type="text/css";
    style.innerHTML = " @media only screen and (max-width: "+max_width+"px) {\n.chat_"+site_key+" {right: 0px !important;width: 100% !important; } " +
        ".btn_finish_big {display: none;}; btn_finish_sml {display: inline}" +
        "} ";
    document.head.appendChild(style);


    var ifrm = document.createElement("iframe");

    var w = getWindowHeight();

    var src = "http://secure.wpadm.com/chats/s/_notactivated.html?h="+w+"&refer=" + encodeURIComponent(window.location.href);

    if(document.location.href.indexOf("https://") === 0) {
        src = src.replace("http://", "https://");
    }

    if (undefined != window['chats_parameters'] && 'object' == typeof chats_parameters && chats_parameters.hasOwnProperty('mode')) {
        src = src + '&mode=' + chats_parameters.mode;
    }

    if (undefined != window['chats_parameters'] && 'object' == typeof chats_parameters && chats_parameters.hasOwnProperty('create_time')) {
        src = src + '&o=' + chats_parameters.create_time;
    }

    if (undefined != window['chats_parameters'] && 'object' == typeof chats_parameters && chats_parameters.hasOwnProperty('sound_path')) {
        src = src + '&s=' + encodeURIComponent(chats_parameters.sound_path);
    }

    if (undefined != window['chats_parameters'] && 'object' == typeof chats_parameters && chats_parameters.hasOwnProperty('host')) {
        src = src + '&host=' + encodeURIComponent(chats_parameters.host);
    } else {
        src = src + '&host=' + encodeURIComponent(site);
    }

    if (undefined != window['chats_parameters'] && 'object' == typeof chats_parameters && chats_parameters.hasOwnProperty('local_cache')) {
        src = src + '&l=' + encodeURIComponent(chats_parameters.local_cache);
    }

    ifrm.id = site_key + '_cont';
    ifrm.className = 'chat_'+site_key;
    ifrm.setAttribute("src", src);
    ifrm.style.width = "300px";
    ifrm.style.maxHeight = "99%";
    <!--ifrm.style.height = "500px";-->
    ifrm.style.height = "40px";
    ifrm.style.position = "fixed";
    ifrm.style.bottom = "-2px";
    ifrm.style.marginBottom = "0px";

    ifrm.style.backgroundColor = 'white';
    ifrm.style.zIndex = 100000;
    ifrm.style.display = 'none';
    ifrm.style.border = '1px solid #2785C1';
    ifrm.style.boxShadow = '0 0 7px rgba(0, 0, 0, 0.3)';

    ifrm.style.right = "40px";
    document.body.appendChild(ifrm);

    window.addEventListener("resize", function(e) {
        afterResize();
    }, false);

    window.addEventListener("orientationchange", function(e) {
        afterResize();
    });

    function afterResize() {
        var ww = getWindowHeight();
        ww =(ww < 240) ? 240 : ww;
        if (w != ww) {
            w = ww;
            if (w < 500) {
                ifrm.contentWindow.postMessage(["set_height", w], "*");
            } else {
                ifrm.contentWindow.postMessage(["set_height", 500], "*");
            }
        }

    }


    var div_copyright = false;
    div_copyright = document.createElement("div");
    div_copyright.id = site_key + '_copyright';
    div_copyright.className = 'chat_'+site_key;
    div_copyright.innerHTML = 'Powered by <a target="_blank" href="http://wp-chat.com"><b>wp-chat.com</b></a>';
    div_copyright.style.width = "300px";
    div_copyright.style.maxHeight = "15px";
    div_copyright.style.height = "15px";
    div_copyright.style.position = "fixed";
    div_copyright.style.right = "40px";
    div_copyright.style.bottom = "4px";
    div_copyright.style.marginBottom = "0px";
    div_copyright.style.textAlign = "center";
    //div.style.backgroundColor = 'white';
    div_copyright.style.zIndex = 100001;
    div_copyright.style.display = 'none';
    div_copyright.style.border = 'none';
    //div.style.boxShadow = '0 0 7px rgba(0, 0, 0, 0.3)';
    div_copyright.style.fontSize = "12px";
    div_copyright.style.fontFamily = 'Arial, sans-serif';

    document.body.appendChild(div_copyright);


    //f.contentWindow.postMessage(["setMaxHeight", ''], "*");
    window.addEventListener('message', function(e) {
        if ('http://secure.wpadm.com'.replace('http://', 'https://') != e.origin.replace('http://', 'https://')) {
            return;
        }
        //debugger;
        var iframe = document.getElementById(site_key + '_cont');
        var eventName = e.data[0];
        var data = e.data[1];


        switch(eventName) {
            case 'setHeight':
                if (undefined == data) {
                    return;
                }

                if (typeof jQuery == 'undefined') {
                    iframe.style.height = data+'px';
                    if (div_copyright) {
                        if (data > 50) {
                            div_copyright.style.display = '';
                        } else {
                            div_copyright.style.display = 'none';
                        }
                    }
                } else {
                    //jQuery(iframe).height(data).animate("slow");
                    if (div_copyright && data < 50) {
                        jQuery('#'+site_key+'_copyright').hide();
                    }

                    jQuery(iframe).animate(
                        {height:data},
                        function(){
                            if (div_copyright && data > 50) {
                                    jQuery('#'+site_key+'_copyright').show();
                            }
                        });
                }


                break;
            case 'activate_chat':
                window['activateChat']();
                break;
            case 'chat_show':
                iframe.style.display = '';
                ifrm.contentWindow.postMessage(["chat_is_shown", ''], "*");
                break;
            case 'send_offline_email':
                jQuery.ajax({
                    type: "POST",
                    url: chats_parameters.request_url,
                    data: {
                        'mode'          : 'send_email',
                        'action'        : 'jsChatsProcess',
                        'message_page'  : window.location.href,
                        'text'          : data.message,
                        'name'          : data.user_name,
                        'email'         : data.user_email,
                        'offline_form_fields': data.offline_form_fields,
                        'user_timezone' : data.user_tz,
                        'only_mail': 1
                    },
                    dataType: "json",
                    cache: false,
                    success: function(data){
                    }
                });

                break;
            case 'send_email':
                jQuery.ajax({
                    type: "POST",
                    url: chats_parameters.request_url,
                    data: {
                        'mode'          : 'add',
                        'action'        : 'jsChatsProcess',
                        'message'       : data.message,
                        'presented_user_name'     : data.presented_user_name,
                        'presented_user_email'    : data.presented_user_email,
                        'message_page'  : window.location.href,
                        'user_timezone' : data.user_tz,
                        'only_mail': 1
                    },
                    dataType: "json",
                    cache: false,
                    success: function(data){
                    }
                });

                break;
        }
    }, false);

        function getUrlParameter(sParam, default_value) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),

                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] === sParam) {
                    if (sParameterName.length > 2) {
                        sParameterName.shift();
                        return sParameterName.join('='); //sParameterName[1];
                    }
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
            return default_value;
        };

        function getWindowHeight() {
            return window.innerHeight
            || document.documentElement.clientHeight
            || document.body.clientHeight
        }


        window['wpadm_chat_open'] = function() {
            ifrm.contentWindow.postMessage(["chat_maximize", ''], "*");
        }

        window['wpadm_chat_close'] = function() {
            ifrm.contentWindow.postMessage(["chat_minimize", ''], "*");
        }

        window['wpadm_chat'] = function() {
            ifrm.contentWindow.postMessage(["chat_max_min", ''], "*");
        }

        var list = document.getElementsByTagName('a'),
            i =0;
        for(; i< list.length; i++) {
            if (undefined == list[i].href) {
                continue;
            }
            if(list[i].href.indexOf('#chat_open') > 0) {
                list[i].addEventListener("click", function(e) {
                    wpadm_chat_open();
                    return false;
                });
                continue;
            }

            if(list[i].href.indexOf('#chat_close') > 0) {
                list[i].addEventListener("click", function(e) {
                    wpadm_chat_close();
                    return false;
                });
                continue;
            }

            if(list[i].href.indexOf('#chat') > 0) {
                list[i].addEventListener("click", function(e) {
                    wpadm_chat();
                    return false;
                });
            }
        }
    })();
