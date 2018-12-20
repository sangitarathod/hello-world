function setCookie(name,value){name=WIDGET_KEY+'_'+name;var path='/';var today=new Date();today.setTime(today.getTime());var expires=1000*60*60*('5'-0);expires=(0==expires)?1000*60*60*5:expires;var expires_date=new Date(today.getTime()+(expires));var expires=expires_date.toGMTString();document.cookie=name+"="+escape(value)+((expires)?"; expires="+expires:"")+((path)?"; path="+path:"")}
function getCookie(name,default_value){name=WIDGET_KEY+'_'+name;var cookie=" "+document.cookie;var search=" "+name+"=";var setStr=default_value;var offset=0;var end=0;if(cookie.length>0){offset=cookie.indexOf(search);if(offset!=-1){offset+=search.length;end=cookie.indexOf(";",offset)
if(end==-1){end=cookie.length}
setStr=unescape(cookie.substring(offset,end))}}
return(setStr)}
function deleteCookie(name){name=WIDGET_KEY+'_'+name;var path='/';var value=!1;var today=new Date();today.setTime(today.getTime());var expires=-1*1000*60*60*24;var expires_date=new Date(today.getTime()+(expires));var expires=expires_date.toGMTString();document.cookie=name+"="+escape(value)+((expires)?"; expires="+expires:"")+((path)?"; path="+path:"")}
function urlify(text){var urlRegex=/(https?:\/\/[^\s]+)/g;return text.replace(urlRegex,function(url){return '<a href="'+url+'" target="_blank">'+url+'</a>'})}
var PAGE_URL=getUrlParameter('refer');var HTTP_SERVER_URL='http://secure.wpadm.com/chat/widget/[action]/c37b56ddf83a66b9c14edf474a25cd05';if(PAGE_URL.indexOf('https://')===0){HTTP_SERVER_URL=HTTP_SERVER_URL.replace("http://","https://")}
var WS_SERVER_URL='https://secure.wpadm.com:8082';var WIDGET_KEY='c37b56ddf83a66b9c14edf474a25cd05';var settings={ask_name:'',ask_email:'',admin_signature:'Admin',user_signature:'You',admin_avatar:'/wp-content/plugins/chats/assets/admin_avatar.png',user_avatar:'/wp-content/plugins/chats/assets/user_avatar.png',hello_message:'Hello. Do you have any questions?',popup_automatically_delay_sec:"10"-0,popup_automatically:"0"-0,mode:(undefined==getUrlParameter('mode')||getUrlParameter('mode')!="plugin")?"self":"plugin",sound_path:(undefined!=getUrlParameter('s'))?getUrlParameter('s'):"",host:(undefined!=getUrlParameter('host'))?getUrlParameter('host'):'',copyright:1,user_timezone:(-new Date().getTimezoneOffset()/60),status:1,show_avatars:0,panel_min_height:39,chatbox_type:'normal'}
if(PAGE_URL.indexOf('https://')===0){settings.host=settings.host.replace("http://","https://")}
if(settings.admin_avatar.indexOf('http')!==0){settings.admin_avatar=settings.host+settings.admin_avatar}
if(settings.user_avatar.indexOf('http')!==0){settings.user_avatar=settings.host+settings.user_avatar}
if(settings.chatbox_type=='circle'){$(document.body).addClass('transparent_body')}
var Config=function(){_this=this;_this.hasChat=function(){return getCookie('chat2_has_chat',0)}
_this.setHasChat=function(value){setCookie('chat2_has_chat',value)}
_this.boxOpened=function(){return getCookie('chat2_box_opened',0)}
_this.setBoxOpened=function(value){setCookie('chat2_box_opened',value)}
_this.allowedAutoPopup=function(){return(-1==getCookie('chat2_box_opened',-1))}
_this.getSoundPath=function(){if(settings.mode=='plugin'){return settings.host+settings.sound_path}else{return '/chat_sound'}}}
var config=new Config();var Chat2=function(){_this=this;var _io,_view;_this.getView=function(){return _view};_this.getIO=function(){return _io};_this.hash='';_this.init=function(){loadHash();_view=new Chat2_View(this);if(undefined==window.io){window.parent.postMessage(["chat_show",''],"*");_view.offline();return}
_io=new Chat2_IO(this);if($("#messages_cont .message").length==0){_view.helloMessage()}
if(settings.status!=2){window.parent.postMessage(["chat_show",""],"*")}}
function isMySite(){var refer=PAGE_URL.replace('https://','').replace('http://','').replace('www.','');var my_site='http://localhost/woocommerce_demo'.replace('https://','').replace('http://','').replace('www.','');return(refer.indexOf(my_site)===0)}
_this.initAfterShowChatbox=function(){if(config.hasChat()==1){_io.connect(function(){_io.send('load_messages',{})});if(config.boxOpened()==1){__Observer.subscribe('io_load_messages__after',function(){chat2.getView().maximize()})}}else{if(config.boxOpened()==1){_view.maximize()}}
if(settings.popup_automatically==1){setTimeout(function(){if(config.allowedAutoPopup()){chat2.getView().maximize()}},1000*settings.popup_automatically_delay_sec)}}
_this.playSound=function(direct){if(undefined==direct||direct=='in'){direct='out'}else{direct='in'}
var path=config.getSoundPath();$('<audio autoplay="autoplay" style="display:none;">'+'<source src="'+path+'/'+direct+'.mp3" />'+'</audio>').appendTo('body')}
_this.send=function(){var fields=[];fields.push($('#message')[0]);if(settings.ask_email!=''&&$('#chat_online .message').length==1&&$('#ask_email').length){fields.push($('#ask_email')[0])}
if(settings.ask_name!=''&&$('#chat_online .message').length==1&&$('#ask_name').length){fields.push($('#ask_name')[0])}
if(!validateForm(fields)){return}
if(!_io.is_connected()){_io.connect(chat2.send);return}
var m=new Chat2_Message;m.text=$('#message').val();m.direct='in';var message_data={message:m.text,presented_user_name:$('#ask_name').val(),presented_user_email:$('#ask_email').val(),user_tz:settings.user_timezone};_io.send('message',message_data)
config.setHasChat(1);_view.message(m);if(settings.mode=='plugin'&&$("#messages_cont .message").length==2){window.parent.postMessage(["send_email",message_data],"*")}
_view.hideAskFields();$('#message').val('');$('#message').focus();chat2.playSound('in')}
_this.sendOffline=function(){if(!validOfflineForm()){return}
var data={user_name:$('#offline_name').val(),user_email:$('#offline_email').val(),user_tz:settings.user_timezone,message:$('#offline_message').val(),hash:generateHash(),message_page:PAGE_URL,offline_form_fields:[]}
$('.offline_form_fields').each(function(){data.offline_form_fields.push($(this).val())})
jQuery.ajax({url:HTTP_SERVER_URL.replace('[action]','send-offline'),type:'post',data:data,success:function(res_data){if(settings.mode=='plugin'){window.parent.postMessage(["send_offline_email",data],"*")}
$('#chat_offline').find('.form').hide();$('#chat_offline').find('.result-success').show();var that=this;setTimeout(function(){_view.minimize();$('#chat_offline').find('input').val('');$('#chat_offline').find('textarea').val('');$('#chat_offline').find('.form').show();$('#chat_offline').find('.result-success').hide()},5000)}})}
_this.afterFinish=function(){$('#email_for_transcription').val($('#ask_email').val());_view.afterFinish();config.setHasChat(0);deleteHash();loadHash();setTimeout(function(){chat2.finish()},5*1000)}
_this.sendTranscription=function(){var fields=[];fields.push($('#email_for_transcription')[0]);if(!validateForm(fields)){return}
_io.send('send_transcription',{email:$('#email_for_transcription').val()},function(){});chat2.finish()}
_this.finish=function(){_view.minimize();if(_io.is_connected)
_io.disconnect();config.setHasChat(0);deleteHash();loadHash();_view.deleteMessages();_view.showAskFields();_view.unmarkInput($('#message'),'wrong');_view.unmarkInput($('#ask_name'),'wrong');_view.unmarkInput($('#ask_email'),'wrong');_view.online();$('.btn_finish').css('display','none')}
function loadMessages(){_io.send('load_messages',{})}
_this.io_message=function(data){_view.message(data)}
_this.io_messages=function(data){_view.deleteMessages();var i=0,l=data.length;for(;i<l;i++){_view.message(data[i])}
if(data.length==0){config.setHasChat(0);_view.showAskFields()}else{_view.hideAskFields();config.setHasChat(1)}}
function validateForm(fields){var res=!0;var email_re=/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;for(i in fields){var r=!0;var $f=$(fields[i]);if($f.attr('required')&&$.trim($f.val())==''){r=!1}
if($f.attr('type')=='email'&&$.trim($f.val())!=''&&!email_re.test($f.val())){r=!1}
if(r){_view.unmarkInput($f,'wrong')}else{_view.markInput($f,'wrong');res=!1;$f.focus()}}
return res}
function validOfflineForm(){var res=!0;var re=/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;var fields=['offline_message','offline_email','offline_name'];for(i in fields){var input=$("#"+fields[i]);if($.trim($(input).val())==''||(fields[i]=='offline_email'&&!re.test($(input).val()))){_view.markInput($(input),'wrong')
$(input).focus();res=!1}else{_view.unmarkInput($(input),'wrong')}}
return res}
function loadHash(){chat2.hash=getCookie('chat2_hash');if(!chat2.hash){chat2.hash=generateHash();setCookie('chat2_hash',chat2.hash)}}
function deleteHash(){_this.hash=!1;deleteCookie('chat2_hash')}
function generateHash(){var hash='';var words='0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';var max_position=words.length-1;var position=0;for(i=0;i<10;++i){position=Math.floor(Math.random()*max_position);hash=hash+words.substring(position,position+1)}
return hash}}
var Chat2_View=function(chat2){_this=this;var default_height=getUrlParameter('h')-20;default_height=(default_height<500)?default_height:500;_this.setDefaultHeight=function(h){var old_value=default_height;default_height=h;default_height=(default_height<500)?default_height:500;if(1==config.boxOpened()&&old_value!=default_height){chat2.getView().maximize()}}
_this.message=function(message){var c=(message.direct=='in')?'message-in':'message-out';var signature=(message.direct=='in')?settings.user_signature:settings.admin_signature;if(message.direct=='out'&&undefined!=message.operator_signature&&message.operator_signature){signature=message.operator_signature}
if(settings.show_avatars){var avatar=((message.direct=='in')?settings.user_avatar:settings.admin_avatar)}else{var avatars=!1}
var time='';if(message.hasOwnProperty('created')){var m=message.created.match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/);var d=new Date(m[1],m[2],m[3],m[4],m[5],m[6]);d.setHours((m[4]-0)+settings.user_timezone);var hour=d.getHours();hour=(hour<10)?"0"+hour:hour;var min=d.getMinutes();min=(min<10)?"0"+min:min;time=hour+':'+min}
var t=$("<div>").text(message.text).html();t=urlify(t);var h='<div class="message '+c+'">'+((!avatar)?'':'<span class="chats_avatar"><img src="'+avatar+'"></span>')+'<div class="chats_message"><span class="chats_name">'+$("<div>").text(signature).html()+'</span>'+'<span class="chats_time">'+time+'</span>'+'<div style="clear: both"></div>'+'<span class="chats_text">'+t+'</span></div>'+'</div>'+'<div style="clear: both"></div>';$('#messages_cont').append(h);scrollToLastMessage()}
_this.helloMessage=function(){chat2.getView().message({direct:'out',text:settings.hello_message})}
_this.deleteMessages=function(){$('#messages_cont').html('');chat2.getView().helloMessage()}
_this.minimize=function(){config.setBoxOpened(0);$(".btn_max").show();$(".btn_min").hide();$(".btn_finish").hide("fast");var height=settings.panel_min_height;window.parent.postMessage(["setHeight",height],"*");if(settings.chatbox_type=='circle'){$('.circle_panel').show("slow");$('.normal_panel').hide("slow");$('#chat_online').hide("slow");$('#chat_offline').hide("slow");$(document.body).addClass('transparent_body')}}
_this.maximize=function(){config.setBoxOpened(1);chat2.getView().showHideAskFields();if($("#messages_cont .message").length<2){config.setHasChat(0)}
$(".btn_max").hide();$(".btn_min").show();if($("#chat_online:visible").length){if(chat2.getIO().is_connected()){$(".btn_finish").show()}}
window.parent.postMessage(["setHeight",default_height],"*");if(settings.chatbox_type=='circle'){$('.normal_panel').show("fast",function(){$('.circle_panel').hide("fast",function(){if(settings.status==1){$('#chat_online').show("slow");$(".btn_finish").show("slow")}else{$('#chat_offline').show("slow")}
$(document.body).removeClass('transparent_body');setMessagesContHeight(default_height)})})}else{setMessagesContHeight(default_height)}
setTimeout(function(){$("#message").focus()},500)}
_this.markInput=function(input,cl){$(input).addClass(cl);$(input).closest('.body_line').find('.label').addClass(cl)}
_this.unmarkInput=function(input,cl){$(input).removeClass(cl);$(input).closest('.body_line').find('.label').removeClass(cl)}
_this.showHideAskFields=function(){if($("#messages_cont .message").length<2){chat2.getView().showAskFields()}else{chat2.getView().hideAskFields()}}
_this.showAskFields=function(){$("#chat_online .ask_name").show();$("#chat_online .ask_email").show()}
_this.hideAskFields=function(){$("#chat_online .ask_name").hide();$("#chat_online .ask_email").hide();setMessagesContHeight()}
function setMessagesContHeight(max_h){max_h=default_height;h=max_h-$('.normal_panel').outerHeight();if(settings.status==1){h-=$("#message").outerHeight()+38;if(0==config.hasChat()&&settings.ask_email!=''){h-=$(".ask_email").outerHeight()+3}
if(0==config.hasChat()&&settings.ask_name!=''){h-=$(".ask_name").outerHeight()+3}}
if(settings.copyright){h-=20}else{h-=5}
$('#messages_cont').css('max-height',h+'px');$('#messages_cont').css('height',h+'px')}
function scrollToLastMessage(){$('#messages_cont').animate({scrollTop:$('#messages_cont').prop("scrollHeight")},500,function(){})}
_this.offline=function(){$('#chat_online').hide();$('#chat_offline').show();$('#chat_afterFinish').hide();$('.btn_finish').css('display','none');setMessagesContHeight()}
_this.online=function(){$('#chat_online').show();$('#chat_offline').hide();$('#chat_afterFinish').hide();$('.btn_finish').css('display','inline');setMessagesContHeight()}
_this.afterFinish=function(){$('#chat_online').hide();$('#chat_after_finish').show();$('.btn_finish').css('display','none');$('#email_for_transcription').focus()}
_this.errorWrongSite=function(){$('#chat_online').hide();$('#chat_offline').hide();$('#error_wrong_site').show();setMessagesContHeight()}}
var Chat2_IO=function(chat2){_this=this;var ws;var is_connected=!1;_this.is_connected=function(){return is_connected}
_this.connect=function(connect_callback){var data=["hash="+chat2.hash,"url="+encodeURIComponent(PAGE_URL),'user_timezone='+settings.user_timezone,'key='+WIDGET_KEY]
ws=io.connect(WS_SERVER_URL+'?'+data.join('&'));ws.on('connection',function(data){is_connected=!0;if(connect_callback){setTimeout(connect_callback,500)}else if(config.boxOpened()==1){$('.btn_finish').show('fast')}});ws.on('disconnect',function(data){is_connected=!1});ws.on('messages',function(data){chat2.io_messages(data);__Observer.publish("io_load_messages__after",data)});ws.on('message',function(data){chat2.io_message(data);__Observer.publish("io_messages__after");chat2.playSound('out')});ws.on('chat',function(data){if(data.event=='finish')
chat2.afterFinish();__Observer.publish("io_chat__finish");chat2.playSound('out')})}
_this.send=function(event,data,callback){ws.emit(event,data);if(callback){callback()}}
_this.disconnect=function(){ws.disconnect()}}
var Chat2_Message=function(){_this=this;_this.direct='';_this.text='';_this.dt=''}
var chat2=new Chat2();$(function(){chat2.init();$(".btn_max").on("click",function(){chat2.getView().maximize()})
$(".btn_min").on("click",function(){chat2.getView().minimize()})
$(".btn_finish").on("click",function(){chat2.getIO().send('finish',{},function(){});chat2.afterFinish()})
$('.panel_title').click(function(e){var target=$(e.target);if(target.is('span')&&!target.hasClass('title')){return!0}
if($(".btn_max:visible").length){chat2.getView().maximize()}else{chat2.getView().minimize()}});$('.circle_panel').click(function(e){var target=$(e.target);if(target.is('span')&&!target.hasClass('title')){return!0}
chat2.getView().maximize()});$('#message_cont textarea').on('keydown',function(e){if(e.which==13){$('#message_cont .btn_send_message').click();return!1}});$('#message_cont input').on('keydown',function(e){if(e.which==13){$('#message_cont .btn_send_message').click();return!1}});__Observer.subscribe('io_messages__after',function(){if(0==config.boxOpened()){chat2.getView().maximize()}});__Observer.subscribe('io_chat__finish',function(){if(0==config.boxOpened()){chat2.getView().maximize()}});__Observer.subscribe('io_load_messages__after',function(data){if(0==config.boxOpened()&&'object'==typeof data&&data.hasOwnProperty('length')&&data.length>0){chat2.getView().maximize()}});window.addEventListener('message',function(e){var eventName=e.data[0];var data=e.data[1];if(PAGE_URL.indexOf(e.origin)==-1){return}
switch(eventName){case 'set_height':chat2.getView().setDefaultHeight(data);break;case 'chat_is_shown':if(undefined!=window.io){chat2.initAfterShowChatbox()}
break;case 'chat_maximize':chat2.getView().maximize();break;case 'chat_minimize':chat2.getView().minimize();break;case 'chat_max_min':if(config.boxOpened()==1){chat2.getView().minimize()}else{chat2.getView().maximize()}
break}},!1)});function registrationSite(){window.parent.postMessage(["registration_site",""],"*");document.getElementById('btn_registration').disabled=!0}