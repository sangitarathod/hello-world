<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );?>

<?php if(!empty($personalKey)){ ?>
<?php $shortcode = new WPAdm_Chats_Shortcode(1); ?>
    <div id="chats_shortcode_tab" class="tab">
        <table class="form-table">
            <thead>
            <tr class="row_title">
                <th scope="row" colspan="2">
                    <?php _e("Shortcode", 'Chats');?> [wpadm-chat]
                    <span onclick="showFullContent(this);"  class="chats_tab_action">&nbsp;</span>
                </th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">

                        <?php
                            $shortcode->form()
                        ?>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
<?php } ?>
