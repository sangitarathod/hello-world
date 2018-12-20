function chats_setStatus(status, status_title, ajaxurl, reload, nonce) {
    var data_post = {
        'action' : 'wpadm_chats_setStatus',
        'status' : status,
        'nonce': nonce
    }

    jQuery('#wp-admin-bar-chats-settings').removeClass('hover');
    jQuery(jQuery('#wp-admin-bar-chats-settings').find("a")[0]).html(status_title);


    jQuery('#wp-admin-bar-chats-settings').removeClass('admin-bar-icon-chats-status-online');
    jQuery('#wp-admin-bar-chats-settings').removeClass('admin-bar-icon-chats-status-offline');
    jQuery('#wp-admin-bar-chats-settings').removeClass('admin-bar-icon-chats-status-hidden');
    jQuery('#wp-admin-bar-chats-settings').addClass('admin-bar-icon-chats-status-' + status);


    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        dataType: 'json',
        data: data_post,
        success: function(data) {
            if (data.hasOwnProperty('result') && data.result == 'success') {
                if (reload == 1) {
                    window.location.reload(true);
                }
            }
        }
    });
}
