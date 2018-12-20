<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );?>

<?php if(!empty($personalKey)){ ?>
    <div id="call_chatbox_tab" style class="tab">
        <table class="form-table">
            <thead>
            <tr class="row_title">
                <th scope="row" colspan="2">
                    <?php _e("Call of chat-box", 'Chats');?>
                    <span onclick="showFullContent(this);"  class="chats_tab_action">&nbsp;</span>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <div style="font-weight: 14px; max-width: 854px;">
                        <strong><?php _e("Several ways to add to the website interface an element for opening and closing of chat-box:", 'Chats'); ?></strong>
                        <ol>
                            <li>
                                <?php
                                $s = __('Adding of bookmark-link: "%s1" - for opening of chat-box, "%s2" - and for closing of chat-box or just "%s3"(for opening and closing of chat-box within one bookmark-link).', 'Chats');
                                $s = str_replace(
                                    array('%s1','%s2','%s3'),
                                    array('<i>#chat_open</i>', '<i>#chat_close</i>', '<i>#chat</i>'),
                                    $s
                                );
                                echo $s;
                                unset($s);
                                ?>
                                <br><?php _e('For example', 'Chats'); ?>, <i>&lt;a href="#chat"&gt;Chat&lt;/a&gt;</i>
                                <hr>
                            </li>
                            <li>
                                <?php _e('To add as website menu item:', 'Chats'); ?>
                                <ul>
                                    <?php
                                    $s1 = __('select a page "%s1", "%s2" or "%s3" from the list "%s4"', 'Chats');
                                    $s1 = str_replace(
                                        array('%s1', '%s2', '%s3', '%s4'),
                                        array('<i>#wpadm-chat-open</i>', '<i>#wpadm-chat-close</i>', '<i>#wpadm-chat</i>', __('Pages') . '. ' .__('Most Recent')),
                                        $s1
                                    );
                                    echo "<li>- {$s1}</li>";
                                    unset($s1);

                                    $s2 = 'add a custom link and use this values as address "%s1", "%s2" or "%s3"';
                                    $s2 = str_replace(
                                        array('%s1', '%s2', '%s3'),
                                        array('<i>#chat_open</i>', '<i>#chat_close</i>', '<i>#chat</i>'),
                                        $s2
                                    );
                                    echo "<li>- {$s2}</li>";
                                    unset($s2);
                                    ?>
                                </ul>

                                <a href="<?php echo admin_url('nav-menus.php'); ?>" class="button button-primary"><?php _e('Menus'); ?></a>

                                <hr>
                            </li>
                            <li>
                                <?php _e('To add as a website widget "WPAdm Chat Control". In this case, it is possible to customize the appearance of the widget', 'Chats'); ?>
                                <br>
                                <a href="<?php echo admin_url('widgets.php'); ?>" class="button button-primary"><?php _e('Widgets'); ?></a>
                                <hr>
                            </li>
                            <li>
                                <?php
                                $s3 = __('During website theme development use the js-methods %s1, %s2 and %s3', 'Chats');
                                $s3 = str_replace(
                                    array('%s1', '%s2', '%s3'),
                                    array('<i>wpadm_chat_open()</i>','<i>wpadm_chat_close()</i>','<i>wpadm_chat()</i>'),
                                    $s3
                                );
                                echo $s3;
                                unset($s3);
                                ?>
                                <hr>
                            </li>

                            <li>
                                <?php
                                $s4 = __('Use shortcode $ to insert as button or link in the template(theme) of your site', 'Chats');
                                $s4 = str_replace('$', '<input onclick="this.select(); return false;" onkeydown="return false;"  style="background-color: yellow; color: black; padding: 0px 5px 0px 5px;" value="[wpadm-chat]">', $s4);
                                echo $s4;
                                ?>
                                <br>
                                <a id="shortcode-wpadm-chat" href="#shortcode-wpadm-chat" class="button button-primary" onclick="jQuery('#chats_shortcode_tab .chats_tab_action').click()"><?php _e('Settings'); ?>  shortcode</a>
                            </li>

                        </ol>

                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    </div>
<?php } ?>
