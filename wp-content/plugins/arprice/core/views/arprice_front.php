<?php

if (!function_exists('arp_get_pricing_table_string')) {

    function arp_get_pricing_table_string($table_id, $pricetable_name = "", $is_tbl_preview = 0) {

        global $font_awesome_match;
        $font_awesome_match = array();
        $arp_inc_effect_css = array();
        global $arp_is_lightbox;
        $arp_load_js_css = get_option('arp_load_js_css');

        wp_enqueue_style('arprice_front_css');

        global $wpdb, $arprice_form, $arp_mainoptionsarr, $arp_pricingtable, $arp_templateresponsivearr, $arp_template_column_radius, $arprice_version, $arprice_images_css_version, $arprice_default_settings, $arprice_old_template_css, $arp_animate_price,$arprice_fonts,$wp_upload_dir;
        $arp_responsive_arr = $arprice_default_settings->arprice_responsive_width_array();
        $arp_mainoptionsarr = $arp_pricingtable->arp_mainoptions();
        
        $arp_col_wrapper_height = $arprice_default_settings->arprice_column_wrapper_height();
        $arp_col_wrapper_highlighted_height = $arprice_default_settings->arprice_default_highlighted_column_height_with_hover_effect();
        $arp_col_wrapper_default_height = $arprice_default_settings->arprice_column_wrapper_default_height();

        $total_tabs = $arp_pricingtable->arp_toggle_step_name();

        $id = $table_id;
        $name = $pricetable_name;
        $caption_array = array();
        if ($is_tbl_preview && $is_tbl_preview == 1) {
            if (isset($_REQUEST['optid']) && $_REQUEST['optid'] != '') {
                $post_values = get_option($_REQUEST['optid']);
                $post_values = json_decode(stripslashes_deep($post_values['filtered_data']), true);
                
                $get_preview_data = $arprice_form->get_preview_table($post_values);
                
                $id = $table_id = $get_preview_data['table_id'];
                $is_template = $get_preview_data['is_template'];
                $is_animated = $get_preview_data['is_animated'];
                
                $opts = maybe_unserialize($get_preview_data['table_options']);
                $general_option = maybe_unserialize($get_preview_data['general_options']);

                $arp_template_name = $get_preview_data['template_name'];
            }
        } else {

            if( isset($GLOBALS['arp_data']) && isset($GLOBALS['arp_data'][$id]) ){
                $sql = $GLOBALS['arp_data'][$id];
            } else {
                $sql = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arp_arprice WHERE ID = %d AND status = %s ", $id, 'published'));
                $GLOBALS['arp_data'][$id] = $sql;
            }
            $table_id = $sql->ID;
            
            if( isset($GLOBALS['arp_opt_data']) && isset($GLOBALS['arp_opt_data'][$id]) ){
                $sql_opt = $GLOBALS['arp_opt_data'][$id];
            } else {
                $sql_opt = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "arp_arprice_options WHERE table_id = %d ", $id));
                $GLOBALS['arp_opt_data'][$id] = $sql_opt;
            }
            $is_template = $sql->is_template;
            $is_animated = $sql->is_animated;
            $opts = maybe_unserialize($sql_opt->table_options);
            $general_option = maybe_unserialize($sql->general_options);
            $arp_template_name = $sql->template_name;
            
        }



        $table_cols = array();
        $table_cols = $table_cols_new = $opts['columns'];

        if( isset($arp_load_js_css) && $arp_load_js_css == 'arp_load_js_css' ){
            wp_print_scripts('arprice_js');
            wp_print_scripts('arprice_slider_js');
            wp_print_scripts('arp_tooltip_front');
            wp_print_scripts('arp_animate_numbers');
        }

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $maxrowcount = 0;
        if (is_array($table_cols)) {
            foreach ($table_cols as $countcol) {
                if (isset($countcol['rows']) && count($countcol['rows']) > $maxrowcount)
                    $maxrowcount = count($countcol['rows']);
            }
            $maxrowcount--;
        }

        $arp_tablet_view_width = $arp_mainoptionsarr['general_options']['template_options']['arp_tablet_view_width'];

        $opts['columns'] = $table_cols;

        $column_animation = $general_option['column_animation'];

        $is_animation = $column_animation['is_animation'];

        $column_settings = $general_option['column_settings'];

        $hover_type = $column_settings['column_highlight_on_hover'];


        $enable_hover_effect = isset( $column_settings['enable_hover_effect'] ) ? $column_settings['enable_hover_effect'] : 0;
        $arp_global_button_class = '';
        $arp_global_button_type = isset($column_settings['arp_global_button_type']) ? $column_settings['arp_global_button_type'] : 'flat';
        $arp_global_button_class_array = $arprice_default_settings->arp_button_type();
        $arp_global_button_size = $arprice_default_settings->arp_button_size_new();

        if ( !isset($column_settings['enable_button_hover_effect']) || $column_settings['enable_button_hover_effect'] != 1) {
            $arp_global_button_class = @$arp_global_button_class_array[$arp_global_button_type]['class'] . ' arp_button_hover_disable';
        } else {
            $arp_global_button_class = @$arp_global_button_class_array[$arp_global_button_type]['class'];
        }


        $template_settings = $general_option['template_setting'];

        $general_settings = $general_option['general_settings'];

        $template_type = $template_settings['template_type'];

        $template = $template_settings['template'];

        $template_id = $template_settings['template'];

        $ref_template = $general_settings['reference_template'];

        $is_responsive = $general_option['column_settings']['is_responsive'];

        $template_feature = $arp_mainoptionsarr['general_options']['template_options']['features'][$ref_template];

        if(isset($template_feature['amount_style']) && $template_feature['amount_style']=='default'){
            $template_feature['amount_style'] = 'arp_default';
        }

        $hide_blank_row = isset($general_option['column_settings']['column_hide_blank_rows']) ? $general_option['column_settings']['column_hide_blank_rows'] : '';

        $informative_cls = '';
        if ($is_tbl_preview == 1 || ( isset($_REQUEST['home_view']) && $_REQUEST['home_view'] == 1 )) {
            if ($is_template == 1) {
                do_action('enqueue_preview_style', $arp_template_name, $arp_template_name, 0, $is_template);
            } else {
                do_action('enqueue_preview_style', $id, $id, 0, $is_template);
            }
        }

        
        global $arprice_class;
        $setact = 0;
        global $arp_chk_version;
        $setact = $arprice_class->$arp_chk_version();
        

        if ($is_tbl_preview != 0) {
            $setact = 1;
        }

        global $arp_has_tooltip;
        if ($setact != 1) {
            $arp_has_tooltip = 1;
        }


        $tablestring = "";

        $tablestring .= $arprice_form->arp_load_js_css($table_id, $is_template);

        if ($column_settings['column_border_radius_top_left'] > 0 or $column_settings['column_border_radius_top_right'] > 0 or $column_settings['column_border_radius_bottom_right'] > 0 or $column_settings['column_border_radius_bottom_left'] > 0) {

            $tablestring .= "<style type='text/css' media='all'>";

            if ($is_animated == 0) {

                if ($ref_template == 'arptemplate_10')
                    $tablestring .= ".arptemplate_$table_id .ArpPricingTableColumnWrapper .arpplan {";
                else
                    $tablestring .= ".arptemplate_$table_id .arp_column_content_wrapper {";

                $tablestring .= "border-radius:{$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px {$column_settings['column_border_radius_bottom_right']}px {$column_settings['column_border_radius_bottom_left']}px !important;";

                $tablestring .= " -moz-border-radius: {$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px {$column_settings['column_border_radius_bottom_right']}px {$column_settings['column_border_radius_bottom_left']}px !important;";

                $tablestring .= "-webkit-border-radius:{$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px {$column_settings['column_border_radius_bottom_right']}px {$column_settings['column_border_radius_bottom_left']}px !important;";

                $tablestring .= "-o-border-radius: {$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px {$column_settings['column_border_radius_bottom_right']}px {$column_settings['column_border_radius_bottom_left']}px !important;";

                if ($ref_template != 'arptemplate_10') {
                    $tablestring .= "overflow:hidden !important;";
                }

                $tablestring .= "}";

                if ($ref_template == 'arptemplate_6' or $ref_template == 'arptemplate_9') {

                    $tablestring .= ".arptemplate_$table_id .maincaptioncolumn .arpcaptiontitle {";

                    $tablestring .= " border-radius:{$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px 0 0 !important;";

                    $tablestring .= " -webkit-border-radius:{$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px 0 0 !important;";

                    $tablestring .= " -moz-border-radius:{$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px 0 0 !important;";

                    $tablestring .= " -o-border-radius:{$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px 0 0 !important;";

                    $tablestring .= "}";
                }
            } else {

                $tablestring .= ".arptemplate_$table_id .ArpPricingTableColumnWrapper { ";

                $tablestring .= "border-radius:{$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px {$column_settings['column_border_radius_bottom_right']}px {$column_settings['column_border_radius_bottom_left']}px !important;overflow:hidden !important;";

                $tablestring .= " -moz-border-radius: {$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px {$column_settings['column_border_radius_bottom_right']}px {$column_settings['column_border_radius_bottom_left']}px !important;overflow:hidden !important;";

                $tablestring .= "-webkit-border-radius:{$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px {$column_settings['column_border_radius_bottom_right']}px {$column_settings['column_border_radius_bottom_left']}px !important;overflow:hidden !important;";

                $tablestring .= "-o-border-radius: {$column_settings['column_border_radius_top_left']}px {$column_settings['column_border_radius_top_right']}px {$column_settings['column_border_radius_bottom_right']}px {$column_settings['column_border_radius_bottom_left']}px !important;overflow:hidden !important;";

                $tablestring .= "}";
            }

            $tablestring .= "</style>";
        }


        $title_cls = "";

        if ($column_animation['is_animation'] == 'yes') {
            $animation_margin = 'margin-bottom:45px;';
        } else {
            $animation_margin = '';
        }

        
        do_action('arp_predisplay_pt_action', $table_id);
        do_action('arp_predisplay_pt_action' . $table_id, $table_id);

        $arp_unique_table_id = rand(1, 99999);

        $tablestring = apply_filters('arp_predisplay_pricingtable_filter', $tablestring, $table_id);


        if ($column_animation['is_animation'] == 'yes' and $column_animation['pagi_nav_btn'] == 'pagination_top'){
            $tablestring .= "<div id='arp_pagination_wrapper'><div class='arp_pagination arp_pagination_top arp_paging_style_1' id='arp_slider_" . $id . "_paginatio_top_".$arp_unique_table_id."'></div></div>";
        }



        if ($column_settings['column_wrapper_width_txtbox'] != '') {
            $container_width = $column_settings['column_wrapper_width_txtbox'] . 'px;';
        } else {
            $container_width = $arp_mainoptionsarr['general_options']['wrapper_width'] . 'px;';
        }
        $cart_url = '';

        if (is_plugin_active('woocommerce/woocommerce.php')) {

            global $woocommerce;
            if (!empty($woocommerce) && method_exists($woocommerce->cart, 'get_cart_url')) {
                $cart_url = @$woocommerce->cart->get_cart_url();
                if (( isset($_GET['added-to-cart']) and ! empty($_GET['added-to-cart']) ) or ( isset($_GET['add-to-cart']) and ! empty($_GET['add-to-cart']) )) {
                    $tablestring .= "<input type='hidden' id='arp_carturl' name='arp_carturl' value='" . $cart_url . "' />";
                }
            }
        }

        $display_column_mobile = isset($column_settings['display_col_mobile']) ? $column_settings['display_col_mobile'] : '';
        $display_column_tablet = isset($column_settings['display_col_tablet']) ? $column_settings['display_col_tablet'] : '';
        $display_column_desktop = isset($column_settings['display_col_desktop']) ? $column_settings['display_col_desktop'] : '';

        $general_option['column_settings']['toggle_column_animation'] = isset($general_option['column_settings']['toggle_column_animation']) ? $general_option['column_settings']['toggle_column_animation'] : '';
        $cart_url = isset($cart_url) ? $cart_url : '';
        
        if ($is_template == 1) {
            $template_name = $arp_template_name;
        } else {
            $template_name = $table_id;
        }
        
        $tltp_bgcolor = $arprice_form->hex2rgb($general_option['tooltip_settings']['background_color']);


        if ($general_option['tooltip_settings']['style'] == 'normal' || $general_option['tooltip_settings']['style'] == 'drop') {
            $tooltip_bg_color = 'rgb(' . $tltp_bgcolor['red'] . ',' . $tltp_bgcolor['green'] . ',' . $tltp_bgcolor['blue'] . ')';
        } else if ($general_option['tooltip_settings']['style'] == 'glass') {
            $tooltip_bg_color = 'rgba(' . $tltp_bgcolor['red'] . ',' . $tltp_bgcolor['green'] . ',' . $tltp_bgcolor['blue'] . ',0.9)';
        } else if ($general_option['tooltip_settings']['style'] == 'alert') {
            $tooltip_bg_color = 'rgba(' . $tltp_bgcolor['red'] . ',' . $tltp_bgcolor['green'] . ',' . $tltp_bgcolor['blue'] . ',0.7)';
        }

        if ($general_option['tooltip_settings']['tooltip_width'] == '')
            $tooltip_max_width = 'null';
        else
            $tooltip_max_width = $general_option['tooltip_settings']['tooltip_width'];

        if ($general_option['tooltip_settings']['animation']) {
            $tooltip_animation = $general_option['tooltip_settings']['animation'];
        } else {
            $tooltip_animation = 'grow';
        }

        $tooltip_option = "";

        $animation_settings = "";

        $tooltip_width = $general_option['tooltip_settings']['tooltip_width'];

        $default_tooltip_width = $arp_mainoptionsarr['general_options']['tooltipsetting']['width'];

        if ($tooltip_width == '' or $tooltip_width == 0) {
            
            if ($default_tooltip_width == '') {
                $tooltip_width = '\'auto\'';
            } else {
                $tooltip_width = $default_tooltip_width;
            }
        } else {

            $tooltip_width = $tooltip_width;
        }


       
        if ($tooltip_animation == 'grow') {
            $animation_in = "zoomIn";
            $animation_out = "zoomOut";
        } else if ($tooltip_animation == 'fade') {
            $animation_in = "fadeIn";
            $animation_out = "fadeOut";
        } else if ($tooltip_animation == 'swing') {
            $animation_in = "swing";
            $animation_out = "fadeOut";
        } else if ($tooltip_animation == 'slide') {
            $animation_in = "fadeInLeftBig";
            $animation_out = "fadeOutLeftBig";
        } else if ($tooltip_animation == 'fall') {
            $animation_in = "fadeInDownBig";
            $animation_out = "fadeOutUpBig";
        }

        $tooltip_trigger_type = $general_option['tooltip_settings']['tooltip_trigger_type'];

        $tooltip_icon_position = isset($general_option['tooltip_settings']['tooltip_icon_position']) ? $general_option['tooltip_settings']['tooltip_icon_position'] : '';

        $tooltip_display_style = $general_option['tooltip_settings']['tooltip_display_style'];
        if( $tooltip_display_style != 'default' ){
            $tooltip_informative_icon = $general_option['tooltip_settings']['tooltip_informative_icon'];
        } else {
            $tooltip_informative_icon = '';
        }

        
        
        $tablestring .= "<div class='arp_template_main_container' id='arp_template_main_container' style='width:{$container_width}text-align:center;' data-hide-blank-rows='{$hide_blank_row}' data-is-tempalte='{$is_template}' data-woocomerce-cart-url='{$cart_url}' data-is-display-tooltip='{$setact}' data-mobile-width='" . get_option('arp_mobile_responsive_size') . "' data-is-responsive='{$general_option['column_settings']['is_responsive']}' data-is-animated='{$is_animated}' data-arp-template='arptemplate_{$table_id}' data-arp-uniq-id='{$arp_unique_table_id}' data-template-type='{$template_type}' data-table-preview='{$is_tbl_preview}' data-reference-template='{$ref_template}' data-hover-type='{$hover_type}' data-column-mobile='{$display_column_mobile}' data-column-tablet='{$display_column_tablet}' data-column-desktop='{$display_column_desktop}' data-all-column-width='{$column_settings['all_column_width']}' data-tablet-width='" . get_option('arp_tablet_responsive_size') . "' data-space-columns='{$column_settings['column_space']}' data-responsive-width-arr='" . json_encode($arp_responsive_arr[$ref_template]) . "' data-column-wrapper-width-arr='" . json_encode($arp_col_wrapper_height[$ref_template]) . "' data-is-price-animation='{$general_option['column_settings']['toggle_column_animation']}' data-column-wrapper-highlighted-height='".json_encode($arp_col_wrapper_highlighted_height[$ref_template])."' data-column-wrapper-default-height='".json_encode($arp_col_wrapper_default_height[$ref_template])."'  data-enable-loader='".get_option('arp_enable_loader',0)."'  data-enable-analytics='".get_option('arp_enable_analytics',0)."'>";

        $template_main_css = "";
        if( isset($is_tbl_preview) && ($is_tbl_preview == 1 || $is_tbl_preview == 2) ){
            $template_main_css = "padding-bottom:40px;";
        }

        $front_main_container_class = '';
        if( isset( $is_animation ) && 'yes' == $is_animation ){
            $front_main_container_class = 'arp_front_main_container_animated';
        }

        $tablestring .= "<div class='ArpTemplate_main arp_front_main_container ".$front_main_container_class."' id=\"ArpTemplate_main\" style='" . $animation_margin . $template_main_css. "'>";
        
        
        $tablestring .= "<input type='hidden' id='arp_ajaxurl' name='arp_ajaxurl' value='" . admin_url('admin-ajax.php') . "' />";
        
        $column_ord = str_replace('\'', '"', $general_settings['column_order']);

        $col_ord_arr = json_decode($column_ord, true);

        if ($is_tbl_preview == 1 || $is_tbl_preview == 2) {
            if ($ref_template == 'arptemplate_5' || $ref_template == 'arptemplate_7') {
                $googlemap = 0;
                if ($opts['columns']) {
                    foreach ($opts['columns'] as $columns) {

                        $columns['is_new_window_actual'] = isset($columns['is_new_window_actual']) ? $columns['is_new_window_actual'] : '';

                        $html_content_shortcode = isset($columns['arp_header_shortcode']) ? $columns['arp_header_shortcode'] : "";
                        $html_content_sceond_shortcode = isset($columns['arp_header_shortcode_sceond']) ? $columns['arp_header_shortcode_sceond'] : "";
                        $html_content_third_shortcode = isset($columns['arp_header_shortcode_third ']) ? $columns['arp_header_shortcode_third '] : "";

                        if (preg_match('/arp_googlemap/', $html_content_shortcode))
                            $googlemap = 1;
                        if (preg_match('/arp_googlemap/', $html_content_sceond_shortcode))
                            $googlemap = 1;
                        if (preg_match('/arp_googlemap/', $html_content_third_shortcode))
                            $googlemap = 1;
                    }
                }

                if ($googlemap) {
                    if(is_ssl()){
                        $tablestring .= '<script type="text/javascript" language="javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>';
                    } else {
                        $tablestring .= '<script type="text/javascript" language="javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
                    }
                    $tablestring .='<script type="text/javascript" language="javascript" src="' . PRICINGTABLE_URL . '/js/jquery.gomap-1.3.2.min.js"></script>';
                }
            }
        }

        $arp_default_character_arr = get_option('arp_css_character_set');
        $arp_subset = (isset($arp_default_character_arr) and ! empty($arp_default_character_arr)) ? "&subset=" . implode(',', $arp_default_character_arr) : '';

        if (is_ssl())
            $googlefontbaseurl = "https://fonts.googleapis.com/css?family=";
        else
            $googlefontbaseurl = "http://fonts.googleapis.com/css?family=";

        $default_fonts = $arprice_fonts->get_default_fonts();

        $including_google_fonts = array();        

        $tooltip_font_family = isset($general_option['tooltip_settings']['tooltip_font_family'])?$general_option['tooltip_settings']['tooltip_font_family']:'';
        if (!in_array($tooltip_font_family, $default_fonts) && $tooltip_font_family != '') {
            if (!in_array($tooltip_font_family, $including_google_fonts)) {
                $including_google_fonts[] = $tooltip_font_family;
            }
        }
         $general_column_settings = isset($general_option['column_settings'])?$general_option['column_settings']:array();
        if (!in_array($general_column_settings['price_font_family_global'], $default_fonts) && $general_column_settings['price_font_family_global'] != '') {
            if (!in_array($general_column_settings['price_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['price_font_family_global'];
            }
        }
        if (!in_array($general_column_settings['header_font_family_global'], $default_fonts) && $general_column_settings['header_font_family_global'] != '') {
            if (!in_array($general_column_settings['header_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['header_font_family_global'];
            }
        }

        if (!in_array($general_column_settings['body_font_family_global'], $default_fonts) && $general_column_settings['body_font_family_global'] != '') {
            if (!in_array($general_column_settings['body_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['body_font_family_global'];
            }
        }
        if (!in_array($general_column_settings['footer_font_family_global'], $default_fonts) && $general_column_settings['footer_font_family_global'] != '') {
            if (!in_array($general_column_settings['footer_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['footer_font_family_global'];
            }
        }
        if (!in_array($general_column_settings['button_font_family_global'], $default_fonts) && $general_column_settings['button_font_family_global'] != '') {
            if (!in_array($general_column_settings['button_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['button_font_family_global'];
            }
        }
        if (!in_array($general_column_settings['description_font_family_global'], $default_fonts) && $general_column_settings['description_font_family_global'] != '') {
            if (!in_array($general_column_settings['description_font_family_global'], $including_google_fonts)) {
                $including_google_fonts[] = $general_column_settings['description_font_family_global'];
            }
        }

        
        $toggle_title_font_family = isset($general_option['general_settings']['toggle_title_font_family'])?$general_option['general_settings']['toggle_title_font_family']:'';
        if (isset($toggle_title_font_family) && !in_array($toggle_title_font_family, $default_fonts) && $toggle_title_font_family != '') {
            if (!in_array($toggle_title_font_family, $including_google_fonts)) {
                $including_google_fonts[] = $toggle_title_font_family;
            }
        }

        $toggle_button_font_family = isset($general_option['general_settings']['toggle_button_font_family'])?$general_option['general_settings']['toggle_button_font_family']:'';     
            if (isset($toggle_button_font_family) && !in_array($toggle_button_font_family, $default_fonts) && $toggle_button_font_family != '') {
            if (!in_array($toggle_button_font_family, $including_google_fonts)) {
                $including_google_fonts[] = $toggle_button_font_family;
            }
        }

        
        foreach ($opts['columns'] as $j => $columns) {

            if (isset($columns['is_caption']) && $columns['is_caption'] == 1){

                $arp_caption_header_font_family = isset($columns['header_font_family'])?$columns['header_font_family']:'';
                if (isset($arp_caption_header_font_family) && !in_array($arp_caption_header_font_family, $default_fonts) && $arp_caption_header_font_family != '') {
                    if (!in_array($arp_caption_header_font_family, $including_google_fonts)) {
                        $including_google_fonts[] = $arp_caption_header_font_family;
                    }
                }

                $arp_caption_content_font_family = isset($columns['content_font_family'])?$columns['content_font_family']:'';
                if (isset($arp_caption_content_font_family) && !in_array($arp_caption_content_font_family, $default_fonts) && $arp_caption_content_font_family != '') {
                    if (!in_array($arp_caption_content_font_family, $including_google_fonts)) {
                        $including_google_fonts[] = $arp_caption_content_font_family;
                    }
                }

                $arp_caption_footer_level_options_font_family = isset($columns['footer_level_options_font_family'])?$columns['footer_level_options_font_family']:'';
                if (isset($arp_caption_footer_level_options_font_family) && !in_array($arp_caption_footer_level_options_font_family, $default_fonts) && $arp_caption_footer_level_options_font_family != '') {
                    if (!in_array($arp_caption_footer_level_options_font_family, $including_google_fonts)) {
                        $including_google_fonts[] = $arp_caption_footer_level_options_font_family;
                    }
                }

            }

        }

        if (isset($including_google_fonts) and is_array($including_google_fonts) and ! empty($including_google_fonts)) {
            foreach ($including_google_fonts as $google_fonts) {
                $tablestring .=  '<link rel="stylesheet" type="text/css" href="' . $googlefontbaseurl . urlencode(trim($google_fonts)) . $arp_subset . '" />';
            }
        }

        $tablestring .= "<style type='text/css' media='all'>";
        $tablestring .= '[class*="arpfa-"] {width: auto; height: auto; top: 0px; vertical-align: unset;}';
        $tablestring .= $arprice_form->arp_render_customcss($template_name, $general_option, $is_tbl_preview, $opts, $is_animated);


        if (isset($column_settings['arp_load_first_time_after_migration']) ? $column_settings['arp_load_first_time_after_migration'] : '') {
            $old_template_css_array = $arprice_old_template_css->arprice_old_template_css_array();
            $old_css = $old_template_css_array[$ref_template][0];
            $css_new = preg_replace('/arptemplate_([\d]+)/', 'arptemplate_' . $table_id, $old_css);
            $tablestring .= $css_new;
        }

        if ($general_option['tooltip_settings']['style'] == 'normal') {
            $tablestring .= " .arp_tooltip_" . $id . " {
            border-radius:4px !important;
                -moz-border-radius:4px !important;
                -webkit-border-radius:4px !important;
                -o-border-radius:4px !important;
        }";
        } else if ($general_option['tooltip_settings']['style'] == 'glass') {
            $tablestring .= " .arp_tooltip_" . $id . " {
            border-radius:10px !important;
                -moz-border-radius:10px !important;
                -webkit-border-radius:10px !important;
                -o-border-radius:10px !important;
            box-shadow:0px 0px 20px rgba(0,0,0,0.3);
                -moz-box-shadow:0px 0px 20px rgba(0,0,0,0.3);
                -webkit-box-shadow:0px 0px 20px rgba(0,0,0,0.3);
                -o-box-shadow:0px 0px 20px rgba(0,0,0,0.3);          
        }";
        } else if ($general_option['tooltip_settings']['style'] == 'alert') {
            $tablestring .= " .arp_tooltip_" . $id . " {
            border-radius:0px !important;
                -moz-border-radius:0px !important;
                -webkit-border-radius:0px !important;
                -o-border-radius:0px !important;
            box-shadow:0px 0px 20px rgba(0,0,0,0.3);
                -moz-box-shadow:0px 0px 20px rgba(0,0,0,0.3);
                -webkit-box-shadow:0px 0px 20px rgba(0,0,0,0.3);
                -o-box-shadow:0px 0px 20px rgba(0,0,0,0.3);          
        }";
        } else if ($general_option['tooltip_settings']['style'] == 'drop') {
            $tablestring .= ".arp_tooltip_" . $id . " {
            border-radius:20px;
                -moz-border-radius:20px;
                -webkit-border-radius:20px;
                -o-border-radius:20px;
            padding:10px;
            text-align:center;
        }";
        }
        
        $templates_to_chekc = array('arptemplate_1', 'arptemplate_2', 'arptemplate_3', 'arptemplate_4', 'arptemplate_5', 'arptemplate_6', 'arptemplate_7', 'arptemplate_8', 'arptemplate_9', 'arptemplate_10', 'arptemplate_11', 'arptemplate_17', 'arptemplate_18', 'arptemplate_20', 'arptemplate_21', 'arptemplate_22', 'arptemplate_23', 'arptemplate_25', 'arptemplate_26');
        if(isset($general_settings['toggle_button_verticle_space'])){
            $vertical_space = $general_settings['toggle_button_verticle_space'];
            if(in_array($ref_template, $templates_to_chekc)){
                $vertical_space = $general_settings['toggle_button_verticle_space'] - 20;
            }
            
            $tablestring .= ".arp_outer_wrapper_all_columns.arptemplate_".$template_name." .toggle_content_wrapper.arp_button_style_switch, 
                            .toggle_content_wrapper.arp_border_button_style_switch, 
                            .toggle_content_wrapper.arp_radio_style_switch,
                            .arp_outer_wrapper_all_columns .toggle_content_wrapper.arp_slide_button_style_switch,
                            .arp_outer_wrapper_all_columns .toggle_content_wrapper.arp_stepy_style_switch
            {margin-bottom: ".$vertical_space."px !important;}";
        }

        $arprice_hide_section_array = $arprice_default_settings->arprice_hide_section_array();
        $arprice_hide_section_array = $arprice_hide_section_array[$ref_template];

        if (isset($column_settings['hide_footer_global']) && $column_settings['hide_footer_global'] == '1') {
            foreach ($arprice_hide_section_array['arp_footer'] as $css_classs) {
                $tablestring .= ".arptemplate_" . $template_name . " " . $css_classs . " {display : none !important;}";
            }
        }
        $hide_header = false;
        if (isset($column_settings['hide_header_global']) && $column_settings['hide_header_global'] == '1') {
            foreach ($arprice_hide_section_array['arp_header'] as $css_classs) {
                $tablestring .= ".arptemplate_" . $template_name . "  " . $css_classs . " {display : none !important;}";
            }
            if ($ref_template === 'arptemplate_23') {
                $tablestring .= ".arptemplate_" . $template_name . " .arppricetablecolumntitle{display:none !important;}";
            }
            $hide_header = true;
        }
        $hide_price = false;
        if (isset($column_settings['hide_price_global']) && $column_settings['hide_price_global'] == '1') {
            foreach ($arprice_hide_section_array['arp_price'] as $css_classs) {
                $tablestring .= ".arptemplate_" . $template_name . "  " . $css_classs . "  {display : none !important;}";
            }
            $hide_price = true;
        }

        if ($hide_header == true && $hide_price == true && $ref_template === 'arptemplate_10') {
            $tablestring .= ".arptemplate_" . $template_name . " .planContainer.arp_ribbon_2 .arp_ribbon_container{ top:-33px !important; }";
        }

        if (isset($column_settings['hide_feature_global']) && $column_settings['hide_feature_global'] == '1') {
            foreach ($arprice_hide_section_array['arp_feature'] as $css_classs) {
                $tablestring .= ".arptemplate_" . $template_name . "  " . $css_classs . " {display : none !important;}";
            }
        }
        if (isset($column_settings['hide_description_global']) && $column_settings['hide_description_global'] == '1') {

            foreach ($arprice_hide_section_array['arp_description'] as $css_classs) {

                $tablestring .= ".arptemplate_" . $template_name . "  " . $css_classs . "  {display : none !important;}";
            }
        }
        if (isset($column_settings['hide_header_shortcode_global']) && $column_settings['hide_header_shortcode_global'] == '1') {
            foreach ($arprice_hide_section_array['arp_header_shortcode'] as $css_classs) {
                $tablestring .= ".arptemplate_" . $template_name . "  " . $css_classs . "  {display : none !important;}";
            }
        }
        $tablestring .= get_option('arp_global_custom_css');

        $tablestring .= isset($general_settings['arp_custom_css']) ? $general_settings['arp_custom_css'] : "";

        $toggle_mobile_view_style = isset($general_settings['toggle_mobile_style']) ? $general_settings['toggle_mobile_style'] : 0;

        if (get_option('arp_desktop_responsive_size') and get_option('arp_desktop_responsive_size') > 0 and $general_option['column_settings']['is_responsive'] == 1) {
            $tablestring .= ".arp_template_main_container{ max-width:" . get_option('arp_desktop_responsive_size') . "px !important; }";
        }

        if (get_option('arp_mobile_responsive_size') and get_option('arp_mobile_responsive_size') > 0 and $general_option['column_settings']['is_responsive'] == 1) {
            $tablestring .= "
            @media all and (max-width:" . get_option('arp_mobile_responsive_size') . "px){";
            $tablestring .= ".arptemplate_" . $template_name . " .ArpPricingTableColumnWrapper.no_animation:not(.maincaptioncolumn), .arptemplate_" . $template_name . " .ArpPricingTableColumnWrapper.no_animation:not(.maincaptioncolumn), .arptemplate_" . $template_name . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn), .arptemplate_" . $template_name . " .ArpPricingTableColumnWrapper:not(.maincaptioncolumn){";
            
            if ($is_animation) {
                $tablestring .= "width:100%;";
            } else {
                $tablestring .= "width:100%;";
            }
            $tablestring .="margin-left:auto !important;";

            $tablestring .="margin-right:auto !important;";

            $tablestring .= "max-width:320px !important;";
            $tablestring .= "float:none !important;";
            $tablestring .= "display:inline-block !important;";
        
            $tablestring .= "}";

            $tablestring .= ".ArpTemplate_main ,";
            $tablestring .= ".arptemplate_" . $template_name . " .arp_inner_wrapper_all_columns ,";
            $tablestring .= ".arptemplate_" . $template_name . " .arp_allcolumnsdiv {";
            $tablestring .= "width:100%;";
            $tablestring .= "}";

            $tablestring .= "}";
        }

        if (get_option('arp_mobile_responsive_size') and get_option('arp_mobile_responsive_size') > 0) {
            $tablestring .= "@media all and (max-width:" . get_option('arp_mobile_responsive_size') . "px){";


            if ($ref_template == 'arptemplate_12') {
                $tablestring .= ".arptemplate_" . $template_name . " .maincaptioncolumn .arpplan{";
                $tablestring .= "border-right:1px solid #E0E0E0;";
                $tablestring .= "}";
            }

            if ($ref_template == 'arptemplate_9') {
                $tablestring .= ".arptemplate_" . $template_name . " .maincaptioncolumn .arpcolumnheader  .arpcaptiontitle{";

                $tablestring .= "border-radius: 2px 2px 0px 0px;
                                        -moz-border-radius: 2px 2px 0px 0px;
                                        -webkit-border-radius: 2px 2px 0px 0px;
                                        -o-border-radius: 2px 2px 0px 0px;";

                $tablestring .= "}";
                $tablestring .= ".arptemplate_" . $template_name . " .maincaptioncolumn .arppricingtablebodycontent{";

                $tablestring .= "}";
            }

            if($toggle_mobile_view_style == 1){

                $tablestring .= "#ArpTemplate_main.arp_front_main_container .arptemplate_" . $template_name . " .toggle_content_wrapper .arp_toggle_dropdown_container{";
                $tablestring .= "display: inline-block;";
                $tablestring .= "}";

                $tablestring .= ".arptemplate_" . $template_name . " .toggle_content_wrapper .toggle_content_switches{";
                $tablestring .= "display: none !important;";
                $tablestring .= "}";

                $tablestring .= "#ArpTemplate_main.arp_front_main_container .arptemplate_" . $template_name . " .toggle_content_wrapper.arp_radio_style_switch.arp_toggle_mobile_dropdown_wrapper, .arptemplate_" . $template_name . " .toggle_content_wrapper.arp_radio_style_switch.arp_toggle_mobile_dropdown_wrapper{";
                $tablestring .= "background: none;";
                $tablestring .= "border: none;";
                $tablestring .= "}";
            }



            $tablestring .= "}";
        }


        if($toggle_mobile_view_style == 1){
            $tablestring .= ".arp_widget_table #ArpTemplate_main.arp_front_main_container .toggle_content_wrapper .arp_toggle_dropdown_container,";
            $tablestring .= ".arp_widget_table .toggle_content_wrapper .arp_toggle_dropdown_container{";
            $tablestring .= "display: inline-block;";
            $tablestring .= "}";

            $tablestring .= ".arp_widget_table .toggle_content_wrapper .toggle_content_switches{";
            $tablestring .= "display: none !important;";
            $tablestring .= "}";

            $tablestring .= ".arp_widget_table #ArpTemplate_main.arp_front_main_container .toggle_content_wrapper.arp_radio_style_switch,";
            $tablestring .= ".arp_widget_table .toggle_content_wrapper.arp_radio_style_switch{";
            $tablestring .= "background: none;";
            $tablestring .= "border: none;";
            $tablestring .= "}";
        }

        $tablestring .= "</style>";

        $template_id = $template_settings['template'];
        $color_scheme = 'arp' . $template_settings['skin'];

        $hover_class = '';
        if ($enable_hover_effect) {
            $hover_class = $hover_type;
        }
        if (!in_array($hover_class, array('hover_effect', 'shadow_effect')) && ($enable_hover_effect)) {
            $hover_class .= ' arp_hover_animated_effect';
            $arp_inc_effect_css[] = 1;
        }
        
        if ($is_animation != "" && $is_animation == 'yes') {
            $animation_class = 'has_animation';
            $hover_class .= ' no_effect';
        } else {
            $animation_class = 'no_animation';
        }

        
        $enable_hover_effect_class = '';
        if (!$enable_hover_effect) {
            $enable_hover_effect_class = 'arp_disable_hover';
        }
        
        $global_column_width = "";
      


        if ($column_animation['is_animation'] == 'yes') {
            global $arp_is_animation;
            $arp_is_animation = 1;
        }

        if ($column_animation['is_animation'] == 'yes' and $column_animation['pagi_nav_btn'] != 'navigation') {
            $slider_pagination_container = 'arp_slider_pagination';
            if ($column_animation['pagi_nav_btn'] == 'pagination_top') {
                $slider_pagination_container .= ' Top';
            } else if ($column_animation['pagi_nav_btn'] == 'pagination_bottom' or $column_animation['pagi_nav_btn'] == 'both') {
                $slider_pagination_container .= ' Bottom';
            }
        } else {
            $slider_pagination_container = '';
        }


        
        $navigation = "";
        if ($column_animation['is_animation'] == 'yes') {
            $navigation = ( $column_animation['navi_nav_btn'] == 'navigation' ) ? 1 : 0;
        }
        if ($navigation) {
            $tablestring .= "<div class='arp_prev_div'>";
            $tablestring .= "<div id='arp_prev_btn_" . $template_name . "_".$arp_unique_table_id."' class='arp_prev_btn arp_nav_style_1'></div>";
            $tablestring .= "</div>";
        }

        
        $col_array = array();
        foreach ($opts['columns'] as $j => $columns) {
            if (isset($columns['is_caption']) && $columns['is_caption'] == 1)
                $col_array[] = 1;
            else
                $col_array[] = 0;
        }

        $tablestring .= "<div class='ArpPriceTable arp_outer_wrapper_all_columns arp_price_table_" . $template_name . " arp_price_ref_table_" . $ref_template . " arptemplate_" . $template_name . " " . " arptemplate_" . $template_name."_".$arp_unique_table_id . " " . $color_scheme . " " . $slider_pagination_container . "'";

        
        if (isset($column_animation['is_animation']) and $column_animation['is_animation'] == 'yes') {
            global $arp_pricingtable;
            $arp_pricingtable->arp_pricing_table_main_settings();
            global $arp_mainoptionsarr;
            $data_items = $column_animation['visible_column'] ? $column_animation['visible_column'] : 1;
            $scrolling_columns = $column_animation['scrolling_columns'] ? $column_animation['scrolling_columns'] : 1;
            $navigation = ( $column_animation['navi_nav_btn'] == 'navigation' ) ? 1 : 0;
            $transition_speed = $column_animation['transition_speed'] ? $column_animation['transition_speed'] : '500';
            
            $hide_caption = ( $column_settings['hide_caption_column'] == 1 ) ? 1 : 0;
            
            $autoplay = ( $column_animation['autoplay'] == 1 ) ? 1 : 0;
            $infinite = $autoplay ? 1 : 0;

            $sticky_caption = ( in_array(1, $col_array) and $column_animation['sticky_caption'] == 1 ) ? 'true' : 'false';

            $sliding_effect = (in_array($column_animation['sliding_effect'], $arp_mainoptionsarr['general_options']['column_animation']['sliding_effect'])) ? $column_animation['sliding_effect'] : 'slide';
            $easing_effect = (in_array($column_animation['sliding_effect'], $arp_mainoptionsarr['general_options']['column_animation']['easing_effect'])) ? $column_animation['sliding_effect'] : 'swing';


            $tablestring .= " data-animate='true' data-id='" . $template_name . "' data-items='" . $data_items . "' data-scroll='" . $scrolling_columns . "' data-autoplay='" . $autoplay . "' data-effect='" . $sliding_effect . "' data-speed='" . $transition_speed . "' data-caption='" . $hide_caption . "' data-infinite='" . $infinite . "' data-sticky-caption='" . $sticky_caption . "' data-easing='" . $easing_effect . "' style='display:table-cell;'";
        }
        $tablestring .= ">";

        $arp_template_bg_img_style ='';
        if(isset($column_animation['template_bg_img']) && $column_animation['template_bg_img'] != ''){
            $wp_upload_dir = wp_upload_dir();
            $arp_template_bg_img= $column_animation['template_bg_img'];
            $arp_template_bg_img_url= $wp_upload_dir['baseurl'].'/arprice/template_background_image/'.$arp_template_bg_img ;
            $arp_template_bg_img_style= "background-image :url(".$arp_template_bg_img_url."); padding:50px; background-repeat:no-repeat;";
        }

        $arp_pricing_table_col_style = '';
        if( $navigation ){
            $arp_pricing_table_col_style = 'display:table-cell;';
        }

        $tablestring .= "<div class='arp_inner_wrapper_all_columns'  id='ArpPricingTableColumns'  style='".$arp_template_bg_img_style.$arp_pricing_table_col_style."' >";

        $one_toggle_selected = $two_toggle_selected = $three_toggle_selected = '';
        $one_toggle_selected = ' toggle_selected active_toggle';
        $setas_default_toggle = 0;
        $toggle_style = '';
        if ($general_settings['enable_toggle_price'] == 1) {
            $toggle_style = $general_settings['arp_toggle_main'];
            $toggle_step_count = $general_settings['arp_step_main'];
            $toggle_mobile_view_style = isset($general_settings['toggle_mobile_style']) ? $general_settings['toggle_mobile_style'] : 0;

            $togglestep_keys = $arp_pricingtable->arp_toggle_step_label_with_db($general_settings);
            $scounter = explode('|',$togglestep_keys[$toggle_step_count]);

            $switch_counter = $scounter[count($scounter) - 3];

            $tablestring .= "<input type='hidden' id='toggle_switch_width' value='".json_encode($arp_pricingtable->arp_toggle_switch_position())."' />";
            $tablestring .= "<input type='hidden' id='toggle_slide_button_width' value='".json_encode($arp_pricingtable->arp_toggle_slide_button_position())."' />";
            $tablestring .= "<input type='hidden' id='arp_total_tabs' name='arp_total_tabs' value='" . json_encode($total_tabs) . "' />";
            $tablestring .= "<input type='hidden' id='arp_toggle_swtich_steps' name='arp_toggle_swtich_steps' value='" . json_encode($arp_pricingtable->arp_toggle_step_label()) . "' />";


            
            switch ($toggle_style) {
                case 0:
                    $toggle_wrapper_style = 'arp_button_style_switch';
                    break;
                case 1:
                    $toggle_wrapper_style = 'arp_radio_style_switch';
                    break;
                case 2:
                    $toggle_wrapper_style = 'arp_border_button_style_switch';
                    break;
                case 3:
                    $toggle_wrapper_style = 'arp_slide_button_style_switch';
                    break;
                case 4:
                    $toggle_wrapper_style = 'arp_stepy_style_switch';
                    break;
                default:
                    $toggle_wrapper_style = 'arp_button_style_switch';
                    break;
            }
            $toggle_label_position = $general_settings['arp_label_position_main'];
            $toggle_label_yearly = $general_settings['togglestep_yearly'];
            $toggle_label_monthly = $general_settings['togglestep_monthly'];
            $toggle_label_quarterly = $general_settings['togglestep_quarterly'];
            $toggle_sub_label_yearly = isset($general_settings['togglestep_sub_yearly']) ? $general_settings['togglestep_sub_yearly'] : '';
            $toggle_sub_label_monthly = isset($general_settings['togglestep_sub_monthly']) ? $general_settings['togglestep_sub_monthly'] : '';
            $toggle_sub_label_quarterly = isset($general_settings['togglestep_sub_quarterly']) ? $general_settings['togglestep_sub_quarterly'] : '';
            $setas_default_toggle = $general_settings['setas_default_toggle'];

            $one_toggle_selected = $two_toggle_selected = $three_toggle_selected = '';
            $yearly_toggle_selected = $monthly_toggle_selected = $quarterly_toggle_selected = '';
            $one_selected_fa_class = $two_selected_fa_class = $three_selected_fa_class = 'arpfa arpfa-circle-thin arpfa-lg';
            if ($setas_default_toggle == 1) {
                $monthly_toggle_selected = ' selected ';
                $two_toggle_selected = ' toggle_selected active_toggle ';
                $toggle_content_default_value = 'two_step_two';
                $two_selected_fa_class = "arpfa arpfa-dot-circle-o arpfa-lg";
            } else if ($setas_default_toggle == 2) {
                $quarterly_toggle_selected = ' selected ';
                $three_toggle_selected = ' toggle_selected active_toggle ';
                $toggle_content_default_value = 'two_step_three';
                $three_selected_fa_class = "arpfa arpfa-dot-circle-o arpfa-lg";
            } else {
                $yearly_toggle_selected = ' selected ';
                $one_toggle_selected = ' toggle_selected active_toggle ';
                $toggle_content_default_value = 'two_step_one';
                $one_selected_fa_class = "arpfa arpfa-dot-circle-o arpfa-lg";
            }

            if ($toggle_label_position == 0) {
                $toggle_wrapper_cls = 'arp_toggle_left_position';
            } else if ($toggle_label_position == 1) {
                $toggle_wrapper_cls = 'arp_toggle_top_position';
            } else {
                $toggle_wrapper_cls = 'arp_toggle_right_position';
            }

            $animated_template = ( $is_animated == 1 ) ? 'toggle_animated_template' : '';

            if($toggle_mobile_view_style == 1){
                $toggle_wrapper_cls .= ' arp_toggle_mobile_dropdown_wrapper';
            }

            $tablestring .= "<div class='toggle_content_wrapper $animated_template $toggle_wrapper_style $toggle_wrapper_cls arptemplate_" . $template_name . "_arp_toogle_wrapper' id='arptemplate_" . $template_name . "_arp_toogle_wrapper'>";

            $tablestring .= "<div class='toggle_content_title $switch_counter'>";
            $tablestring .= $general_settings['toggle_label_content'];
            $tablestring .= "</div>";
            $tablestring .= "<div class='toggle_content_switches $switch_counter'>";

            $toggle_mobile_dropdown_string = " <div class='arp_toggle_dropdown_container'>";
            $toggle_mobile_dropdown_string .= '<input type="hidden" id="arp_toggle_mobile_hidden_ul"  name="arp_mobile_toggle_dropdown" class="arp_mobile_toggle_dropdown" value="ARPTOGGLEDEFAULTVALUE" /> ';

            $toggle_mobile_dropdown_string .= "<dl class='arp_selectbox arp_toggle_switch_dd' id='arp_toggle_mobile_hidden_dd' data-name='arp_mobile_toggle_dropdown' data-id='arp_toggle_mobile_hidden_ul'>";

            $toggle_mobile_dropdown_string .= "<dt><span>ARPTOGGLEDEFAULTSTRING</span>";

            $toggle_mobile_dropdown_string .= "<i class='arpfa arpfa-caret-down arpfa-lg'></i></dt>";

            $toggle_mobile_dropdown_string .= "<dd>";
            $toggle_mobile_dropdown_string .= "<ul data-id='arp_toggle_mobile_hidden_ul'>";

            $switch_string = $radio_string = $border_button_string = $slide_button_string = $stepy_string = "";

             for($t = 0; $t < $toggle_step_count; $t++ ){

                    $count = $t + 1;

                    $toggle_data = $togglestep_keys[$count];

                    $toggle_data_array = explode('|',$toggle_data);
                    $toggle_db_key = $toggle_data_array[0];
                    $toggle_label = $toggle_data_array[1];

                    array_push($font_awesome_match, @$toggle_label);

                    $toggle_id = $toggle_data_array[2];
                    $data_value = $toggle_data_array[4];

                    $random_step = rand(1, 9999);

                    $selected_cls = '';
                    $selected_cls_radio = 'arpfar arpfa-circle arpfa-lg';
                    if( $setas_default_toggle == $t  ){
                        $selected_cls = ' selected ';
                        $selected_cls_radio = 'arpfar arpfa-dot-circle arpfa-lg';
                        $toggle_mobile_dropdown_string = str_replace('ARPTOGGLEDEFAULTSTRING', $toggle_label, $toggle_mobile_dropdown_string);
                        $toggle_mobile_dropdown_string = str_replace('ARPTOGGLEDEFAULTVALUE', 'arptemplate_'.$template_name.'_'.$random_step.'_toggle_step_'.$t, $toggle_mobile_dropdown_string);
                    }

                    $display_switch = '';
                    $last_stepy_cls = '';
                    if( $count > 2 ){
                        if( $toggle_step_count == $count ){
                            $display_switch = '';
                            $last_stepy_cls = 'arp_last_stepy_box';
                        } else if( $count > $toggle_step_count ){
                            $display_switch = 'display:none;';
                        }
                    } else {
                        if( $toggle_step_count == $count ){
                            $last_stepy_cls = 'arp_last_stepy_box';
                        }
                    }


                    $switch_string .= "<div class='button_switch_box {$switch_counter} {$selected_cls}' data-total='{$toggle_step_count}' data-count='{$t}' id='arptemplate_{$template_name}_{$random_step}_toggle_step_{$t}' data-value='{$data_value}' style='{$display_switch}' >";

                    $switch_string .= $toggle_label;

                    $switch_string .= "</div>";

                    $radio_string .= "<div class='radio_button_box {$selected_cls}' id='arptemplate_{$template_name}_{$random_step}_toggle_step_{$t}' data-count='{$t}' data-total='{$toggle_step_count}' data-value='{$data_value}' style='{$display_switch}' >";

                    $radio_string .= "<span class='".$selected_cls_radio."'></span>";

                    $radio_string .= "<label class='toggle_content_label_txt'>".$toggle_label."</label>";

                    $radio_string .= "</div>";

                    $border_button_string .= "<div class='border_button_box {$selected_cls}' data-total='{$toggle_step_count}' data-count='{$t}' id='arptemplate_{$template_name}_{$random_step}_toggle_step_{$t}' data-value='{$data_value}' style='{$display_switch}' >";

                    $border_button_string .= $toggle_label;

                    $border_button_string .= "</div>";

                    $slide_button_string .= "<div class='slide_button_box {$switch_counter} {$selected_cls}' data-total='{$toggle_step_count}' data-count='{$t}' id='arptemplate_{$template_name}_{$random_step}_toggle_step_{$t}' data-value='{$data_value}' style='{$display_switch}' >";

                    $slide_button_string .= $toggle_label;

                    $slide_button_string .= "</div>";

                    $stepy_string .= "<div class='stepy_box {$selected_cls} {$last_stepy_cls}' data-total='{$toggle_step_count}' data-count='{$t}' id='arptemplate_{$template_name}_{$random_step}_toggle_step_{$t}' data-value='{$data_value}' style='{$display_switch}' >";

                    $stepy_string .= "<div class='stepy_box_selected'><div class='arp_icon'><i class='arpfa arpfa-check'></i></div></div>";

                    $stepy_string .= "<span>".$toggle_label."</span>";

                    $stepy_string .= "</div>";

                    $toggle_mobile_dropdown_string .= "<li class='arp_selectbox_option' data-value='arptemplate_{$template_name}_{$random_step}_toggle_step_{$t}' data-label='".$toggle_label."'>";
                    $toggle_mobile_dropdown_string .= $toggle_label;
                    $toggle_mobile_dropdown_string .= "</li>";

                }

                $toggle_mobile_dropdown_string .= "</ul>";
                $toggle_mobile_dropdown_string .= "</dd>";

                $toggle_mobile_dropdown_string .= "</dl>";
                $toggle_mobile_dropdown_string .= "</div>";


            if ($toggle_wrapper_style == 'arp_radio_style_switch') {

                $tablestring .= $radio_string;

                
            } else if ($toggle_wrapper_style == 'arp_button_style_switch') {

                $tablestring .= $switch_string;



                
                $tablestring .= "<div class='button_switch_box button_switch_box_selected'></div>";

            } else if ($toggle_wrapper_style == 'arp_border_button_style_switch') {

                $tablestring .= $border_button_string;

            } else if ($toggle_wrapper_style == 'arp_slide_button_style_switch') {

                $tablestring .= $slide_button_string;

                $tablestring .= "<div class='slide_button_box slide_button_box_selected'></div>";

            } else if ($toggle_wrapper_style == 'arp_stepy_style_switch') {

                $tablestring .= "<div id='arp_stepy_style_switch'>";
                $tablestring .= $stepy_string;
                $tablestring .= "</div>";

            }


            $tablestring .= "<input type='hidden' name='arprice_toggle_content_value' id='arprice_toggle_content_value' class='switch_front_radio_btn' value='" . $toggle_content_default_value . "'/>";
            $tablestring .= "</div>";

            if($toggle_mobile_view_style == 1){
                $tablestring .= $toggle_mobile_dropdown_string;
            }

            $tablestring .= "<input type='hidden' name='arprice_toggle_mobile_view_style' id='arprice_toggle_mobile_view_style' value='" . $toggle_mobile_view_style . "'/>";

            $tablestring .= "</div>";
        }

        $x = 0;

        if ($opts['columns'] and count($opts['columns']) > 0) {
            
            $header_img = array();
            foreach ($opts['columns'] as $j => $columns) {
                $header_img[] = 0;
                if (isset($columns['arp_header_shortcode']) && $columns['arp_header_shortcode'] != '')
                    $header_img[] = 1;

                if (isset($columns['arp_header_shortcode_second']) && $columns['arp_header_shortcode_second'] != '')
                    $header_img[] = 1;

                if (isset($columns['arp_header_shortcode_third']) && $columns['arp_header_shortcode_third'] != '')
                    $header_img[] = 1;
            }

            foreach ($opts['columns'] as $j => $columns) {

                if (isset($columns['column_width']) && $columns['column_width'] != '' && $columns['column_width'] > 0) {
                    $inline_column_width[] = 1;
                } else {
                    $inline_column_width[] = 0;
                }
            }


            $margin_top_all_div = "";
            if (isset($column_animation['is_animation']) and $column_animation['is_animation'] == 'yes') {
                if ($ref_template == 'arptemplate_10' || $ref_template == 'arptemplate_13' || $ref_template == 'arptemplate_14' || $ref_template == 'arptemplate_15' || $ref_template == 'arptemplate_16') {
                    $margin_top_all_div = 'padding-top:36px;';
                } else {
                    $margin_top_all_div = 'padding-top:22px;';
                }
            }


            $tablestring .= "<div class='arp_allcolumnsdiv' style='" . $margin_top_all_div . "'>";
            $style_ = 0;
            $arf_cap_col_hidden_row_css = array();
            foreach ($opts['columns'] as $j => $columns) {

                if (@$columns['is_caption'] == 1 and $template_feature['caption_style'] == 'default') {
                    $inlinecolumnwidth = "";
                    if ($columns["column_width"] != "")
                        $inlinecolumnwidth = 'width:' . $columns["column_width"] . 'px';
                    $column_highlight = $opts['columns'][$j]['column_highlight'];
                    if ($column_highlight && $column_highlight == 1)
                        $highlighted_column = 'column_highlight';

                    $column_hide = isset($opts['columns'][$j]['column_hide']) ? $opts['columns'][$j]['column_hide'] : 0;
                    if ($column_hide && $column_hide == 1){
                        continue;
                    }

                    if ($columns['column_width'] != '') {
                        $has_custom_column_width = 'data-has_custom_column_width="true"';
                        $has_custom_width = '';
                    } else {
                        $has_custom_column_width = 'data-has_custom_column_width="false"';
                        $has_custom_width = '';
                    }

                    
                    $footer_hover_class = "";

                    if ($columns['footer_content'] != '' and $template_feature['has_footer_content'] == 1) {

                        $footer_hover_class .= ' has_footer_content';
                        if ($columns['footer_content_position'] == 0) {
                            $footer_hover_class .= " footer_below_content";
                        } else {
                            $footer_hover_class .= " footer_above_content";
                        }
                    } else {
                        $footer_hover_class = "";
                    }

                    $column_settings['hide_caption_column'] = isset($column_settings['hide_caption_column']) ? $column_settings['hide_caption_column'] : "";

                    if (!empty($general_option['column_settings']['column_box_shadow_effect']) && $general_option['column_settings']['column_border_radius_top_left'] == 0 && $general_option['column_settings']['column_border_radius_top_right'] == 0 && $general_option['column_settings']['column_border_radius_bottom_right'] == 0 && $general_option['column_settings']['column_border_radius_bottom_left'] == 0) {
                        if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_1')
                            $shadow_default_class = 'shadow_style_1';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_2')
                            $shadow_default_class = 'shadow_style_2';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_3')
                            $shadow_default_class = 'shadow_style_3';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_4')
                            $shadow_default_class = 'shadow_style_4';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_5')
                            $shadow_default_class = 'shadow_style_5';
                        else
                            $shadow_default_class = 'arp_none';
                    } else {
                        $shadow_default_class = '';
                    }

                    if ($shadow_default_class == 'shadow_style_5' && $columns['is_caption'] == 1 && ( $ref_template == 'arptemplate_6' || $ref_template == 'arptemplate_9' )) {
                        $shadow_default_class = 'shadow_style_none';
                    }
                
                    if( $column_settings['hide_caption_column'] ){
                        $hide_caption_column = ' arp_hidden_captioncolumn ';
                    } else {
                        $hide_caption_column = '  ';
                    }

                    $toggle_step ='';
                    if(isset($toggle_content_default_value) ){
                        $toggle_step = 'arp_toggle_column_' . ($setas_default_toggle + 1);
                    }
                    

                    $tablestring .= "<div id='main_" . $j . "' " . $has_custom_column_width . " data-order='main_column_" . $style_ . "' class='$shadow_default_class ArpPricingTableColumnWrapper  arppricingtablecaptioncolumn ".$hide_caption_column." " . $has_custom_width . " style_" . $j . " maincaptioncolumn  " . $animation_class . " arp_style_$style_ $enable_hover_effect_class ".$toggle_step." ' style='";

                    if ($column_settings['hide_caption_column'] == 1) {
                        $tablestring .= "display:none;";
                    }
                    if ($columns['column_width'] != '' && $columns['column_width'] > 0) {
                        $tablestring .= $inlinecolumnwidth;
                    } else {
                        if ($is_responsive != 1) {
                            $tablestring .= $global_column_width;
                        }
                    } $tablestring .= "'";
                    $tablestring.= " >";
                    $new_clickable_class = "";
                    if (isset($column_settings['full_column_clickable']) && $column_settings['full_column_clickable'] == '1') {
                        $new_clickable_class = "is_column_clickable";
                    }

                    $tablestring .= "<div class='arpplan " . $new_clickable_class . " ";
                    if ($columns['is_caption'] == 1) {
                        $tablestring .= "maincaptioncolumn ";
                    } else {
                        $tablestring .= $j . " ";
                    } if ($x % 2 == 0) {
                        $tablestring .= " arpdark-bg ArpPriceTablecolumndarkbg";
                    }


                    $tablestring .= "' style='";
                    if ($column_settings['hide_caption_column'] == 1) {
                        $tablestring .= "display:none;";
                    }
                    $tablestring .= "' >";



                    if ($ref_template == 'arptemplate_15')
                        $tablestring .= "<div class='arp_template_rocket'><div></div></div>";
                    $tablestring .= "<div class='planContainer'>";
                    if (!empty($columns['column_background_image']) && $ref_template === 'arptemplate_21') {
                        $column_level_bgi_class = ' hide_col_bg_img ';
                    } else {
                        $column_level_bgi_class = '';
                    }
                    $tablestring .= "<div class='arp_column_content_wrapper $column_level_bgi_class'>";
                    if (( $template == 'arptemplate_4' || $template == 'arptemplate_12') && in_array(1, $header_img))
                        $header_cls = 'has_header_code';

                    $tablestring .= arp_get_column_header_part( $columns, $ref_template, $template_feature, $j, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id );
                    
                    if( $columns['is_caption'] ){
                        $caption_array = arp_get_caption_feature_part( $columns, $template_name, $total_tabs, $maxrowcount, $column_settings );
                    }

                    $tablestring .= arp_get_column_feature_part( $columns, $ref_template, $template_feature, $j, $total_tabs, $setas_default_toggle, $maxrowcount, $x, $arp_unique_table_id, $column_settings, $table_id, $tooltip_display_style, $tooltip_trigger_type, $tooltip_icon_position, $tooltip_informative_icon, $caption_array, $template_name );


                    if ($template_feature['button_position'] == 'default') {
                        $tablestring .= arp_get_column_footer_part( $columns, $ref_template, $template_feature, $j, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id );
                    }


                    $tablestring .= "</div>";
                    $tablestring .= "</div>";
                    $tablestring .= "</div>";
                    $tablestring .= "</div>";
                    
                    $x++;
                    $style_++;
                } 
                else if (@$columns['is_caption'] == 1 and $template_feature['caption_style'] == 'style_1') {
                    for ($i = 0; $i <= $maxrowcount; $i++) {
                        $rows = isset($opts['columns'][$j]['rows']['row_' . $i]) ? $opts['columns'][$j]['rows']['row_' . $i] : array();
                        $caption_li[$i] = stripslashes_deep($rows['row_description']);
                    }
                } else if (@$columns['is_caption'] == 1 and $template_feature['caption_style'] == 'style_2') {
                    for ($i = 0; $i <= $maxrowcount; $i++) {
                        $rows = isset($opts['columns'][$j]['rows']['row_' . $i]) ? $opts['columns'][$j]['rows']['row_' . $i] : array();
                        $caption_li[$i] = stripslashes_deep($rows['row_description']);
                    }
                }
            }

            $c = $x;
            if ($c == 0) {
                $c = $x = 1;
            }
            

            $new_arr = array();
            if (is_array($col_ord_arr) && count($col_ord_arr) > 0) {
                foreach ($col_ord_arr as $key => $value) {
                    $new_value = str_replace('main_', '', $value);
                    $new_col_id = $new_value;
                    foreach ($opts['columns'] as $j => $columns) {
                        if ($new_col_id == $j) {
                            $new_arr['columns'][$new_col_id] = $columns;
                        }
                    }
                }
            } else {
                $new_arr = $opts;
            }

            if (in_array(1, $col_array) and $column_animation['sticky_caption'] == 1 && $is_animation == 'yes') {
                $tablestring .= "<div class='arp_allcolumnsdiv_sticky'>";
            }
            $caption_hidden_html = '';            
            foreach ($new_arr['columns'] as $j => $columns) {

                $shortcode_class = '';
                $shortcode_class_array = $arprice_default_settings->arp_shortcode_custom_type();
                if (isset($columns['arp_shortcode_customization_style'])) {
                    $columns['arp_shortcode_customization_size'] = isset($columns['arp_shortcode_customization_size']) ? $columns['arp_shortcode_customization_size'] : '';
                    $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'] = isset($shortcode_class_array[$columns['arp_shortcode_customization_style']]['class']) ? $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'] :'';
                    $shortcode_class = $columns['arp_shortcode_customization_size'] . ' ' . $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'];
                }
                if (@$columns['is_caption'] == 0) {
                    $inlinecolumnwidth = "";
                    if ($columns["column_width"] != "")
                        $inlinecolumnwidth = 'width:' . $columns["column_width"] . 'px';
                    $column_highlight = $opts['columns'][$j]['column_highlight'];
                    if ($column_highlight && $column_highlight == 1)
                        $highlighted_column = 'column_highlight ';
                    else
                        $highlighted_column = '';

                    $column_hide = isset($opts['columns'][$j]['column_hide']) ? $opts['columns'][$j]['column_hide'] : 0;
                    if ($column_hide && $column_hide == 1){
                        continue;
                    }

                    if ($columns['column_width'] != '' ) {
                        $has_custom_column_width = 'data-has_custom_column_width="true"';

                        $has_custom_width = '';
                    } else {
                        $has_custom_column_width = 'data-has_custom_column_width="false"';
                        $has_custom_width = '';
                    }

                    $footer_hover_class = "";

                    if (@$columns['footer_content'] != '' and $template_feature['has_footer_content'] == 1) {

                        $footer_hover_class .= ' has_footer_content';
                        if ($columns['footer_content_position'] == 0) {
                            $footer_hover_class .= " footer_below_content";
                        } else {
                            $footer_hover_class .= " footer_above_content";
                        }
                    } else {
                        $footer_hover_class = "";
                    }

                    
                    if ($is_animation == 'yes') {
                        $has_custom_width = '';
                    }
                    if (!empty($general_option['column_settings']['column_box_shadow_effect']) && $general_option['column_settings']['column_border_radius_top_left'] == 0 && $general_option['column_settings']['column_border_radius_top_right'] == 0 && $general_option['column_settings']['column_border_radius_bottom_right'] == 0 && $general_option['column_settings']['column_border_radius_bottom_left'] == 0) {
                        if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_1')
                            $shadow_default_class = 'shadow_style_1';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_2')
                            $shadow_default_class = 'shadow_style_2';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_3')
                            $shadow_default_class = 'shadow_style_3';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_4')
                            $shadow_default_class = 'shadow_style_4';
                        else if ($general_option['column_settings']['column_box_shadow_effect'] == 'shadow_style_5')
                            $shadow_default_class = 'shadow_style_5';
                        else
                            $shadow_default_class = 'arp_none';
                    } else {
                        $shadow_default_class = '';
                    }

                    if ($ref_template == 'arptemplate_2') {
                        
                    }

                    $toggle_step ='';
                    if(isset($toggle_content_default_value) ){
                        $toggle_step = 'arp_toggle_column_' .( $setas_default_toggle + 1);
                    }

                    $tablestring .= "<div id='main_" . $j . "' " . $has_custom_column_width . " data-order='main_column_" . $style_ . "'   class='$shadow_default_class  " . $highlighted_column . " ArpPricingTableColumnWrapper arpricemaincolumn style_" . $j . " " . $hover_class . " " . $animation_class . " " . $has_custom_width . " arp_style_$style_ $enable_hover_effect_class ".$toggle_step."'  style='";
                   
                    if ($c == 0) {
                        $tablestring .= "border-left:1px solid #DADADA;";
                    } if ($columns['column_width'] != '' && $columns['column_width'] > 0) {
                        $tablestring .= $inlinecolumnwidth;
                    } else {
                        if ($is_responsive != 1) {
                            $tablestring .= $global_column_width;
                        }
                    } $tablestring .= "'";
                    $tablestring .= " data-column-footer-position='{$columns['footer_content_position']}'";
                    $tablestring.= ">";


                    $new_clickable_class = "";
                    if (isset($column_settings['full_column_clickable']) && $column_settings['full_column_clickable'] == '1') {
                        $new_clickable_class = "is_column_clickable";
                    }


                    $tablestring .= "<div class='arpplan " . $new_clickable_class . " ";
                    if ($columns['is_caption'] == 1) {
                        $tablestring .= "maincaptioncolumn";
                    } else {
                        $tablestring .= "column_" . $c;
                    } if ($x % 2 == 0) {
                        $tablestring .= " arpdark-bg ArpPriceTablecolumndarkbg";
                    } $tablestring .= "'>";
                    if ($ref_template == 'arptemplate_15')
                        $tablestring .= "<div class='arp_template_rocket'><div></div></div>";

                    $columns['ribbon_setting']['arp_ribbon'] = isset($columns['ribbon_setting']['arp_ribbon']) ? $columns['ribbon_setting']['arp_ribbon'] : "";
                    $tablestring .= "<div class='planContainer " . $columns['ribbon_setting']['arp_ribbon'] . " '>";

                    $header_cls = '';
                    if (isset($columns['arp_header_shortcode']) && $columns['arp_header_shortcode'] != '')
                        $header_cls = 'has_arp_shortcode';
                    if (isset($columns['arp_header_shortcode_second']) && $columns['arp_header_shortcode_second'] != '')
                        $header_cls = 'has_arp_shortcode';
                    if (isset($columns['arp_header_shortcode_third']) && $columns['arp_header_shortcode_third'] != '')
                        $header_cls = 'has_arp_shortcode';

                    $columns_custom_ribbon_position = '';
                    if (isset($columns['ribbon_setting']) && $columns['ribbon_setting'] and $columns['ribbon_setting']['arp_ribbon'] != '' and ( $columns['ribbon_setting']['arp_ribbon_content'] != '' || $columns['ribbon_setting']['arp_ribbon_content_second'] != '' || $columns['ribbon_setting']['arp_ribbon_content_third'] != '' || $columns['ribbon_setting']['arp_custom_ribbon'] != '' || $columns['ribbon_setting']['arp_custom_ribbon_second'] || $columns['ribbon_setting']['arp_custom_ribbon'] != '' || $columns['ribbon_setting']['arp_custom_ribbon_third'])) {
                        if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_6') {
                            if ($columns['ribbon_setting']['arp_ribbon_position'] == 'left') {
                                if( isset($columns['ribbon_setting']['arp_ribbon_custom_position_rl']) && $columns['ribbon_setting']['arp_ribbon_custom_position_rl'] != ""){
                                    $columns_custom_ribbon_position .= "left:{$columns['ribbon_setting']['arp_ribbon_custom_position_rl']}px;";
                                }
                            } else {
                                if( isset($columns['ribbon_setting']['arp_ribbon_custom_position_rl']) && $columns['ribbon_setting']['arp_ribbon_custom_position_rl'] != ""){
                                    $columns_custom_ribbon_position .= "right:{$columns['ribbon_setting']['arp_ribbon_custom_position_rl']}px;";
                                }
                            }
                            if( isset($columns['ribbon_setting']['arp_ribbon_custom_position_top']) && $columns['ribbon_setting']['arp_ribbon_custom_position_top'] != ""){
                                $columns_custom_ribbon_position .= "top:{$columns['ribbon_setting']['arp_ribbon_custom_position_top']}px;";
                            }
                        }
                        $basic_col = $arp_mainoptionsarr['general_options']['arp_basic_colors'];
                        $ribbon_bg_col = $columns['ribbon_setting']['arp_ribbon_bgcol'];
                        $base_color = $ribbon_bg_col;
                        $base_color_key = array_search($base_color, $basic_col);
                        $gradient_color = $arp_mainoptionsarr['general_options']['arp_basic_colors_gradient'][$base_color_key];
                        $ribbon_border_color = $arp_mainoptionsarr['general_options']['arp_ribbon_border_color'][$base_color_key];
                        if ($columns['ribbon_setting']['arp_ribbon'] != 'arp_ribbon_6') {
                            $tablestring .= "<style type='text/css'>";
                            if (in_array($base_color, $basic_col)) {
                                if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_1') {
                                    $tablestring .= "#ArpTemplate_main.arp_front_main_container .arp_price_table_" . $template_name . " #main_" . $j . " .arp_ribbon_content:before, #ArpTemplate_main.arp_front_main_container .arp_price_table_" . $template_name . " #main_" . $j . " .arp_ribbon_content:after{";
                                    $tablestring .= "border-top-color:" . $gradient_color . " !important;";
                                    $tablestring .= "}";
                                }
                                if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_3') {
                                    $tablestring .= "#ArpTemplate_main.arp_front_main_container .arp_price_table_" . $template_name . " #main_" . $j . " .arp_ribbon_content:before,#ArpTemplate_main.arp_front_main_container .arp_price_table_" . $template_name . " #main_" . $j . " .arp_ribbon_content:after{";
                                    $tablestring .= "border-top-color:" . $base_color . " !important;";
                                    $tablestring .= "}";
                                    $tablestring .= "#ArpTemplate_main.arp_front_main_container .arp_price_table_" . $template_name . " #main_" . $j . " .arp_ribbon_content{";
                                    $tablestring .= "border-top:75px solid " . $base_color . ";";
                                    $tablestring .= "color:" . $columns['ribbon_setting']['arp_ribbon_txtcol'] . ";";
                                    $tablestring .= "}";
                                } else {
                                    if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_1') {
                                        $tablestring .= "#ArpTemplate_main.arp_front_main_container .arp_price_table_" . $template_name . " #main_" . $j . " .arp_ribbon_content{";
                                        $tablestring .= "background:" . $gradient_color . ";";
                                        $tablestring .= "background-color:" . $gradient_color . ";";
                                        $tablestring .= "background-image:-moz-linear-gradient(0deg," . $gradient_color . "," . $base_color . "," . $gradient_color . ");";
                                        $tablestring .= "background-image:-webkit-gradient(linear, 0 0, 0 0, color-stop(0%," . $gradient_color . "), color-stop(50%," . $base_color . "), color-stop(100%," . $gradient_color . "));";
                                        $tablestring .= "background-image:-webkit-linear-gradient(left," . $gradient_color . " 0%, " . $base_color . " 51%, " . $gradient_color . " 100%);";
                                        $tablestring .= "background-image:-o-linear-gradient(left," . $gradient_color . " 0%, " . $base_color . " 51%, " . $gradient_color . " 100%);";
                                        $tablestring .= "background-image:linear-gradient(90deg," . $gradient_color . "," . $base_color . ", " . $gradient_color . ");";
                                        $tablestring .= "background-image:-ms-linear-gradient(left," . $gradient_color . "," . $base_color . ", " . $gradient_color . ");";
                                        $tablestring .= "filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='" . $base_color . "', endColorstr='" . $gradient_color . "', GradientType=1);";
                                        $tablestring .= '-ms-filter: "progid:DXImageTransform.Microsoft.gradient (startColorstr="' . $base_color . '", endColorstr="' . $gradient_color . '", GradientType=1)";';
                                        $tablestring .= "background-repeat:repeat-x;";
                                        $tablestring .= "border-top:1px solid {$ribbon_border_color};";
                                        $tablestring .= "box-shadow:13px 1px 2px rgba(0,0,0,0.6);";
                                        $tablestring .= "-webkit-box-shadow:13px 1px 2px rgba(0,0,0,0.6);";
                                        $tablestring .= "-moz-box-shadow:13px 1px 2px rgba(0,0,0,0.6);";
                                        $tablestring .= "-o-box-shadow:13px 1px 2px rgba(0,0,0,0.6);";
                                        $tablestring .= "color:" . $columns['ribbon_setting']['arp_ribbon_txtcol'] . ";";
                                        $tablestring .= "text-shadow:0 0 1px rgba(0,0,0,0.4);";
                                        $tablestring .= "}";
                                    } else {
                                        $tablestring .= "#ArpTemplate_main.arp_front_main_container .arp_price_table_" . $template_name . " #main_" . $j . " .arp_ribbon_content{";
                                        $tablestring .= "background:" . $base_color . ";";
                                        $tablestring .= "background-color:" . $base_color . ";";
                                        $tablestring .= "background-image:-moz-linear-gradient(top, " . $base_color . ", " . $gradient_color . ");";
                                        $tablestring .= "background-image:-webkit-gradient(linear, 0 0, 0 100%, from(" . $base_color . "), to(" . $gradient_color . "));";
                                        $tablestring .= "background-image:-webkit-linear-gradient(top, " . $base_color . ", " . $gradient_color . ");";
                                        $tablestring .= "background-image:-o-linear-gradient(top, " . $base_color . ", " . $gradient_color . ");";
                                        $tablestring .= "background-image:linear-gradient(to bottom, " . $base_color . ", " . $gradient_color . ");";
                                        $tablestring .= "background-repeat:repeat-x;";
                                        $tablestring .= "filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='" . $base_color . "', endColorstr='" . $gradient_color . "', GradientType=0);";
                                        $tablestring .= '-ms-filter: "progid:DXImageTransform.Microsoft.gradient (startColorstr="' . $base_color . '", endColorstr="' . $gradient_color . '", GradientType=0)";';
                                        if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_2') {
                                            $tablestring .= "box-shadow:0 -1px 1px rgba(0,0,0,0.4);";
                                            $tablestring .= "-webkit-box-shadow:0 -1px 1px rgba(0,0,0,0.4);";
                                            $tablestring .= "-moz-box-shadow:0 -1px 1px rgba(0,0,0,0.4);";
                                            $tablestring .= "-o-box-shadow:0 -1px 1px rgba(0,0,0,0.4);";
                                        } else if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_5') {
                                            $tablestring .= "box-shadow:-2px 2px 1px rgba(0, 0, 0, 0.3);";
                                            $tablestring .= "-webkit-box-shadow:-2px 2px 1px rgba(0, 0, 0, 0.3);";
                                            $tablestring .= "-moz-box-shadow:-2px 2px 1px rgba(0, 0, 0, 0.3);";
                                            $tablestring .= "-o-box-shadow:-2px 2px 1px rgba(0, 0, 0, 0.3);";
                                        } else {
                                            $tablestring .= "box-shadow:0 0 3px rgba(0,0,0,0.3);";
                                            $tablestring .= "-webkit-box-shadow:0 0 3px rgba(0,0,0,0.3);";
                                            $tablestring .= "-moz-box-shadow:0 0 3px rgba(0,0,0,0.3);";
                                            $tablestring .= "-o-box-shadow:0 0 3px rgba(0,0,0,0.3);";
                                        }
                                        $tablestring .= "color:" . $columns['ribbon_setting']['arp_ribbon_txtcol'] . ";";
                                        $tablestring .= "}";
                                    }
                                }
                            } else {
                                if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_1' or $columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_3') {
                                    $tablestring .= ".arp_price_table_" . $template_name . " #main_" . $j . " .arp_ribbon_content:before,#main_" . $j . " .arp_ribbon_content:after{";
                                    $tablestring .= "border-top-color:" . $base_color . "  !important;";
                                    $tablestring .= "}";
                                }
                                if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_3') {
                                    $tablestring .= ".arp_price_table_" . $template_name . " #main_" . $j . " .arp_ribbon_content{";
                                    $tablestring .= "border-top:75px solid " . $base_color . ";";
                                    $tablestring .= "color:" . $columns['ribbon_setting']['arp_ribbon_txtcol'] . ";";
                                    $tablestring .= "}";
                                } else {
                                    $tablestring .= ".arp_price_table_" . $template_name . " #main_" . $j . " .arp_ribbon_content{";
                                    $tablestring .= "background:" . $base_color . ";";
                                    $tablestring .= "color:" . $columns['ribbon_setting']['arp_ribbon_txtcol'] . ";";
                                    $tablestring .= "}";
                                }
                            }
                            $tablestring .= "</style>";
                        }

                        $g = 0;
                        foreach($total_tabs as $key => $tab_name){

                            if( $g == 0 ){
                                $ribbon_content = 'arp_ribbon_content';
                                $custom_ribbon = 'arp_custom_ribbon';
                            } else {
                                $ribbon_content = 'arp_ribbon_content_'.$tab_name[2];
                                $custom_ribbon = 'arp_custom_ribbon_'.$tab_name[2];
                            }

                            array_push($font_awesome_match, @$columns['ribbon_setting'][$ribbon_content]);

                            $selected = ($g == $setas_default_toggle) ? 'toggle_selected active_toggle': '';

                            $ribbon_position = strtolower($columns['ribbon_setting']['arp_ribbon_position']);

                            if( (isset($columns['ribbon_setting'][$ribbon_content]) && $columns['ribbon_setting'][$ribbon_content] != '' && $columns['ribbon_setting']['arp_ribbon'] != 'arp_ribbon_6') || (isset($columns['ribbon_setting'][$custom_ribbon]) && $columns['ribbon_setting'][$custom_ribbon] != '' && $columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_6') ){
                                $tablestring .= "<div id='arp_ribbon_container_{$j}_{$g}' class='arp_ribbon_container {$selected} toggle_step_{$tab_name[2]} arp_ribbon_{$ribbon_position} {$columns['ribbon_setting']['arp_ribbon']}' style='{$columns_custom_ribbon_position}'>";
                                    
                                    $tablestring .= "<div class='arp_ribbon_content arp_ribbon_{$ribbon_position}'>";
                                        if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_3'){
                                            $tablestring .= "<span>";
                                        }
                                        if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_6') {
                                            $tablestring .= "<img src='" . $columns['ribbon_setting'][$custom_ribbon] . "' alt='" . $columns['ribbon_setting'][$custom_ribbon] . "' />";
                                        } else {
                                            $tablestring .= $columns['ribbon_setting'][$ribbon_content];
                                        }

                                        if ($columns['ribbon_setting']['arp_ribbon'] == 'arp_ribbon_3'){
                                            $tablestring .= "</span>";
                                        }
                                    $tablestring .= "</div>";

                                $tablestring .= "</div>";
                            }

                            $g++;
                        }

                        
                    }

                    if (!empty($columns['column_background_image']) && $ref_template === 'arptemplate_24') {
                        $column_level_bgi_class = ' hide_col_bg_img ';
                    } else {
                        $column_level_bgi_class = '';
                    }
                    $tablestring .= "<div class='arp_column_content_wrapper $column_level_bgi_class'>";

                    $tablestring .= arp_get_column_header_part( $columns, $ref_template, $template_feature, $j, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id );
                    
                    if( $columns['is_caption'] ){
                        $caption_array = arp_get_caption_feature_part( $columns, $template_name, $total_tabs, $maxrowcount, $column_settings );
                    }
                    $tablestring .= arp_get_column_feature_part( $columns, $ref_template, $template_feature, $j, $total_tabs, $setas_default_toggle, $maxrowcount, $x, $arp_unique_table_id, $column_settings, $table_id, $tooltip_display_style, $tooltip_trigger_type, $tooltip_icon_position, $tooltip_informative_icon, $caption_array, $template_name );

                    if( 'style_3' == $template_feature['amount_style'] ){
                        $tablestring .= arp_get_column_pricing_part( $columns, $ref_template, $template_feature, $j, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id );
                    }

                    if( 'default' == $template_feature['button_position'] ){
                        $tablestring .= arp_get_column_footer_part( $columns, $ref_template, $template_feature, $j, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id );
                    }

                    $tablestring .= "</div>";
                    $tablestring .= "</div>";
                    $tablestring .= "</div>";
                    $tablestring .= "</div>";

                    $c++;
                    if ($x % 5 == 0) {
                        $c = 1;
                    }
                    $x++;
                    $style_++;
                }
            }

            if (in_array(1, $col_array) and $column_animation['sticky_caption'] == 1 && $is_animation == 'yes') {
                $tablestring .= "</div>";
            }

            if( get_option('arp_enable_loader',0) && get_option('arp_enable_loader',0) != '' ){
                $tablestring .= '<div class="arp_template_loader_continer"><div class="arp_template_loader_wrapper"><div class="arp_template_loader_div"></div></div></div>';
            }
            $tablestring .= "</div>";

            if( $arp_is_lightbox ){
                $tablestring .= "<div class='arp_front_modal_overlay' style='text-align:center;'><div class='arp_video_content'></div></div>";
            }
        } else {
            $tablestring .= __('Please select valid table', 'ARPrice');
        }

        

        

        $tablestring .= "</div>";
        $tablestring .= "</div>";

        
        
        if ($navigation) {
            $tablestring .= "<div class='arp_next_div'>";
            $tablestring .= "<div id='arp_next_btn_" . $template_name . "_".$arp_unique_table_id."' class='arp_next_btn arp_nav_style_1'></div>";
            $tablestring .= "</div>";
        }
        if ($setact != 1) {
            $tablestring .= "<div style='float: left;width: 100%;clear: both;margin-top: 10px;'><span style='color:#FF0000; font-size:12px !important; text-align:center; display:block !important;'>Powered by <a href='http://codecanyon.net/item/arprice-ultimate-compare-pricing-table-plugin/10049883?ref=reputeinfosystems' target='_blank'>ARPrice</a></span></div>";
            $tablestring .= "<div><span style='color:#FF0000; font-size:12px !important; text-align:center; display:block !important;'>&nbsp;&nbsp;(Unlicensed)</span></div>";
        }
        $tablestring .= "</div>";

        $tablestring .= "</div>";



        if ($column_animation['is_animation'] == 'yes' and ( $column_animation['pagi_nav_btn'] == 'pagination_bottom' )){
            $tablestring .= "<div id='arp_pagination_wrapper'><div class='arp_pagination arp_pagination_top arp_paging_style_1' id='arp_slider_" . $id . "_paginatio_top_".$arp_unique_table_id."'></div></div>";
        }


        $tablestring = apply_filters('arp_postdisplay_pricingtable_filter', $tablestring, $table_id);


        do_action('arp_postdisplay_pt_action', $table_id);
        do_action('arp_postdisplay_pt_action' . $table_id, $table_id);

        global $arp_has_tooltip;

        if ($arp_has_tooltip == 1)
            $arp_inc_effect_css[] = 1;

        if($arp_has_tooltip==1){
             $tablestring .= '<input type="hidden" id="arp_tooltip_settings_arptemplate_'.$table_id.'" class="arp_tooltip_settings"  data-tooltip-bgcolor="'.$tooltip_bg_color.'" data-tooltip-width="'.$tooltip_width.'" data-tooltip-color="'.$general_option['tooltip_settings']['text_color'].'" data-tooltip-trigger-type="'.$tooltip_trigger_type.'" data-tooltip-position="'.$general_option['tooltip_settings']['position'].'" data-template-id="'.$table_id.'" data-animation-in="'.$animation_in.'" data-animation-out="'.$animation_out.'" data-tooltip-display-style="'.$tooltip_display_style.'"/>';
        }

        
        $inbuild = "";
        if ($setact == 0) {
            $inbuild = " (U)";
        }

        
        $fa_pattern = '/class\=(\'|")(arpfa|arpfar|arpfab|arpfas)\s(.*?)(\'|")/i'; 
        
        
        $mi_pattern = '/class\=(\'|")(material-icons)\s(.*?)(\'|")/i';
        
        
        $ti_pattern = '/class\=(\'|")(typcn)\s(.*?)(\'|")/i';
        
        
        $ic_pattern = '/class\=(\'|")(icon)\s(.*?)(\'|")/i';
        
        
        $filtered_font_awesome_match = array_values(array_filter($font_awesome_match));
        
        
        if (preg_grep($fa_pattern, $filtered_font_awesome_match) || $tooltip_display_style == 'informative' || $toggle_style == 1 || $toggle_style == 4 ) {
            global $arp_has_fontawesome;
            $arp_has_fontawesome = 1;
        }
        
        
        if( preg_grep($mi_pattern,$filtered_font_awesome_match)){
            global $arp_has_material_icons;
            $arp_has_material_icons = 1;
        }
        
        
        if( preg_grep($ti_pattern,$filtered_font_awesome_match)){
            global $arp_has_typicons;
            $arp_has_typicons = 1;
        }
        
        
        if( preg_grep($ic_pattern,$filtered_font_awesome_match)){
            global $arp_has_ionicons;
            $arp_has_ionicons = 1;
        }
        
        if (!empty($arp_inc_effect_css) && in_array('1', $arp_inc_effect_css)) {
            global $arp_effect_css;
            $arp_effect_css = 1;
        }

        $whole_table = $tablestring;
        $animate_num_pattern = "/(arp_price_amount)/";
        preg_match($animate_num_pattern, $whole_table, $matches);

        if (intval($general_settings['enable_toggle_price']) === 1 && in_array('arp_price_amount', $matches)) {
            $arp_animate_price = 1;
        }

        $tablestring .= "<div style='clear:both;'></div>";

        $tablestring .= '  
<!--Plugin Name: ARPrice    
    Plugin Version: ' . get_option('arprice_version') . ' ' . $inbuild . '
    Developed By: Repute Infosystems
    Developer URL: http://www.reputeinfosystems.com/
-->';

        $tablestring = preg_replace("~\r?~", "", $tablestring);
        $tablestring = preg_replace("~\r\n?~", "", $tablestring);
        $tablestring = preg_replace("/\n\n+/", "", $tablestring);
        $tablestring = preg_replace("|\n|", "", $tablestring);
        $tablestring = preg_replace("~\n~", "", $tablestring);

        $tablestring = $arp_pricingtable->arprice_font_icon_size_parser($tablestring);

        return $tablestring;
    }

}

function arp_get_column_header_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id ){

    global $arprice_default_settings,$font_awesome_match;

    $arp_front_column_header_part = '';

    $header_cls = '';

    $shortcode_class = '';
    $shortcode_class_array = $arprice_default_settings->arp_shortcode_custom_type();
    if (isset($columns['arp_shortcode_customization_style'])) {
        $columns['arp_shortcode_customization_size'] = isset($columns['arp_shortcode_customization_size']) ? $columns['arp_shortcode_customization_size'] : '';
        $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'] = isset($shortcode_class_array[$columns['arp_shortcode_customization_style']]['class']) ? $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'] : '';
        $shortcode_class = $columns['arp_shortcode_customization_size'] . ' ' . $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'];
    }
    
    $arp_front_column_header_part .= "<div class='arpcolumnheader'>";

        if( 'position_1' == $template_feature['header_shortcode_position'] ){
            $g = 0;
            foreach( $total_tabs as $key => $tab_name ){
                $selected = ($g == $setas_default_toggle) ? 'toggle_selected active_toggle': '';
                $arp_front_column_header_part .= "<div class='arp_header_shortcode toggle_step_".$tab_name[2]." ".$selected."'>";
                    if( $g == 0 ){
                        if( 'normal' == $template_feature['header_shortcode_type'] ){
                            $arp_front_column_header_part .= isset($columns['arp_header_shortcode']) ? do_shortcode($columns['arp_header_shortcode']) : '';
                        } else {
                            $arp_front_column_header_part .= "<div class='rounded_corner_wrapper ".$shortcode_class."'>";
                                $arp_front_column_header_part .= "<div class='rounded_corder ".$shortcode_class."'>";
                                    $arp_front_column_header_part .= isset($columns['arp_header_shortcode']) ? do_shortcode($columns['arp_header_shortcode']) : "";
                                $arp_front_column_header_part .= "</div>";
                            $arp_front_column_header_part .= "</div>";
                        }
                        if( isset($columns['arp_header_shortcode']) ){
                            array_push($font_awesome_match,$columns['arp_header_shortcode']);
                        }
                    } else {
                        if( 'normal' == $template_feature['header_shortcode_type'] ){
                            $arp_front_column_header_part .= isset($columns['arp_header_shortcode_'.$tab_name[2]]) ? do_shortcode($columns['arp_header_shortcode_'.$tab_name[2]]) : '';
                        } else {
                            $arp_front_column_header_part .= "<div class='rounded_corner_wrapper ".$shortcode_class."'>";
                                $arp_front_column_header_part .= "<div class='rounded_corder ".$shortcode_class."'>";
                                    $arp_front_column_header_part .= isset($columns['arp_header_shortcode_'.$tab_name[2]]) ? do_shortcode($columns['arp_header_shortcode_'.$tab_name[2]]) : "";
                                $arp_front_column_header_part .= "</div>";
                            $arp_front_column_header_part .= "</div>";
                        }
                        if( isset($columns['arp_header_shortcode_'.$tab_name[2]])){
                            array_push($font_awesome_match,$columns['arp_header_shortcode_'.$tab_name[2]]);
                        }
                    }
                $arp_front_column_header_part .= "</div>";

                $g++;
            }
        }

        if( isset($columns['is_caption']) && 1 == $columns['is_caption'] ){
            $arp_front_column_header_part .= "<div class='arpcaptiontitle'>";
                
                $g = 0;
                foreach($total_tabs as $key => $tab_name){
                    $selected = ($g == $setas_default_toggle) ? 'toggle_selected active_toggle' : '';
                    $arp_front_column_header_part .= "<div class='html_content_{$tab_name[2]} toggle_step_{$tab_name[2]} {$selected}'>";
                        if( $g == 0 ){
                            $arp_front_column_header_part .= isset($columns['html_content']) ? do_shortcode($columns['html_content']) : '';
                            array_push($font_awesome_match, @$columns['html_content']);
                        } else {
                            $arp_front_column_header_part .= isset($columns['html_content_'.$tab_name[2]]) ? do_shortcode($columns['html_content_'.$tab_name[2]]) : '';
                            array_push($font_awesome_match, @$columns['html_content_'.$tab_name[2]]);
                        }
                    $arp_front_column_header_part .= "</div>";
                    $g++;
                }
            $arp_front_column_header_part .= "</div>";
            array_push($font_awesome_match,$columns['html_content']);
        } else {
            $arp_front_column_header_part .= "<div class='arppricetablecolumntitle'>";
                $g = 0;
                foreach($total_tabs as $key => $tab_name){
                    $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                    $arp_front_column_header_part .= "<div class='bestPlanTitle package_title_".$tab_name[2]." toggle_step_".$tab_name[2]." ".$selected."'>";
                        if( $g == 0 ){
                            $arp_front_column_header_part .= isset($columns['package_title']) ? do_shortcode($columns['package_title']) : "";
                            array_push($font_awesome_match, $columns['package_title']);
                        } else {
                            $arp_front_column_header_part .= isset($columns['package_title_'.$tab_name[2]]) ? do_shortcode($columns['package_title_'.$tab_name[2]]) : "";
                            if( isset($columns['package_title_'.$tab_name[2]]) ){
                                array_push($font_awesome_match, $columns['package_title_'.$tab_name[2]]);
                            }
                        }
                    $arp_front_column_header_part .= "</div>";
                    $g++;
                }

                if( 'enable' == $template_feature['column_description'] && ('style_1' == $template_feature['column_description_style']) ){
                    $arp_front_column_header_part .= arp_get_column_description_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle);
                }

            $arp_front_column_header_part .= "</div>";

            if( 'enable' == $template_feature['column_description'] && ('style_3' == $template_feature['column_description_style']) ){
                $arp_front_column_header_part .= arp_get_column_description_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle );
            }

            if( 'position_2' == $template_feature['button_position'] ){
                $arp_front_column_header_part .= arp_get_column_footer_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id );
            }

            if( 'default' == $template_feature['header_shortcode_position'] ){
                $arp_front_column_header_part .= arp_get_column_header_shortcode_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle );
            }

            if( 'style_3' != $template_feature['amount_style'] ){
                $arp_front_column_header_part .= arp_get_column_pricing_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id );
            }
        }

        if( 'position_2' == $template_feature['header_shortcode_position'] ){
            $arp_front_column_header_part .= arp_get_column_header_shortcode_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle );
        }

    $arp_front_column_header_part .= "</div>";
    
    return $arp_front_column_header_part;
}

function arp_get_column_header_shortcode_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle ){
    global $arprice_default_settings,$font_awesome_match;
    $arp_column_shortcode_part = '';

    $shortcode_class = '';
    $shortcode_class_array = $arprice_default_settings->arp_shortcode_custom_type();
    if (isset($columns['arp_shortcode_customization_style'])) {
        $columns['arp_shortcode_customization_size'] = isset($columns['arp_shortcode_customization_size']) ? $columns['arp_shortcode_customization_size'] : '';
        $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'] = isset($shortcode_class_array[$columns['arp_shortcode_customization_style']]['class']) ? $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'] : '';
        $shortcode_class = $columns['arp_shortcode_customization_size'] . ' ' . $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'];
    }

    if( 'normal' == $template_feature['header_shortcode_type'] ){
        $g = 0;
        foreach( $total_tabs as $key => $tab_name ){
            $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
            
            $arp_column_shortcode_part .= "<div class='arp_header_shortcode toggle_step_{$tab_name[2]} {$selected}'>";
                if( $g == 0 ){
                    $arp_column_shortcode_part .= isset($columns['arp_header_shortcode']) ? do_shortcode($columns['arp_header_shortcode']) : '';
                    if( isset($columns['arp_header_shortcode']) ){
                        array_push($font_awesome_match,$columns['arp_header_shortcode']);
                    }
                } else {
                    $arp_column_shortcode_part .= isset($columns['arp_header_shortcode_'.$tab_name[2]]) ? do_shortcode($columns['arp_header_shortcode_'.$tab_name[2]]) : "";
                    if( isset($columns['arp_header_shortcode_'.$tab_name[2]]) ){
                        array_push($font_awesome_match,$columns['arp_header_shortcode_'.$tab_name[2]]);
                    }
                }
            $arp_column_shortcode_part .= "</div>";

            $g++;
        }
    } else if( 'rounded_border' == $template_feature['header_shortcode_type'] ) {
        $g = 0;
        foreach( $total_tabs as $key => $tab_name ){
            $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
            
            $arp_column_shortcode_part .= "<div class='arp_rounded_{$tab_name[2]} arp_rounded_shortcode_wrapper toggle_step_{$tab_name[2]} {$selected}'>";
                $arp_column_shortcode_part .= "<div class='rounded_corner_wrapper {$shortcode_class}'>";
                    $arp_column_shortcode_part .= "<div class='rounded_corder {$shortcode_class}'>";
                        if( $g == 0 ){
                            $arp_column_shortcode_part .= isset($columns['arp_header_shortcode']) ? do_shortcode($columns['arp_header_shortcode']) : '';
                            if( isset($columns['arp_header_shortcode'])){
                                array_push($font_awesome_match,$columns['arp_header_shortcode']);
                            }
                        } else {
                            $arp_column_shortcode_part .= isset($columns['arp_header_shortcode_'.$tab_name[2]]) ? do_shortcode($columns['arp_header_shortcode_'.$tab_name[2]]) : "";
                            if( isset($columns['arp_header_shortcode_'.$tab_name[2]]) ){
                                array_push($font_awesome_match,$columns['arp_header_shortcode_'.$tab_name[2]]);
                            }
                        }
                    $arp_column_shortcode_part .= "</div>";
                $arp_column_shortcode_part .= "</div>";
            $arp_column_shortcode_part .= "</div>";

            $g++;
        }
    }

    return $arp_column_shortcode_part;
}

function arp_get_column_pricing_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id ){


    global $arprice_default_settings,$font_awesome_match;

    $arp_column_pricing_part = '';

    $shortcode_class = '';
    $shortcode_class_array = $arprice_default_settings->arp_shortcode_custom_type();
    if (isset($columns['arp_shortcode_customization_style'])) {
        $columns['arp_shortcode_customization_size'] = isset($columns['arp_shortcode_customization_size']) ? $columns['arp_shortcode_customization_size'] : '';
        $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'] = isset($shortcode_class_array[$columns['arp_shortcode_customization_style']]['class']) ? $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'] : '';
        $shortcode_class = $columns['arp_shortcode_customization_size'] . ' ' . $shortcode_class_array[$columns['arp_shortcode_customization_style']]['class'];
    }

    if (!empty($columns['column_background_image']) && $ref_template_id == 'arptemplate_21') {
        $column_bgi_class = ' hide_col_bg_img ';
    } else {
        $column_bgi_class = '';
    }

    $arp_column_pricing_part .= "<div class='arppricetablecolumnprice " . $column_bgi_class . " " . $template_feature['amount_style'] . "' >"; 

        $ref_template_arr = array( 'arptemplate_1', 'arptemplate_21', 'arptemplate_22', 'arptemplate_23', 'arptemplate_24', 'arptemplate_10', 'arptemplate_16', 'arptemplate_14', 'arptemplate_13', 'arptemplate_11', 'arptemplate_8', 'arptemplate_6', 'arptemplate_4', 'arptemplate_2', 'arptemplate_20', 'arptemplate_7', 'arptemplate_5', 'arptemplate_9', 'arptemplate_15' );

        if( 'arp_default' == $template_feature['amount_style'] ){
            if( 'arptemplate_4' == $ref_template_id ){
                $arp_column_pricing_part .="<div class='rounded_corner_wrapper " . $shortcode_class . "'>";
                    $arp_column_pricing_part .= "<div class='arp_price_wrapper rounded_corder $shortcode_class' data-column='main_" . $col_id . "'>";
            } else {
                $arp_column_pricing_part .= "<div class='arp_price_wrapper' data-column='main_" . $col_id . "'>";
            }

            if( 'arptemplate_3' != $ref_template_id ){
                $g = 0;
                foreach( $total_tabs as $key => $tab_name ){
                    $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                            
                    $arp_column_pricing_part .= "<span class='price_text_{$tab_name[2]}_step toggle_step_{$tab_name[2]} {$selected}'>";
                        if( $g == 0 ){
                            $arp_column_pricing_part .= isset($columns['price_text']) ? do_shortcode($columns['price_text']) : '';
                            array_push($font_awesome_match,$columns['price_text']);
                        } else {
                            $arp_column_pricing_part .= isset($columns['price_text_'.$tab_name[3].'_step']) ? do_shortcode($columns['price_text_'.$tab_name[3].'_step']) : "";
                            if( isset($columns['price_text_'.$tab_name[3].'_step']) ){
                                array_push($font_awesome_match,$columns['price_text_'.$tab_name[3].'_step']);
                            }
                        }
                    $arp_column_pricing_part .= "</span>";

                    $g++;
                }

                if( 'arptemplate_4' == $ref_template_id ){
                    $arp_column_pricing_part .= "</div>";
                }
            } else if( 'arptemplate_4' == $ref_template_id ){
                $arp_column_pricing_part .= "<div class='arpmain_price'>";
                    $arp_column_pricing_part .= "<div class='arp_pricerow $column_bgi_class'>";
                        $arp_column_pricing_part .= "<span class='arp_price_value'>";
                            $g = 0;
                            foreach( $total_tabs as $key => $tab_name ){
                                $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                                
                                $arp_column_pricing_part .= "<span class='price_text_{$tab_name[2]}_step arp_price_value_text toggle_step_{$tab_name[2]} {$selected}'>";
                                    if( $g == 0 ){
                                        $arp_column_pricing_part .= isset($columns['price_text']) ? do_shortcode($columns['price_text']) : '';
                                        array_push($font_awesome_match,$columns['price_text']);
                                    } else {
                                        $arp_column_pricing_part .= isset($columns['price_text_'.$tab_name[3].'_step']) ? do_shortcode($columns['price_text_'.$tab_name[3].'_step']) : "";
                                        if( isset($columns['price_text_'.$tab_name[3].'_step']) ){
                                            array_push($font_awesome_match,$columns['price_text_'.$tab_name[3].'_step']);
                                        }
                                    }
                                $arp_column_pricing_part .= "</span>";

                                $g++;
                            }
                        $arp_column_pricing_part .= "</span>";

                        $arp_column_pricing_part .= "<span class='arp_price_duration'>";

                            $g = 0;
                            foreach( $total_tabs as $key => $tab_name ){
                                $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                                
                                $arp_column_pricing_part .= "<span class='price_text_{$tab_name[2]}_step arp_price_duration_text toggle_step_{$tab_name[2]} {$selected}'>";
                                    if( $g == 0 ){
                                        $arp_column_pricing_part .= isset($columns['price_label']) ? do_shortcode($columns['price_label']) : '';
                                        array_push($font_awesome_match,$columns['price_label']);
                                    } else {
                                        $arp_column_pricing_part .= isset($columns['price_text_input_'.$tab_name[3].'_step_label']) ? do_shortcode($columns['price_text_'.$tab_name[3].'_step_label']) : "";
                                        array_push($font_awesome_match,@$columns['price_text_input_'.$tab_name[3].'_step_label']);
                                    }
                                $arp_column_pricing_part .= "</span>";

                                $g++;
                            }

                        $arp_column_pricing_part .= "</span>";

                    $arp_column_pricing_part .= "</div>";

                $arp_column_pricing_part .= "</div>";
            } else {
                $arp_column_pricing_part .= "<span class='arp_price_value'>";

                    $g = 0;
                    foreach( $total_tabs as $key => $tab_name ){
                        $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                        
                        $arp_column_pricing_part .= "<span class='price_text_{$tab_name[2]}_step arp_price_value_text toggle_step_{$tab_name[2]} {$selected}'>";
                            if( $g == 0 ){
                                $arp_column_pricing_part .= isset($columns['price_text']) ? do_shortcode($columns['price_text']) : '';
                                if( isset( $columns['price_text'] ) ){
                                    array_push($font_awesome_match,$columns['price_text']);
                                }
                            } else {
                                $arp_column_pricing_part .= isset($columns['price_text_'.$tab_name[3].'_step']) ? do_shortcode($columns['price_text_'.$tab_name[3].'_step']) : "";
                                if( isset( $columns['price_text_'.$tab_name[3].'_step'] ) ){
                                    array_push($font_awesome_match,$columns['price_text_'.$tab_name[3].'_step']);
                                }
                            }
                        $arp_column_pricing_part .= "</span>";

                        $g++;
                    }

                $arp_column_pricing_part .= "</span>";

                $arp_column_pricing_part .= "<span class='arp_price_duration'>";
                    $g = 0;
                    foreach( $total_tabs as $key => $tab_name ){
                        $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                        
                        $arp_column_pricing_part .= "<span class='price_text_{$tab_name[2]}_step arp_price_duration_text toggle_step_{$tab_name[2]} {$selected}'>";
                            if( $g == 0 ){
                                $arp_column_pricing_part .= isset($columns['price_label']) ? do_shortcode($columns['price_label']) : '';
                                if( isset($columns['price_label']) ){
                                    array_push($font_awesome_match,$columns['price_label']);
                                }
                            } else {
                                $arp_column_pricing_part .= isset($columns['price_text_input_'.$tab_name[3].'_step_label']) ? do_shortcode($columns['price_text_input_'.$tab_name[3].'_step_label']) : "";
                                if( isset( $columns['price_text_input_'.$tab_name[3].'_step_label'] ) ){
                                    array_push($font_awesome_match,$columns['price_text_input_'.$tab_name[3].'_step_label']);
                                }
                            }
                        $arp_column_pricing_part .= "</span>";

                        $g++;
                    }

                $arp_column_pricing_part .= "</span>";
            }
            $arp_column_pricing_part .= "</div>";

            $arp_column_pricing_part .= isset($columns['html_content']) ? $columns['html_content'] : "";
        } else if( 'style_1' == $template_feature['amount_style'] ){
            $arp_column_pricing_part .= "<div class='arp_pricename'>";
                $arp_column_pricing_part .= "<div class='arp_price_wrapper' data-column='main_" . $col_id . "'>";

                    $g = 0;
                    foreach( $total_tabs as $key => $tab_name ){
                        $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                        $arp_column_pricing_part .= "<span class='price_text_{$tab_name[2]}_step arp_price_value_text toggle_step_{$tab_name[2]} {$selected}'>";
                        if( $g == 0 ){
                            $arp_column_pricing_part .= isset($columns['price_text']) ? do_shortcode($columns['price_text']) : '';
                            array_push($font_awesome_match,$columns['price_text']);
                        } else {
                            $arp_column_pricing_part .= isset($columns['price_text_'.$tab_name[3].'_step']) ? do_shortcode($columns['price_text_'.$tab_name[3].'_step']) : "";
                            if( isset($columns['price_text_'.$tab_name[3].'_step']) ){
                                array_push($font_awesome_match,$columns['price_text_'.$tab_name[3].'_step']);
                            }
                        }
                        $arp_column_pricing_part .= "</span>";
                        $g++;
                }

                $arp_column_pricing_part .= "</div>";
            $arp_column_pricing_part .= "</div>";
        } else if( 'style_2' == $template_feature['amount_style'] ){
            $arp_column_pricing_part .= "<div class='arp_price_wrapper' data-column='main_".$col_id."'>";

                $arp_column_pricing_part .= "<span class='arp_price_duration'>";

                    $g = 0;
                    foreach( $total_tabs as $key => $tab_name ){
                        $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                        
                        $arp_column_pricing_part .= "<span class='price_text_{$tab_name[2]}_step arp_price_duration_text toggle_step_{$tab_name[2]} {$selected}'>";
                            if( $g == 0 ){
                                $arp_column_pricing_part .= isset($columns['price_label']) ? do_shortcode($columns['price_label']) : '';
                                array_push($font_awesome_match,$columns['price_label']);
                            } else {
                                $arp_column_pricing_part .= isset($columns['price_text_input_'.$tab_name[3].'_step_label']) ? do_shortcode($columns['price_text_'.$tab_name[3].'_step_label']) : "";
                                if( isset($columns['price_label_'.$tab_name[3].'_step']) ){
                                    array_push($font_awesome_match,$columns['price_label_'.$tab_name[3].'_step']);
                                }
                            }
                        $arp_column_pricing_part .= "</span>";

                        $g++;
                    }

                $arp_column_pricing_part .= "</span>";

                $arp_column_pricing_part .= "<span class='arp_price_value'>";

                    $g = 0;
                    foreach( $total_tabs as $key => $tab_name ){
                        $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                        
                        $arp_column_pricing_part .= "<span class='price_text_{$tab_name[2]}_step arp_price_value_text toggle_step_{$tab_name[2]} {$selected}'>";
                            if( $g == 0 ){
                                $arp_column_pricing_part .= isset($columns['price_text']) ? do_shortcode($columns['price_text']) : '';
                                array_push($font_awesome_match,$columns['price_text']);
                            } else {
                                $arp_column_pricing_part .= isset($columns['price_text_'.$tab_name[3].'_step']) ? do_shortcode($columns['price_text_'.$tab_name[3].'_step']) : "";
                                if( isset($columns['price_text_'.$tab_name[3].'_step']) ){
                                    array_push($font_awesome_match,$columns['price_text_'.$tab_name[3].'_step']);
                                }
                            }
                        $arp_column_pricing_part .= "</span>";

                        $g++;
                    }

                $arp_column_pricing_part .= "</span>";

            $arp_column_pricing_part .= "</div>";

            $arp_column_pricing_part .= do_shortcode(isset($columns['html_content']) ? $columns['html_content'] : "" );
            array_push($font_awesome_match,$columns['html_content']);
        } else if( 'style_3' == $template_feature['amount_style'] ){
            $arp_column_pricing_part .= "<div class='arp_price_wrapper' data-column='main_".$col_id."'>";
                $g = 0;
                foreach ($total_tabs as $key => $tab_name) {
                    $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";

                    $arp_column_pricing_part .= "<span class='price_text_{$tab_name[2]}_step arp_price_value_text toggle_step_{$tab_name[2]} {$selected}'>";
                    if ($g == 0) {
                        $arp_column_pricing_part .= isset($columns['price_text']) ? do_shortcode($columns['price_text']) : '';
                        if( isset($columns['price_text']) ){
                            array_push($font_awesome_match, $columns['price_text']);
                        }
                    } else {
                        $arp_column_pricing_part .= isset($columns['price_text_' . $tab_name[3] . '_step']) ? do_shortcode($columns['price_text_' . $tab_name[3] . '_step']) : "";
                        if( isset( $columns['price_text_' . $tab_name[3].'_step']) ){
                            array_push($font_awesome_match, $columns['price_text_' . $tab_name[3] . '_step']);
                        }
                    }
                    $arp_column_pricing_part .= "</span>";

                    $g++;
                }
            $arp_column_pricing_part .= "</div>";
            $arp_column_pricing_part .= isset( $columns['html_content'] ) ? do_shortcode($columns['html_content']) : '';

            if( 'position_4' == $template_feature['button_position'] ){
                $arp_column_pricing_part .= arp_get_column_footer_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id );
            }
        }


        if( 'enable' == $template_feature['column_description'] && ('style_2' == $template_feature['column_description_style'] || 'style_4' == $template_feature['column_description_style'] ) ){
            if( 'style_2' == $template_feature['column_description_style'] ){
                $arp_column_pricing_part .= "<div class='custom_ribbon_wrapper'>";
            }
            $arp_column_pricing_part .= arp_get_column_description_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle );
            if( 'style_2' == $template_feature['column_description_style'] ){
                $arp_column_pricing_part .= "</div>";
            }
        }

        if( 'position_1' == $template_feature['button_position'] ){
            $arp_column_pricing_part .= arp_get_column_footer_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id );
        }

    $arp_column_pricing_part .= "</div>";

    return $arp_column_pricing_part;

}

function arp_get_caption_feature_part( $columns, $template_name, $total_tabs, $maxrowcount, $column_settings ){
    
    $row_order = isset($columns['row_order']) ? $columns['row_order'] : array();

    $caption_array = array();
    
    for ($ri = 0; $ri <= $maxrowcount; $ri++) {
        $rows = isset($columns['rows']['row_' . $ri]) ? $columns['rows']['row_' . $ri] : array();
        $g = 0;
        foreach($total_tabs as $key => $tab_name){

            $tooltip_key = ($g == 0 ) ? 'row_tooltip' : "row_tooltip_{$tab_name[2]}";
            $desc_key = ($g == 0) ? 'row_description' : "row_description_{$tab_name[2]}";
            $cap_des_key = ($g == 0 ) ? 'description' : "description_{$tab_name[2]}";

            if(!$column_settings['hide_caption_column']){
                $caption_array[$template_name][$ri][$cap_des_key] = (( isset($rows[$desc_key]) && $rows[$desc_key] != '' ) ? stripslashes_deep($rows[$desc_key]) : '');
                
                $caption_array[$template_name][$ri][$tooltip_key] = (( isset($rows[$tooltip_key]) && $rows[$tooltip_key] != '' ) ? stripslashes_deep($rows[$tooltip_key]) : '');
                
            }

            $g++;

        }
    }

    return $caption_array;
}

function arp_get_column_feature_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $maxrowcount, $x, $arp_unique_table_id, $column_settings, $table_id, $tooltip_display_style, $tooltip_trigger_type, $tooltip_icon_position, $tooltip_informative_icon, $caption_array, $template_name ){

    global $font_awesome_match;
    $arp_column_feature_part = '';
    $caption_hidden_html = '';
    $arp_column_feature_part .= "<div class='arpbody-content arppricingtablebodycontent'>";

        if( 'position_3' == $template_feature['button_position'] ){
            $arp_column_feature_part .= arp_get_column_description_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle );
            $arp_column_feature_part .= arp_get_column_footer_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id );
        }

        $arp_column_feature_part .= "<ul class='arp_opt_options arppricingtablebodyoptions' id='" . $x . "' style='text-align:" . $columns['body_text_alignment'] . "'>";

        $r = 0;
        $row_order = isset($columns['row_order']) ? $columns['row_order'] : array();
        if ($row_order && is_array($row_order)) {
            $rows = array();
            asort($row_order);
            $ji = 0;
            $maxorder = max($row_order) ? max($row_order) : 0;
            foreach ($columns['rows'] as $rowno => $row) {
                $row_order[$rowno] = isset($row_order[$rowno]) ? $row_order[$rowno] : ($maxorder + 1);
            }

            foreach ($row_order as $row_id => $order_id) {
                if ($columns['rows'][$row_id]) {
                    $rows['row_' . $ji] = $columns[$col_id]['rows'][$row_id];
                    $ji++;
                }
            }

            $columns[$col_id]['rows'] = $rows;
        }
        $last_li_cls = '';

        for( $ri = 0; $ri <= $maxrowcount; $ri++ ){

            $rows = isset($columns['rows']['row_' . $ri]) ? $columns['rows']['row_' . $ri] : array();
            
            if ($columns['is_caption'] == 1) {
                if (($ri + 1) % 2 == 0) {
                    $cls = 'rowlightcolorstyle';
                } else {
                    $cls = '';
                }
            } else {
                if ($x % 2 == 0) {
                    if (($ri + 1) % 2 == 0) {
                        $cls = 'rowdarkcolorstyle';
                    } else {
                        $cls = '';
                    }
                } else {
                    if (($ri + 1) % 2 == 0) {
                        $cls = 'rowlightcolorstyle';
                    } else {
                        $cls = '';
                    }
                }
            }

            if (($ri + 1 ) % 2 == 0) {
                $cls .= " arp_even_row";
            } else {
                $cls .= " arp_odd_row";
            }

            $rows['row_tooltip'] = isset($rows['row_tooltip']) ? $rows['row_tooltip'] : '';
            $rows['row_tooltip_second'] = isset($rows['row_tooltip_second']) ? $rows['row_tooltip_second'] : '';
            $rows['row_tooltip_third'] = isset($rows['row_tooltip_third']) ? $rows['row_tooltip_third'] : '';


            $last_li_cls = $cls;
            if ($rows['row_tooltip'] != '' || $rows['row_tooltip_second'] != "" || $rows['row_tooltip_third'] != "") {
                global $arp_has_tooltip;
                $arp_has_tooltip = 1;
            }

            $fa_pattern = '/class\=\'(arpfa)\s(.*?)\'/i';

            $columns['column_title'] = isset($columns['column_title']) ? $columns['column_title'] : "";
            array_push($font_awesome_match,$columns['column_title']);
            $columns['html_content'] = isset($columns['html_content']) ? $columns['html_content'] : "";
            array_push($font_awesome_match,$columns['html_content']);

            $row_inline_script_old = isset($rows['row_custom_css']) ? $rows['row_custom_css'] : '';
            $row_inline_script_old = trim($row_inline_script_old);
            $row_inline_script_old = str_replace("/\r|\n/", "", $row_inline_script_old);
            $row_inline_script_old = explode(';', $row_inline_script_old);
            $row_inline_script = '';
            
            if (!empty($row_inline_script_old)) {
                foreach ($row_inline_script_old as $new_css) {
                    if ($new_css != '') {
                        $new_css = explode(':', $new_css);
                        if (isset($new_css[0]) && isset($new_css[1])) {

                            $row_inline_script .= trim(@$new_css[0]) . ' : ' . trim(str_replace("!important", "", @$new_css[1])) . ' ;';
                        }
                    }
                }
            }

            $arp_li_content_type = ( isset($rows['row_content_type']) && $rows['row_content_type'] != '' ) ? $rows['row_content_type'] : 0;

            if ( 'style_1' ==  $template_feature['caption_style'] && 'default' != $template_feature['list_alignment'] ) {

                $arp_column_feature_part .= "<li class='" . $cls;
                if ($rows['row_tooltip'] != "" || $rows['row_tooltip_second'] != "" || $rows['row_tooltip_third'] != "") {
                    $arp_column_feature_part .= " arp_tooltip_li";
                }
                $li_class = $ref_template_id . '_' . $col_id . '_row_' . $ri;
                $arp_column_feature_part .= " " . $li_class . "' id='arp_" . $col_id . '_row_' . $ri . "' style='" . $row_inline_script . "'>";
                    $arp_column_feature_part .= "<span class='caption_li'>";
                        $g = 0;
                        foreach($total_tabs as $key => $tab_name){
                            $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";

                            $arp_column_feature_part .= "<div class='row_label_{$tab_name[2]}_step arp_caption_li_text toggle_step_{$tab_name[2]} {$selected}'>";

                            if( $g == 0 ){
                                $arp_column_feature_part .= isset($rows['row_label']) ? $rows['row_label'] : "";
                                if( isset($columns['row_label']) ){
                                    array_push($font_awesome_match, $columns['row_label']);
                                }
                            } else {
                                $arp_column_feature_part .= isset($rows['row_label_'.$tab_name[2]]) ? $rows['row_label_'.$tab_name[2]] : "";
                                if( $columns['row_label_'.$tab_name[2]] ){
                                    array_push($font_awesome_match, $columns['row_label_'.$tab_name[2]]);
                                }
                            }

                            $arp_column_feature_part .= "</div>";
                            $g++;
                        }
                    $arp_column_feature_part .= "</span>";

                    $arp_column_feature_part .= "<span class='caption_detail'>";

                        $g = 0;
                        foreach($total_tabs as $key => $tab_name){
                            $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";

                            $tooltip_key = ($g == 0 ) ? 'row_tooltip' : "row_tooltip_{$tab_name[2]}";
                            $desc_key = ($g == 0) ? 'row_description' : "row_description_{$tab_name[2]}";
                            if( isset( $rows[$tooltip_key] ) ){
                                array_push($font_awesome_match, $rows[$tooltip_key]);
                            }
                            if( isset( $rows[$desc_key] ) ){
                                array_push($font_awesome_match, $rows[$desc_key]);
                            }

                            $arp_column_feature_part .= "<div class='row_description_".$tab_name[2]."_step arp_caption_detail_text toggle_step_".$tab_name[2]." ".$selected;

                            if( "" != $rows[$tooltip_key] && isset($rows[$desc_key]) && '' != $rows[$desc_key] ){
                                $arp_column_feature_part .= " arp_tooltip ";
                                if( 'click' == $tooltip_trigger_type ){
                                    $arp_column_feature_part .= " on_click ";
                                }

                                if( 'informative' == $tooltip_display_style ){
                                    $arp_column_feature_part .= " arp_informative_tooltip arp_tooltip ";
                                    if( 'right_align' ==  $tooltip_icon_position ){
                                        $arp_column_feature_part .= " arp_informative_right_align ";
                                    } else {
                                        $arp_column_feature_part .= " arp_informative_float_to_content ";
                                    }
                                }
                            }

                            $arp_column_feature_part .= "' data-tipso='";
                            if( "" != $rows[$tooltip_key] && 'default' == $tooltip_display_style && (isset($rows[$desc_key]) && '' != $rows[$desc_key]) ){
                                $arp_column_feature_part .= esc_html($rows[$tooltip_key]);
                            }
                            $arp_column_feature_part .= "' >";

                                if( 1 == $arp_li_content_type ){

                                } else {
                                    $arp_column_feature_part .= (( isset($rows[$desc_key]) && $rows[$desc_key] != '' ) ? stripslashes_deep($rows[$desc_key]) : '');
                                }

                                if ($tooltip_display_style == 'informative' && $rows[$tooltip_key] != '' && ( isset($rows[$desc_key]) && $rows[$desc_key] != '' )) {
                                    if ($tooltip_trigger_type == 'click') {
                                        $informative_cls = " on_click";
                                    }
                                    $arp_column_feature_part .= "<label class='arp_informative_tooltip $informative_cls' data-tipso='" . esc_html($rows[$tooltip_key]) . "'><span>" . stripslashes($tooltip_informative_icon) . "</span></label>";
                                }

                            $arp_column_feature_part .= "</div>";
                            $g++;

                        }

                    $arp_column_feature_part .= "</span>";
                $arp_column_feature_part .= "</li>";

            } else if( 'style_2' == $template_feature['caption_style'] ){

                $arp_column_feature_part .= "<li class='".$cls;
                if ($rows['row_tooltip'] != "" || $rows['row_tooltip_second'] != "" || $rows['row_tooltip_third'] != "") {
                    $arp_column_feature_part .= " arp_tooltip_li";
                }
                $li_class = $ref_template . '_' . $col_id . '_row_' . $ri;
                $arp_column_feature_part .= " " . $li_class . "' id='arp_" . $col_id . '_row_' . $ri . "'";

                $arp_column_feature_part .= " style='" . $row_inline_script . "'>";

                    $arp_column_feature_part .= "<span class='caption_detail'>";

                        $g = 0;
                        foreach( $total_tabs as $key => $tab_name ){
                            $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";

                            $tooltip_key = ($g == 0 ) ? 'row_tooltip' : "row_tooltip_{$tab_name[2]}";
                            $desc_key = ($g == 0) ? 'row_description' : "row_description_{$tab_name[2]}";

                            if( isset( $columns[$tooltip_key] ) ){
                                array_push($font_awesome_match, $columns[$tooltip_key]);
                            }

                            if( isset( $columns[$desc_key] ) ){
                                array_push($font_awesome_match, $columns[$desc_key]);
                            }

                            $arp_column_feature_part .= "<div class='row_description_{$tab_name[2]}_step arp_caption_detail_text toggle_step_{$tab_name[2]} {$selected}' ";

                            if( '' != $rows[$tooltip_key] && ( isset( $rows[$desc_key] ) && '' != $rows[$desc_key] ) ){
                                $arp_column_feature_part .= " arp_tooltip ";
                                if( 'click' == $tooltip_trigger_type ){
                                    $arp_column_feature_part .= " on_click ";
                                }
                                if( 'informative' == $tooltip_display_style ){
                                    $arp_column_feature_part .= " arp_informative_tooltip arp_tooltip ";
                                    if( 'right_align' ==  $tooltip_icon_position ){
                                        $arp_column_feature_part .= " arp_informative_right_align ";
                                    } else {
                                        $arp_column_feature_part .= " arp_informative_float_to_content ";
                                    }
                                }
                            }

                            $arp_column_feature_part .= "' data-tipso='";

                            if( "" != $rows[$tooltip_key] && $tooltip_display_style == 'default' && ( isset( $rows[$desc_key] ) && '' != $rows[$desc_key] ) ){
                                $arp_column_feature_part .= esc_html( $rows[$tooltip_key] );
                            }
                            $arp_column_feature_part .= "' >";

                                if( 1 == $arp_li_content_type ){
                                    $arp_column_feature_part .= arp_get_row_button_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id, $g, $tab_name, $selected, $desc_key, $rows );
                                } else {
                                    $arp_column_feature_part .= (( isset($rows[$desc_key]) && $rows[$desc_key] != '' ) ? stripslashes_deep($rows[$desc_key]) : '');
                                }

                                if( 'informative' == $tooltip_display_style && '' != $rows[$tooltip_key] && ( isset( $rows[$desc_key] ) && '' != $rows[$desc_key] ) ){
                                    if( 'click' == $tooltip_trigger_type ){
                                        $informative_cls = " on_click ";
                                    }
                                    $arp_column_feature_part .= "<label class='arp_informative_tooltip $informative_cls' data-tipso='" . esc_html($rows[$tooltip_key]) . "'><span>" . stripslashes($tooltip_informative_icon) . "</span></label>";
                                }
                            $arp_column_feature_part .= "</div>";
                            $g++;
                        }

                    $arp_column_feature_part .= "</span>";

                    $arp_column_feature_part .= "<span  class='caption_li'>";
                        $g = 0;

                        foreach($total_tabs as $key => $tab_name){
                            $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";

                            $arp_column_feature_part .= "<div class='row_label_{$tab_name[2]}_step arp_caption_li_text toggle_step_{$tab_name[2]} {$selected}'>";

                            if( $g == 0 ){
                                $arp_column_feature_part .= isset($rows['row_label']) ? $rows['row_label'] : "";
                                array_push($font_awesome_match, @$rows['row_label']);
                            } else {
                                $arp_column_feature_part .= isset($rows['row_label_'.$tab_name[2]]) ? $rows['row_label_'.$tab_name[2]] : "";
                                array_push($font_awesome_match, @$rows['row_label'.$tab_name[2]]);
                            }

                            $arp_column_feature_part .= "</div>";
                            $g++;
                        }
                        
                    $arp_column_feature_part .= "</span>";
                    $informative_cls = '';
                    $g = 0;
                    foreach($total_tabs as $key => $tab_name ){
                        
                        $key = ($g == 0) ? 'row_tooltip' : 'row_tooltip_'.$tab_name[2];
                        $dkey = ($g == 0) ? 'row_description' : 'row_description_'.$tab_name[2];

                        if( isset($rows[$tooltip_key]) ){
                            array_push($font_awesome_match, $rows[$tooltip_key]);
                        }
                        if( isset($rows[$desc_key]) ){
                            array_push($font_awesome_match, $rows[$desc_key]);
                        }

                        if( $tooltip_display_style == 'informative' && $rows[$key] != '' && ( isset($rows[$dkey]) && $rows[$dkey] != '' ) ){
                            if ($tooltip_trigger_type == 'click') {
                                $informative_cls = " on_click";
                            }

                            if ($tooltip_icon_position == 'right_align') {
                                $informative_cls .= " arp_informative_right_align arp_tooltip_individual";
                            } else {
                                $informative_cls .= " arp_informative_float_to_content arp_tooltip_individual";
                            }
                            $arp_column_feature_part .= "<label class='arp_informative_tooltip $informative_cls toggle_step_{$tab_name[2]} $one_toggle_selected' data-tipso='" . esc_html($rows[$key]) . "'><span>" . stripslashes($tooltip_informative_icon) . "</span></label>";
                        }
                        $g++;
                    }

                $arp_column_feature_part .= "</li>";

            } else if( 'default' !=  $template_feature['list_alignment'] ) {
                $arp_column_feature_part .= "<li class='" . $cls;
                if (@$rows['row_tooltip'] != "" || @$rows['row_tooltip_second'] != "" || @$rows['row_tooltip_third'] != "") {
                    $arp_column_feature_part .= " arp_tooltip_li ";
                }
                if ($tooltip_trigger_type == 'click') {
                    $arp_column_feature_part .= " on_click ";
                }
                $li_class = $ref_template_id . '_' . $col_id . '_row_' . $ri;
                $arp_column_feature_part .= " " . $li_class . "' id='arp_" . $col_id . '_row_' . $ri . "' style='text-align:" . $template_feature['list_alignment'] . ';' . $row_inline_script . "' >";

                    $g = 0;
                    foreach($total_tabs as $key => $tab_name){
                        $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";

                        $tooltip_key = ($g == 0 ) ? 'row_tooltip' : "row_tooltip_{$tab_name[2]}";
                        $desc_key = ($g == 0) ? 'row_description' : "row_description_{$tab_name[2]}";
                        if( isset($rows[$tooltip_key]) ){
                            array_push($font_awesome_match, $rows[$tooltip_key]);
                        }

                        if( isset($rows[$desc_key]) ){
                            array_push($font_awesome_match, $rows[$desc_key]);
                        }

                        $isBlank = '';
                        if( isset($rows[$desc_key]) && '' == $rows[$desc_key] ){
                            $isBlank = 'blank';
                        }

                        $arp_column_feature_part .= "<div data-isBlank='".$isBlank."' class='row_description_".$tab_name[2]."_step arp_row_description_text toggle_step_".$tab_name[2]." ". $selected;

                        if ( isset($rows[$tooltip_key]) && $rows[$tooltip_key] != "" and ( isset($rows[$desc_key]) && $rows[$desc_key] != '' ) ){
                            $arp_column_feature_part .= " arp_tooltip ";
                            if( 'click' == $tooltip_trigger_type ){
                                $arp_column_feature_part .= " on_click ";
                            }
                            if( 'informative' == $tooltip_display_style ){
                                $arp_column_feature_part .= " arp_informative_tooltip arp_tooltip ";
                                if( 'right_align' == $tooltip_icon_position ){
                                    $arp_column_feature_part .= " arp_informative_right_align ";
                                }
                            }
                        }

                        $arp_column_feature_part .= "' data-tipso='";
                        if (isset($rows[$tooltip_key]) && $rows[$tooltip_key] != "" and $tooltip_display_style == 'default' and (isset($rows[$desc_key]) && $rows[$desc_key] != '')) {
                            $arp_column_feature_part .= esc_html($rows[$tooltip_key]);
                        }
                        $arp_column_feature_part .= "'>";

                            if( 1 == $arp_li_content_type ){
                                $arp_column_feature_part .= arp_get_row_button_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id, $g, $tab_name, $selected, $desc_key, $rows );
                            } else {
                                $arp_column_feature_part .= ((isset($rows[$desc_key]) && $rows[$desc_key] != '') ? stripslashes_deep($rows[$desc_key]) : '');
                            }

                            if( 'informative' == $tooltip_display_style && '' != $rows[$tooltip_key] && (isset($rows[$desc_key]) && '' != $rows[$desc_key] ) ){
                                if( 'click' == $tooltip_trigger_type ){
                                    $informative_cls = ' on_click ';
                                }
                                $arp_column_feature_part .= "<label class='arp_informative_tooltip ".$informative_cls."' data-tipso='" . esc_html($rows[$tooltip_key]) ."'>";
                                    $arp_column_feature_part .= "<span>".stripslashes_deep( $tooltip_informative_icon )."</span>";
                                $arp_column_feature_part .= "</label>";
                            }
                        $arp_column_feature_part .= "</div>";
                        $g++;
                    }
                $arp_column_feature_part .= "</li>";
            } else {

                $arp_column_feature_part .= "<li class='" . $cls;
                $rows['row_tooltip'] = isset( $rows['row_tooltip'] ) ? $rows['row_tooltip'] : '';
                $rows['row_tooltip_second'] = isset( $rows['row_tooltip_second'] ) ? $rows['row_tooltip_second'] : '';
                $rows['row_tooltip_third'] = isset( $rows['row_tooltip_third'] ) ? $rows['row_tooltip_third'] : '';
                if ($rows['row_tooltip'] != "" || $rows['row_tooltip_second'] != "" || $rows['row_tooltip_third'] != "") {
                    $arp_column_feature_part .= " arp_tooltip_li";
                }

                $tooltip_trigger_type = isset($tooltip_trigger_type) ? $tooltip_trigger_type : '';
                $tooltip_display_style = isset($tooltip_display_style) ? $tooltip_display_style : '';
                if ($tooltip_trigger_type == 'click') {
                    $arp_column_feature_part .= " on_click";
                }

                $li_class = $ref_template_id . '_' . $col_id . '_row_' . $ri;
                $arp_column_feature_part .= " " . $li_class . "' id='arp_" . $col_id . '_row_' . $ri . "' style='text-align:" . $template_feature['list_alignment'] . ';' . $row_inline_script . "' >";
                    
                    $g = 0;
                    foreach( $total_tabs as $key => $tab_name ){
                        $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";

                        $tooltip_key = ($g == 0) ? 'row_tooltip' : "row_tooltip_{$tab_name[2]}";
                        $desc_key    = ($g == 0) ? 'row_description' : "row_description_{$tab_name[2]}";
                        $cap_des_key = ($g == 0) ? 'description' : "description_{$tab_name[2]}";
                        if (isset($rows[$tooltip_key])) {
                            array_push($font_awesome_match, $rows[$tooltip_key]);
                        }

                        if (isset($rows[$desc_key])) {
                            array_push($font_awesome_match, $rows[$desc_key]);
                        }

                        $isBlank = '';
                        if( isset( $rows[$desc_key] ) && '' == $rows[$desc_key] ){
                            $isBlank = 'blank';
                        }

                        $arp_column_feature_part .= "<div data-isBlank='".$isBlank."' class='row_description_".$tab_name[2]."_step arp_row_description_text toggle_step_".$tab_name[2]." ". $selected ." ";

                        if (isset($rows[$tooltip_key]) && $rows[$tooltip_key] != "" and (isset($rows[$desc_key]) && $rows[$desc_key] != '')) {
                            $arp_column_feature_part .= " arp_tooltip";
                            if ($tooltip_trigger_type == 'click') {
                                $arp_column_feature_part .= " on_click";
                            }
                            if ($tooltip_display_style == 'informative') {
                                $arp_column_feature_part .= " arp_informative_tooltip arp_tooltip ";
                                if ($tooltip_icon_position == 'right_align') {
                                    $arp_column_feature_part .= " arp_informative_right_align ";
                                } else {
                                    $arp_column_feature_part .= " arp_informative_float_to_content ";
                                }
                            }
                        }

                        $arp_column_feature_part .= "' data-tipso='";
                        if( $tooltip_display_style == '' ){
                            $tooltip_display_style = 'default';
                        }
                        if ( (isset($rows[$tooltip_key]) && $rows[$tooltip_key] != "") && $tooltip_display_style == 'default' && (isset($rows[$desc_key]) && $rows[$desc_key] != '')) {
                            $arp_column_feature_part .= esc_html($rows[$tooltip_key]);
                        }
                        if(!empty($caption_array)){

                            $arf_col_hidden_row_css = isset($arf_cap_col_hidden_row_css[$ri]) ? $arf_cap_col_hidden_row_css[$ri] : '' ;
                            if(isset($caption_array[$template_name][$ri][$cap_des_key])) {

                                $caption_hidden_html = '<p style="'.$arf_col_hidden_row_css.'" class="arprice_caption_hidden">'.$caption_array[$template_name][$ri][$cap_des_key].'</p>';
                            }
                            $tooltip_pos_cls="";
                            if(isset($caption_array[$template_name][$ri][$tooltip_key]) && $caption_array[$template_name][$ri][$tooltip_key]!=''){
                                $tooltip_lbl_cls = " arp_tooltip";
                                if ($tooltip_trigger_type == 'click') {
                                    $tooltip_lbl_cls .= " on_click";
                                }
                                $tooltip_pos_cls = '';
                                $tooltip_pos_div_cls = '';
                                if ($tooltip_display_style == 'informative') {
                                    $tooltip_pos_cls .= " arp_informative_tooltip arp_tooltip ";
                                    if ($tooltip_icon_position == 'right_align') {
                                        $tooltip_pos_div_cls .= " arp_informative_right_align ";
                                    } else {
                                        $tooltip_pos_div_cls .= " arp_informative_float_to_content ";
                                    }
                                }
                                //echo "<pre>";print_r($caption_array);echo "</pre>";
                                if ($tooltip_icon_position == 'right_align'){
                                    $caption_hidden_html = '<div style="'.$arf_col_hidden_row_css.'" class="arprice_caption_hidden '.$tooltip_pos_div_cls.'">'.$caption_array[$template_name][$ri][$cap_des_key];    
                                    $caption_hidden_html .= '<label class="arprice_caption_hidden '.$tooltip_pos_cls.$tooltip_lbl_cls.'" data-tipso="'.$caption_array[$template_name][$ri][$tooltip_key].'">'.$tooltip_informative_icon.'</label>';
                                    $caption_hidden_html .= '</div>';
                                } else{
                                    $caption_hidden_html = '<div style="'.$arf_col_hidden_row_css.'" class="arprice_caption_hidden '.$tooltip_pos_div_cls.'">';
                                    $caption_hidden_html .= '<label class="arprice_caption_hidden '.$tooltip_pos_cls.$tooltip_lbl_cls.'" data-tipso="'.$caption_array[$template_name][$ri][$tooltip_key].'">'.$caption_array[$template_name][$ri][$cap_des_key].' '.$tooltip_informative_icon.'</label>';
                                    $caption_hidden_html .= '</div>';
                                }
                                
                                
                            }

                        }
                        $arp_column_feature_part .= "'>" . $caption_hidden_html;

                            if( 1 == $arp_li_content_type ){
                                $arp_column_feature_part .= arp_get_row_button_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id, $g, $tab_name, $selected, $desc_key, $rows );
                            } else {
                                $arp_column_feature_part .= ((isset($rows[$desc_key]) && $rows[$desc_key] != '') ? stripslashes_deep($rows[$desc_key]) : '');
                            }

                            if ($tooltip_display_style == 'informative' && (isset($rows[$tooltip_key]) && $rows[$tooltip_key] != "") and (isset($rows[$desc_key]) && $rows[$desc_key] != '')) {
                                $informative_cls = '';
                                if ($tooltip_trigger_type == 'click') {
                                    $informative_cls = " on_click";
                                }
                                $arp_column_feature_part .= "<label class='arp_informative_tooltip $informative_cls' data-tipso='" . esc_html($rows[$tooltip_key]) . "'><span>" . stripslashes($tooltip_informative_icon) . "</span></label>";
                            }
                        $arp_column_feature_part .= "</div>";

                        $g++;
                    }
                $arp_column_feature_part .= "</li>";
            }
        }
       
        if ( 'default' != $template_feature['button_position'] ) {
        
            $arp_column_feature_part .= "<li class='arp_last_list_item ".$last_li_cls ."'></li>";
        }

        $arp_column_feature_part .= "</ul>";

    $arp_column_feature_part .= "</div>";

    return $arp_column_feature_part;

}

function arp_get_column_description_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle ){
    global $font_awesome_match;
    $arp_front_column_desc_part = '';

    $g = 0;
    foreach( $total_tabs as $key => $tab_name ){
        $selected = ( $g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
        $arp_front_column_desc_part .= "<div class='column_description column_description_".$tab_name[2]."_step toggle_step_".$tab_name[2]." ".$selected."' data-column_name='main_".$col_id."' data-template_id='".$ref_template_id."' data-type='other_columns_buttons' data-level='column_description_level'>";
            if( $g == 0 ){
                $arp_front_column_desc_part .= isset($columns['column_description']) ? $columns['column_description'] : '';
                if( isset($columns['column_description'] ) ){
                    array_push($font_awesome_match,$columns['column_description']);
                }
            } else {
                $arp_front_column_desc_part .= isset($columns['column_description_'.$tab_name[2]]) ? $columns['column_description_'.$tab_name[2]] : '';
                if(isset( $columns['column_descirption_'. $tab_name[2]] ) ){
                    array_push($font_awesome_match,$columns['column_descirption_'.$tab_name[2]]);
                }
            }
        $arp_front_column_desc_part .= "</div>";
        $g++;
    }

    return $arp_front_column_desc_part;
}

function arp_get_column_footer_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id ){

    global $arprice_default_settings,$font_awesome_match;

    $arp_front_column_footer_part = '';

    $footer_hover_class = "";

    $arp_global_button_size = $arprice_default_settings->arp_button_size_new();

    $enable_hover_effect = isset( $column_settings['enable_hover_effect'] ) ? $column_settings['enable_hover_effect'] : 0;
    $arp_global_button_class = '';
    $arp_global_button_type = isset($column_settings['arp_global_button_type']) ? $column_settings['arp_global_button_type'] : 'flat';
    $arp_global_button_class_array = $arprice_default_settings->arp_button_type();
    $arp_global_button_size = $arprice_default_settings->arp_button_size_new();

    if ( !isset($column_settings['enable_button_hover_effect']) || $column_settings['enable_button_hover_effect'] != 1) {
        $arp_global_button_class = $arp_global_button_class_array[$arp_global_button_type]['class'] . ' arp_button_hover_disable';
    } else {
        $arp_global_button_class = $arp_global_button_class_array[$arp_global_button_type]['class'];
    }

    if ('' != $columns['footer_content'] && 1 == $template_feature['has_footer_content'] ) {

        $footer_hover_class .= ' has_footer_content';
        if ( 0 == $columns['footer_content_position'] ) {
            $footer_hover_class .= " footer_below_content";
        } else {
            $footer_hover_class .= " footer_above_content";
        }
    } else {
        $footer_hover_class = "";
    }

    $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : "";



    $arp_front_column_footer_part .= "<div class='arpcolumnfooter ". $footer_hover_class . strtolower(isset($arp_global_button_size[$columns['button_size']]) ? $arp_global_button_size[$columns['button_size']] : '') ."'>";

        $hide_default_btn_true = "";
        if (isset($columns['hide_default_btn']) && 1 == $columns['hide_default_btn'] ) {
            $hide_default_btn_true = 'hide_default_btn_true';
        }

        $footer_content_below_btn = "";
        if ( (isset($columns['footer_content']) && '' != $columns['footer_content']) && (isset($columns['footer_content_position']) && 1 == $columns['footer_content_position']) && (isset($template_feature['has_footer_content']) && 1 == $template_feature['has_footer_content']) ){
            $footer_content_above_btn = "display:block;";
        } else {
            $footer_content_above_btn = "display:none;";
        }

        if (isset( $template_feature['has_footer_content']) && 1 == $template_feature['has_footer_content']) {
            $arp_front_column_footer_part .= "<div class='arp_footer_content arp_btn_before_content' style='".$footer_content_above_btn."'>";

                $g = 0;
                foreach( $total_tabs as $key => $tab_name ){
                    $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                    $arp_front_column_footer_part .= "<span class='footer_content_".$tab_name[2]."_step arp_footer_content_text toggle_step_".$tab_name[2]." ".$selected."'>";
                        if( $g == 0 ){
                            $arp_front_column_footer_part .= isset($columns['footer_content']) ? $columns['footer_content'] : '';
                            if( isset($columns['footer_content'])){
                                array_push($font_awesome_match,$columns['footer_content']);
                            }
                        } else {
                            $arp_front_column_footer_part .= isset($columns['footer_content_'.$tab_name[2]]) ? $columns['footer_content_'.$tab_name[2]] : '';
                            if( isset($columns['footer_content_'.$tab_name[2]]) ){
                                array_push($font_awesome_match,$columns['footer_content_'.$tab_name[2]]);
                            }
                        }
                    $arp_front_column_footer_part .= "</span>";
                    $g++;    
                }
            $arp_front_column_footer_part .= "</div>";
        }

        if (isset($columns['button_background_color']) && '' != $columns['button_background_color'] ) {
            $button_background_color = $columns['button_background_color'];
        } else {
            $button_background_color = '';
        }

        $g = 0;

        foreach($total_tabs as $key => $tab_name){
            $selected = ($g == $setas_default_toggle) ? 'toggle_selected active_toggle': '';

            $post_var_key = ($g == 0) ? 'post_variables_content' : "post_variables_content_{$tab_name[2]}";
            $btn_url_key = ($g == 0) ? 'button_url' : "button_url_{$tab_name[2]}";
            $btn_text_key = ($g == 0) ? 'button_text' : "btn_content_{$tab_name[2]}";
            $paypal_code_key = ($g == 0) ? 'paypal_code' : "paypal_code_{$tab_name[2]}";



            $columns[$btn_url_key] = isset($columns[$btn_url_key]) ? $columns[$btn_url_key] : '#';

            if( isset($columns[$paypal_code_key]) && $columns[$paypal_code_key] != '' ){
                $paypal_btn = 1;
            } else {
                $paypal_btn = 0;
            }

            if( isset($columns[$btn_text_key]) ){
                array_push($font_awesome_match, $columns[$btn_text_key]);
            }

            if( isset( $columns['is_caption'] ) && 1 == $columns['is_caption'] ){
                $arp_front_column_footer_part .= "";
            } else {
                $arp_front_column_footer_part .= "<div class='arppricetablebutton toggle_step_" . $tab_name[2] . " " . $selected . " " . $hide_default_btn_true . "' style='text-align:center;'>";

                    $tag = "<button type='button'";
                    $end_tag = "</button>";
                    $onclick = " onclick='arp_redirect(\"".$arp_unique_table_id."\", \"" . $columns[$btn_url_key] . "\", \"";
                    $object = "this";
                    $end_braces = "";
                    if(isset($columns['is_nofollow_link']) && $columns['is_nofollow_link']==1){
                        $arp_enable_analytics = get_option('arp_enable_analytics');
                        if( (isset($arp_enable_analytics) && $arp_enable_analytics == 'arp_enable_analytics') || (isset($columns['is_new_window_actual']) && $columns['is_new_window_actual'] == 1) || (isset($column_settings['full_column_clickable']) && $column_settings['full_column_clickable'] == 1 )){
                            $tag = "<a rel='nofollow'";
                            $end_tag = "</a>";
                            $onclick = " href='javascript:void(0)' onclick='arp_redirect(\"".$arp_unique_table_id."\", \"" . $columns[$btn_url_key] . "\", \"";
                            $object = "\".arp_price_tbl_btn_".$col_id."\"";
                            $end_braces = "";
                        } else {
                            $is_new_window ="";
                            if (isset($columns['is_new_window']) && $columns['is_new_window'] == 1){
                                $is_new_window ="target ='_blank'";
                            }
                            $post_var_keyvalue ="";

                            if($columns[$post_var_key] != ''){
                                $post_var_keyvalue = isset($columns[$post_var_key]) ? stripslashes($columns[$post_var_key]) : '';
                                if( '' != $post_var_keyvalue ){
                                    $post_var_keyvalue = str_replace(';', '&', $post_var_keyvalue);
                                    $post_var_keyvalue = '?'.rtrim($post_var_keyvalue, '&');
                                }
                            }
                            $tag = "<a rel='nofollow'";
                            $end_tag = "</a>";
                            $onclick = " href='". $columns[$btn_url_key].$post_var_keyvalue."' ".$is_new_window."";
                            $object = "\".arp_price_tbl_btn_".$j."\"";
                            $end_braces = "";
                        }
                    }
                    $columns['is_post_variables'] = isset($columns['is_post_variables']) ? $columns['is_post_variables'] : '';
                    $arp_front_column_footer_part .= $tag." class='arp_price_tbl_btn_{$col_id} bestPlanButton $arp_global_button_class toggle_step_{$tab_name[2]} $selected " . strtolower(isset($arp_global_button_size[$columns['button_size']]) ? $arp_global_button_size[$columns['button_size']] : '') . "' data-is-post-variables='" . @$columns['is_post_variables'] . "' data-post-variables='" . stripslashes(@$columns[$post_var_key]) . "' ";

                    $columns['btn_img'] = isset($columns['btn_img']) ? $columns['btn_img'] : '';
                    if ($columns['btn_img'] != "" && $columns['hide_default_btn'] != 1) {
                        $arp_front_column_footer_part .= "style='background:" . $columns['button_background_color'] . " url(" . $columns['btn_img'] . ") no-repeat !important; width:" . $columns['btn_img_width'] . "px;height:" . $columns['btn_img_height'] . "px;'";
                    }
                    if (@$columns['hide_default_btn'] == 1) {
                        $arp_front_column_footer_part .= "style='display:none;'";
                    }
                    $arp_front_column_footer_part .= $onclick;
                    $full_column_clickable = isset($column_settings['full_column_clickable']) ? $column_settings['full_column_clickable'] : 0;
                    if(isset($columns['is_nofollow_link']) && $columns['is_nofollow_link']==1 && isset($arp_enable_analytics) && $arp_enable_analytics != 'arp_enable_analytics' && @$columns['is_new_window_actual'] != 1 && $full_column_clickable != 1){
                        $arp_front_column_footer_part .= '>';
                    } else{
                        if (isset($columns['is_new_window']) && 1 == $columns['is_new_window'] ) {
                            $arp_front_column_footer_part .= "1";
                        } else {
                            $arp_front_column_footer_part .= "0";
                        }
                        $arp_front_column_footer_part .="\",\"";
                        if (isset($columns['is_new_window_actual']) && $columns['is_new_window_actual'] == 1) {
                            $arp_front_column_footer_part .= "1";
                        } else {
                            $arp_front_column_footer_part .= "0";
                        }
                        $arp_front_column_footer_part .="\",";
                        $arp_front_column_footer_part .= "\"" . $paypal_btn . "\",{$object},\"" . $table_id . "\",\"main_" . $col_id . "\"){$end_braces};'>";
                    }
                    if ($columns['btn_img'] == "") {
                        $arp_front_column_footer_part .= "<span class='btn_content_{$tab_name[2]}_step bestPlanButton_text'>";
                            $arp_front_column_footer_part .= stripslashes_deep(isset($columns[$btn_text_key]) ? $columns[$btn_text_key] : '');
                        $arp_front_column_footer_part .= "</span>";
                    }
                    $arp_front_column_footer_part .= $end_tag;

                    $arp_front_column_footer_part .= "<div class='arp_paypal_form' id='paypal_form_{$tab_name[2]}_$col_id' ";
                    $columns['hide_default_btn'] = isset( $columns['hide_default_btn'] ) ? $columns['hide_default_btn'] : '';
                    if ( $columns['hide_default_btn'] != 1) {
                        $arp_front_column_footer_part .= "style='display:none;'";
                    }
                    $arp_front_column_footer_part .= ">";
                        $arp_front_column_footer_part .= isset($columns[$paypal_code_key]) ? do_shortcode($columns[$paypal_code_key]) : '';
                    $arp_front_column_footer_part .= "</div>";
                $arp_front_column_footer_part .= "</div>";
            }    

            
            $g++;
        }

        $footer_content_below_btn = "";
        if ( '' != $columns['footer_content'] && $columns['footer_content_position'] == 0){
            $footer_content_below_btn = "display:block;";
        } else {
            $footer_content_below_btn = "display:none;";
        }

        if( isset($template_feature['has_footer_content']) && 1 == $template_feature['has_footer_content'] ){
            $arp_front_column_footer_part .= "<div class='arp_footer_content arp_btn_after_content' style='{$footer_content_below_btn}'>";
                $g = 0;
                foreach( $total_tabs as $key => $tab_name ){
                    $selected = ($g == $setas_default_toggle) ? "toggle_selected active_toggle" : "";
                    $arp_front_column_footer_part .= "<span class='footer_content_{$tab_name[2]}_step arp_footer_content_text toggle_step_{$tab_name[2]} {$selected}'>";
                        if( $g == 0 ){
                            $arp_front_column_footer_part .= isset($columns['footer_content']) ? $columns['footer_content'] : '';
                            if( isset($columns['footer_content'] ) ){
                                array_push($font_awesome_match, $columns['footer_content']);
                            }
                        } else {
                            $arp_front_column_footer_part .= isset($columns['footer_content_'.$tab_name[2]]) ? $columns['footer_content_'.$tab_name[2]] : '';
                            if( isset( $columns['footer_content_'.$tab_name[2]] ) ){
                                array_push($font_awesome_match, $columns['footer_content_'.$tab_name[2]]);
                            }
                        }
                    $arp_front_column_footer_part .= "</span>";
                    $g++;    
                }
            $arp_front_column_footer_part .= "</div>";
        }

        if ( 'arptemplate_16' == $ref_template_id) {
            $arp_front_column_footer_part .= "<div class='arp_bottom_image'>";
                $arp_front_column_footer_part .= "<ul class='arp_boat_img'><li></li></ul>";
                $arp_front_column_footer_part .= "<ul class='arp_water_imgs'>";
                    $arp_front_column_footer_part .= "<li class='arp_water_img_1'></li>";
                    $arp_front_column_footer_part .= "<li class='arp_water_img_2'></li>";
                $arp_front_column_footer_part .= "</ul>";
            $arp_front_column_footer_part .= "</div>";
        }

    $arp_front_column_footer_part .= "</div>";

    if( 'enable' == $template_feature['column_description'] && 'after_button' == $template_feature['column_description_style'] ){
        $arp_front_column_footer_part .= arp_get_column_description_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle );
    }

    return $arp_front_column_footer_part;

}

function arp_get_row_button_part( $columns, $ref_template_id, $template_feature, $col_id, $total_tabs, $setas_default_toggle, $arp_unique_table_id, $column_settings, $table_id, $g, $tab_name, $selected, $desc_key, $rows ){

    global $arprice_default_settings;
    $arp_row_button_part = '';

    $arp_global_button_class = '';
    $arp_global_button_type = isset($column_settings['arp_global_button_type']) ? $column_settings['arp_global_button_type'] : 'flat';
    $arp_global_button_class_array = $arprice_default_settings->arp_button_type();
    $arp_global_button_size = $arprice_default_settings->arp_button_size_new();

    if ( !isset($column_settings['enable_button_hover_effect']) || $column_settings['enable_button_hover_effect'] == 1) {
        $arp_global_button_class = $arp_global_button_class_array[$arp_global_button_type]['class'] . ' arp_button_hover_disable';
    } else {
        $arp_global_button_class = $arp_global_button_class_array[$arp_global_button_type]['class'];
    }

    $post_var_key = ($g == 0) ? 'post_variables_content' : "post_variables_content_{$tab_name[2]}";
    $btn_url_key = ($g == 0) ? 'button_url' : "button_url_{$tab_name[2]}";
    $paypal_code_key = ($g == 0) ? 'paypal_code' : "paypal_code_{$tab_name[2]}";

    if( isset($columns[$paypal_code_key]) && $columns[$paypal_code_key] != '' ){
        $paypal_btn = 1;
    } else {
        $paypal_btn = 0;
    }

    $arp_row_button_part .= "<div class='arppricetablerowbutton toggle_step_{$tab_name[2]} $selected' style='text-align:center;'>";

        $tag = "<button type='button'";
        $end_tag = "</button>";
        $onclick = " onclick='arp_redirect(\"".$arp_unique_table_id."\", \"" . @$columns[$btn_url_key] . "\", \"";
        $object = "this";
        $end_braces="";

        if( isset($columns['is_nofollow_link']) && 1 == $columns['is_nofollow_link'] ){
            $arp_enable_analytics = get_option('arp_enable_analytics');

            if( ( isset($arp_enable_analytics) && 'arp_enable_analytics' == $arp_enable_analytics ) || ( iset($columns['is_new_window_actual']) && 1 == $columns['is_new_window_actual'] ) || ( isset($column_settings['full_column_clickable']) && 1 == $column_settings['full_column_clickable'] ) ){
                $tag = "<a rel='nofollow'";
                $end_tag = "</a>";
                $onclick = " href='javascript:void(0)' onclick='arp_redirect(\"".$arp_unique_table_id."\", \"" . @$columns[$btn_url_key] . "\", \"";
                $object = "\".arp_price_tbl_btn_{$j}\"";
                $end_braces = "";

            } else {
                $is_new_window ="";
                if (isset($columns['is_new_window']) && $columns['is_new_window'] == 1){
                    $is_new_window ="target ='_blank'";
                }
                $post_var_keyvalue ="";

                if(isset($columns[$post_var_key]) && $columns[$post_var_key] != ''){
                    $post_var_keyvalue = isset($columns[$post_var_key]) ? stripslashes($columns[$post_var_key]) : '';
                    $post_var_keyvalue = str_replace(';', '&', $post_var_keyvalue);
                    $post_var_keyvalue = '?'.rtrim($post_var_keyvalue, '&');
                }
                $tag = "<a rel='nofollow'";
                $end_tag = "</a>";
                $onclick = " href='". @$columns[$btn_url_key].$post_var_keyvalue."' ".$is_new_window."";
                $object = "\".arp_price_tbl_btn_{$j}\"";
                $end_braces = "";
            }
        }

        $is_post_variables = isset($columns['is_post_variables']) ? $columns['is_post_variables'] : '';

        $arp_row_button_part .= $tag." class='arp_price_tbl_row_btn_{$col_id} bestPlanRowButton bestPlanButton $arp_global_button_class toggle_step_{$tab_name[2]} $selected " . strtolower(isset($arp_global_button_size[$columns['button_size']]) ? $arp_global_button_size[$columns['button_size']] : '') . "' data-is-post-variables='" . $is_post_variables . "' data-post-variables='" . stripslashes(@$columns[$post_var_key]) . "' ";

        $arp_row_button_part .= "style='width: auto; height: auto; padding: 0px;'";

        $arp_row_button_part .= $onclick;

        if(isset($columns['is_nofollow_link']) && $columns['is_nofollow_link']==1 && isset($arp_enable_analytics) && $arp_enable_analytics != 'arp_enable_analytics' && @$columns['is_new_window_actual'] != 1 && @$column_settings['full_column_clickable'] != 1){
            $arp_row_button_part .= '>';
        } else{
            if (@$columns['is_new_window'] == 1) {
                $arp_row_button_part .= "1";
            } else {
                $arp_row_button_part .= "0";
            }
            $arp_row_button_part .="\",\"";
            if (@$columns['is_new_window_actual'] == 1) {
                $arp_row_button_part .= "1";
            } else {
                $arp_row_button_part .= "0";
            }
            $arp_row_button_part .="\",";
            $arp_row_button_part .= "\"" . $paypal_btn . "\",{$object},\"" . $table_id . "\",\"main_" . $col_id . "\"){$end_braces};'>";
        }

            $arp_row_button_part .= "<span class='btn_content_{$tab_name[2]}_step bestPlanButton_text bestPlanRowButton_text'>";
                $arp_row_button_part .= (( isset($rows[$desc_key]) && $rows[$desc_key] != '' ) ? stripslashes_deep($rows[$desc_key]) : '');
            $arp_row_button_part .= "</span>";

        $arp_row_button_part .= $end_tag;

        $arp_row_button_part .= "<div class='arp_paypal_form' id='paypal_form_{$tab_name[2]}_$col_id' style='display:none;'>";
            $arp_row_button_part .= isset($columns[$paypal_code_key]) ? do_shortcode($columns[$paypal_code_key]) : '';
        $arp_row_button_part .= "</div>";

    $arp_row_button_part .= "</div>";


    return $arp_row_button_part;
}
?>
