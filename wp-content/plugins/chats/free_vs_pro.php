<?php
//    wp_enqueue_style( 'css-admin-chats-free-vs-pro' , plugins_url( self::$plugin_name . "/assets/free_vs_pro.css" ) );

?>

<link href="<?php echo plugins_url( self::$plugin_name . "/assets/free_vs_pro.css" ); ?>" media="screen" rel="stylesheet" type="text/css">

<table class="wpadm_chat_free_vs_pro_table">
    <thead id="fixed_thead">
    <tr>
        <th style="border-left: none;">
            <?php _e("Service", 'Chats'); ?>
        </th>
        <th style="width: 200px;">
            <?php _e("Pro License", 'Chats'); ?>
        </th>
        <th style="width: 200px;">
            <?php _e("Free License", 'Chats'); ?>
        </th>
    </tr>
    </thead>

    <tbody style="border-bottom: none;">
    <tr>
        <td style="border-left: none;"><div class="service_name"><?php _e("Custom visitor interface", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center"><span class="free">&nbsp;</span></td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Visitor Multilingual interface", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center"><span class="free">&nbsp;</span></td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Administrator Multilingual interface", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center"><span class="free">&nbsp;</span></td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Chat Mobile App", 'Chats'); ?><br><small>(<?php _e("download from Google Play", 'Chats'); ?>)</small></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center"><span class="free">&nbsp;</span></td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Chat Chrome App", 'Chats'); ?><br><small>(<?php _e("download from Google Chrome Web Store", 'Chats'); ?>)</small></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center"><span class="free">&nbsp;</span></td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Sending of chat messages", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center"><span class="free">&nbsp;</span></td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Unlimited chat queries and requests", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center"><span class="free">&nbsp;</span></td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Unlimited websites", 'Chats'); ?><br>(<?php _e("Unlimited number of domains", 'Chats'); ?>)</div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center"><span class="free">&nbsp;</span></td>
    </tr>

    <tr>
        <td><div class="service_name"><?php _e("Simultaneous chats", 'Chats'); ?></div></td>
        <td class="center"><b style="font-size: 16px;"><?php _e("Unlimited", 'Chats'); ?></b></td>
        <td class="center"><b style="font-size: 16px;"><?php _e("Unlimited", 'Chats'); ?></b></td>
    </tr>

    <tr>
        <td><div class="service_name"><?php _e("Chat Operators", 'Chats'); ?></div></td>
        <td class="center"><b style="font-size: 16px;"><?php _e("Unlimited", 'Chats'); ?></b></td>
        <td class="center"><b style="font-size: 16px;"><?php _e("Unlimited", 'Chats'); ?></b></td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Priority of incoming messages for each operator", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center"><?php _e("All the chats messages are <strong>not&nbsp;spread&nbsp;equally</strong> between admin and operators", 'Chats'); ?></td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("The number of simultaneous chats for each operator can be set", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Remove copyright notice \"Powered by\"", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Real time Statistics", 'Chats'); ?> (<?php _e("Visitors online statistics", 'Chats'); ?>)</div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Statistic about visitor", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><span class="prefix">–</span><?php _e("Country", 'Chats'); ?> / <?php _e("City", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><span class="prefix">–</span><?php _e("Browser", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><span class="prefix">–</span><?php _e("Operating system", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><span class="prefix">–</span><?php _e("User on world map", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Detailed Statistics", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("User (customer) detail info can be saved during chat", 'Chats'); ?><br>
                <small>(<?php _e("First and last name, company, address, email, phone, skype, social data like Facebook, Twitter, Google+", 'Chats'); ?>)</small></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Chat Archive messages", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>
    <tr>
        <td><div class="service_name"><?php _e("Chat Offline messages", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>

    <tr>
        <td><div class="service_name"><?php _e("Chat Auto-Answer messages", 'Chats'); ?></div></td>
        <td class="center"><span class="free">&nbsp;</span></td>
        <td class="center">&nbsp;</td>
    </tr>

    <tr>
        <td style="border-bottom:1px solid #CDCDCD;" class="no_vborder"><div class="service_name"><?php _e("Request for visitor's email and name", 'Chats'); ?></div></td>
        <td style="border-bottom:1px solid #CDCDCD;" class="center"><span class="free">&nbsp;</span></td>
        <td style="border-bottom:1px solid #CDCDCD;" class="center">&nbsp;</td>
    </tr>

    </tbody>
</table>