<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$class_button_preview = uniqid('preview');

$id_preview = uniqid('preview');
$id_button = $this->get_field_id( 'btn' );

$class_link_preview = uniqid( 'preview_link' );

$class_hover = uniqid( 'hover' );

$id_link = $this->get_field_id( 'link' );

$grp_type = uniqid( 'grp_type' );

$id_text = $this->get_field_id( 'text' );

$id_background = $this->get_field_id( 'background' );
$id_color = $this->get_field_id( 'color' );
$id_bordercolor = $this->get_field_id( 'bordercolor' );
$id_border_radius = $this->get_field_id( 'border_radius' );
$id_shadow_color = $this->get_field_id( 'shadow_color' );
$id_shadow_size = $this->get_field_id( 'shadow_size' );

$id_hover = $this->get_field_id( 'hover' );
$id_hover_background = $this->get_field_id( 'hover_background' );
$id_hover_color = $this->get_field_id( 'hover_color' );
$id_hover_bordercolor = $this->get_field_id( 'hover_bordercolor' );
$id_hover_shadow_color = $this->get_field_id( 'hover_shadow_color' );

$id_classname = $this->get_field_id( 'classname' );

$function = uniqid('fun');

$html = file_get_contents(home_url());
preg_match_all("|<link.*/>|Ui", $html, $m);

$styles = $m[0];

$iframe =  "<!DOCTYPE html><html><head>".str_replace('"', "'", implode("\n", $styles))."</head><body style='overflow:hidden;'></body></html>";

?>

<table style="border: none;" class="wpadm_chats_widget_form">
    <tr>
        <td style="text-align: right; margin-right: 5px; vertical-align: top;">
            <label><?php _e('Type', 'Chats'); ?>:</label>
        </td>
        <td>
            <span id="<?php echo $grp_type;?>">
                <label><input type=radio name="<?php echo $this->get_field_name( 'type' ); ?>" value="button" <?php if($instance['type'] == 'button') {echo 'checked';}?> onclick="<?php echo $function; ?>()"> <?php _e('Button', 'Chats'); ?></label>
                <br><label><input type=radio name="<?php echo $this->get_field_name( 'type' ); ?>" value="link" <?php if($instance['type'] == 'link') {echo 'checked';}?> onclick="<?php echo $function; ?>()"> <?php _e('Link', 'Chats'); ?></label>
            </span>
        </td>

        <?php if (!preg_match("|customize\.php$|Uis", $_SERVER['SCRIPT_FILENAME'])) { ?>
            <td style="padding-left: 15px; vertical-align: top; border-left: 1px solid #d8d3d3;" rowspan="5">
                <?php _e('Preview', 'Chats'); ?>:<br>

                <div style="display: none;">
                <span id="<?php echo $id_preview; ?>_cont">
                    <div class="<?php echo $class_button_preview; ?>" style="padding: 10px; display: none; margin: 5px;">
                        <button type="button" id="<?php echo $id_button;?>"><?php echo $instance['text']; ?></button>
                    </div>

                    <div class="<?php echo $class_link_preview; ?>" style="padding: 10px; display: none; margin: 5px;">
                        <a href="#chat"><span id="<?php echo $id_link;?>"><?php echo $instance['text']; ?></span></a>
                    </div>
                </span>
                </div>
                <iframe id="<?php echo $id_preview; ?>" srcdoc="<?php echo $iframe; ?>" style="display: none; width: 100%; max-width: 130px; height: 100px; border: none; overflow: hidden" scrolling="no" ></iframe>
            </td>



        <?php } ?>

    </tr>

    <tr>
        <td style="text-align: right; margin-right: 5px; width: 50px;">
            <label for=<?php echo $id_text; ?> > <?php _e('Text', 'Chats'); ?>:</label>
        </td>
        <td style="padding-right: 15px;">
            <input id="<?php echo $id_text; ?>" style="width: 96px" type="text" name="<?php echo $this->get_field_name( 'text' ); ?>" value="<?php echo $instance['text']; ?>" onkeyup="<?php echo $function; ?>();">
        </td>
    </tr>
    <tr>
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_classname; ?> > <?php _e('Class name', 'Chats'); ?>:</label></td>
        <td><input type="text" id="<?php echo $id_classname;?>"  style="width: 96px" name="<?php echo $this->get_field_name( 'classname' ); ?>" value="<?php echo $instance['classname']; ?>" ></td>
    </tr>

    <tr>
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_color; ?> > <?php _e('Color', 'Chats'); ?>:</label></td>
        <td><input class="settings_colorpicker" type="text" id="<?php echo $id_color ?>" name="<?php echo $this->get_field_name( 'color' ); ?>" value="<?php echo $instance['color']; ?>"  onchange="<?php echo $function; ?>()"/></td>
    </tr>

    <tr class="<?php echo $class_button_preview; ?>">
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_background; ?> > <?php _e('Background', 'Chats'); ?>:</label></td>
        <td><input class="settings_colorpicker" type="text" id="<?php echo $id_background ?>" name="<?php echo $this->get_field_name( 'background' ); ?>" value="<?php echo $instance['background']; ?>"  onchange="<?php echo $function; ?>()"/></td>
    </tr>
    <tr class="<?php echo $class_button_preview; ?>">
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_bordercolor; ?> > <?php _e('Border color', 'Chats'); ?>:</label></td>
        <td><input class="settings_colorpicker" type="text" id="<?php echo $id_bordercolor ?>" name="<?php echo $this->get_field_name( 'bordercolor' ); ?>" value="<?php echo $instance['bordercolor']; ?>"  onchange="<?php echo $function; ?>()"/></td>
    </tr>

    <tr class="<?php echo $class_button_preview; ?>">
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_border_radius; ?> > <?php _e('Border radius', 'Chats'); ?>:</label></td>
        <td>
            <input type="number" min="0" id="<?php echo $id_border_radius ?>" style="width: 96px;" name="<?php echo $this->get_field_name( 'border_radius' ); ?>" value="<?php echo $instance['border_radius']; ?>"  onchange="<?php echo $function; ?>()"/>
            <div id="slider_<?php echo $id_border_radius; ?>"></div>
        </td>
    </tr>

    <tr class="<?php echo $class_button_preview; ?>">
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_shadow_color; ?> > <?php _e('Shadow color', 'Chats'); ?>:</label></td>
        <td><input class="settings_colorpicker" type="text" id="<?php echo $id_shadow_color ?>" name="<?php echo $this->get_field_name( 'shadow_color' ); ?>" value="<?php echo $instance['shadow_color']; ?>"  onchange="<?php echo $function; ?>()"/></td>
    </tr>


    <tr class="<?php echo $class_button_preview; ?>">
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_shadow_size; ?> > <?php _e('Shadow size', 'Chats'); ?>:</label></td>
        <td><input type="number" min="0" id="<?php echo $id_shadow_size ?>"  style="width: 96px;" name="<?php echo $this->get_field_name( 'shadow_size' ); ?>" value="<?php echo $instance['shadow_size']; ?>"  onchange="<?php echo $function; ?>()"/></td>
    </tr>

    <tr><td colspan="3" style="padding-top: 10px;"></td></tr>

    <tr>
        <td></td><td colspan="2"> <label><input type="checkbox" id="<?php echo $id_hover; ?>" name="<?php echo $this->get_field_name( 'hover' ); ?>" value="1" onclick="<?php echo $function; ?>()" <?php if ($instance['hover'] == 1) {echo 'checked';} ?>> <?php _e('On hover', 'Chats'); ?></label></td>
    </tr>
    <tr class="<?php echo $class_hover;?>">
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_hover_color; ?> > <?php _e('Color', 'Chats'); ?>:</label></td>
        <td><input class="settings_colorpicker" type="text" id="<?php echo $id_hover_color ?>" name="<?php echo $this->get_field_name( 'hover_color' ); ?>" value="<?php echo $instance['hover_color']; ?>"  onchange="<?php echo $function; ?>()"/></td>
    </tr>
    <tr class="<?php echo $class_hover . ' ' . $class_button_preview; ?>">
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_hover_background; ?> > <?php _e('Background', 'Chats'); ?>:</label></td>
        <td><input class="settings_colorpicker" type="text" id="<?php echo $id_hover_background ?>" name="<?php echo $this->get_field_name( 'hover_background' ); ?>" value="<?php echo $instance['hover_background']; ?>"  onchange="<?php echo $function; ?>()"/></td>
    </tr>
    <tr class="<?php echo $class_hover . ' ' . $class_button_preview; ?>">
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_hover_bordercolor; ?> > <?php _e('Border color', 'Chats'); ?>:</label></td>
        <td><input class="settings_colorpicker" type="text" id="<?php echo $id_hover_bordercolor ?>" name="<?php echo $this->get_field_name( 'hover_bordercolor' ); ?>" value="<?php echo $instance['hover_bordercolor']; ?>"  onchange="<?php echo $function; ?>()"/></td>
    </tr>
    <tr class="<?php echo $class_hover . ' ' . $class_button_preview; ?>">
        <td style="text-align: right; margin-right: 5px;"><label for=<?php echo $id_hover_shadow_color; ?> > <?php _e('Shadow color', 'Chats'); ?>:</label></td>
        <td><input class="settings_colorpicker" type="text" id="<?php echo $id_hover_shadow_color ?>" name="<?php echo $this->get_field_name( 'hover_shadow_color' ); ?>" value="<?php echo $instance['hover_shadow_color']; ?>"  onchange="<?php echo $function; ?>()"/></td>
    </tr>

    <tr><td colspan="3" style="padding-top: 10px;"></td></tr>


</table>
</p>

<script>
    jQuery(document).ready( function() {
        <?php echo $function; ?>();

        jQuery.each(jQuery('.settings_colorpicker'), function() {
            jQuery(this).minicolors({
                defaultValue: jQuery(this).attr('data-defaultValue') || '',
                inline: jQuery(this).attr('data-inline') === 'true',
                letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
                position: jQuery(this).attr('data-position') || 'bottom left',
                theme: 'default'
            });

            <?php if (!preg_match("|customize\.php$|Uis", $_SERVER['SCRIPT_FILENAME'])) {
                    echo $function . '_make_preview()';
                }
            ?>

        });

//        jQuery('#slider_<?php //echo $id_border_radius; ?>//').slider();

    })

    function <?php echo $function; ?>() {

        var hover = (jQuery('#<?php echo $id_hover; ?>').attr('checked') == 'checked');
        var type = jQuery("#<?php echo $grp_type; ?> input[type='radio']:checked").val();
        var class_button_preview = '<?php echo $class_button_preview ?>';
        var class_hover = '<?php echo $class_hover ?>';

        var $link_preview = jQuery(".<?php echo $class_link_preview; ?>");
        var $button_preview = jQuery(".<?php echo $class_button_preview; ?>");

        var $link_preview2 = jQuery('#<?php echo $id_preview; ?>').contents().find(".<?php echo $class_link_preview; ?>");
        var $button_preview2 = jQuery('#<?php echo $id_preview; ?>').contents().find(".<?php echo $class_button_preview; ?>");


        if (type == 'button') {
            $link_preview.hide();
            $button_preview.show();
            $link_preview2.hide();
            $button_preview2.show();
        }

        if (type == 'link') {
            $link_preview.show();
            $button_preview.hide();
            $link_preview2.show();
            $button_preview2.hide();
        }

        jQuery('#<?php echo $id_preview; ?>').contents().find('.<?php echo $class_hover; ?>').each(function() {
            if (hover &&
                (
                    (type == 'link' && !jQuery(this).hasClass(class_button_preview))
                    || type == 'button'
                )
            ) {
                jQuery(this).show();
            } else {
                jQuery(this).hide();
            }
        })

        <?php if (preg_match("|customize\.php$|Uis", $_SERVER['SCRIPT_FILENAME'])) {
            echo "return;";
        }
        ?>


//        var btn = jQuery('#<?php //echo $id_button ;?>//');
//        var link = jQuery('#<?php //echo $id_link ;?>//');
        var btn = jQuery('#<?php echo $id_preview; ?>').contents().find('#<?php echo $id_button ;?>');
        var link = jQuery('#<?php echo $id_preview; ?>').contents().find('#<?php echo $id_link ;?>');


        var txt = jQuery('#<?php echo $id_text ;?>').val();

        var background = jQuery('#<?php echo $id_background; ?>').val();
        var color = jQuery('#<?php echo $id_color; ?>').val();
        var bordercolor = jQuery('#<?php echo $id_bordercolor; ?>').val();

        link.html(txt);
        btn.html(txt);

        var shadow_size = parseInt(jQuery('#<?php echo $id_shadow_size; ?>').val());
        var shadow_color = jQuery('#<?php echo $id_shadow_color; ?>').val();


        setCss();

        if(hover) {
            btn.mouseover(function() {
                setHoverCss();
            }).mouseout(function() {
                setCss();
            });
            link.mouseover(function() {
                setHoverCss();
            }).mouseout(function() {
                setCss();
            });
        } else {
            btn.unbind('mouseover').unbind('mouseout');
            link.unbind('mouseover').unbind('mouseout');
        }

        function setCss() {
            btn.css('background-color', background);
            btn.css('color', color);
            link.css('color', color);
            if (bordercolor) {
                btn.css('border', "1px solid " + bordercolor);
            } else {
                btn.css('border', "");
            }

            btn.css('border-radius', jQuery('#<?php echo $id_border_radius; ?>').val() + 'px');

            if (shadow_size && shadow_color) {
                btn.css('box-shadow', String(shadow_size) + "px "+ String(shadow_size) + "px 10px 0px " + shadow_color);
            } else {
                btn.css('box-shadow', "0px 0px 0px 0px white");
            }

        }

        function setHoverCss() {
            var hover_background = jQuery('#<?php echo $id_hover_background; ?>').val();
            var hover_color = jQuery('#<?php echo $id_hover_color; ?>').val();
            var hover_bordercolor = jQuery('#<?php echo $id_hover_bordercolor; ?>').val();

            if (hover_color) {
                link.css('color', hover_color);
                btn.css('color', hover_color);
            }
            if (hover_background) {
                btn.css('background-color', hover_background);
            }

            if (hover_bordercolor) {
                btn.css('border', "1px solid " + hover_bordercolor);
            }
            var hover_shadow_color = jQuery('#<?php echo $id_hover_shadow_color; ?>').val();
            if (shadow_size && hover_shadow_color) {
                btn.css('box-shadow', String(shadow_size) + "px "+ String(shadow_size) + "px 10px 0px " + hover_shadow_color);
            }

        }
    }

    function <?php echo $function; ?>_make_preview() {

        var body = jQuery('#<?php echo $id_preview; ?>').contents().find('body');
        body.html(jQuery("#<?php echo $id_preview; ?>_cont").html());
        body.css('background-color', 'transparent');
//        body.css('text-align', 'center');



        var btn = jQuery('#<?php echo $id_preview; ?>').contents().find('#<?php echo $id_button ;?>');
        var link = jQuery('#<?php echo $id_preview; ?>').contents().find('#<?php echo $id_link ;?>');
        btn.css('position', 'absolute');
        btn.css('top', '10px');
        btn.css('left', '10px');


        <?php echo $function; ?>();

        jQuery('#<?php echo $id_preview; ?>').show();

    }


</script>