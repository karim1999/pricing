<?php
$opt = get_option('saasland_opt');
$titlebar_align = !empty($opt['titlebar_align']) ? $opt['titlebar_align'] : 'center';
$background_type = function_exists('get_field') ? get_field('banner_background_type') : '';
$background_image = '';
if ( $background_type == 'image' ) {
    $background_image = function_exists('get_field') ? get_field('banner_background_image') : '';
    $background_image = !empty($background_image) ? "style='background: url($background_image); background-size: cover; background-position: center center; background-repeat: no-repeat;'" : '';
    $banner_shape_image = '';
} elseif ( $background_type == 'color' ) {
    $banner_shape_image = function_exists('get_field') ? get_field('banner_shape_image') : '';
    $background_image = '';
}
?>

<section class="breadcrumb_area>
    <?php
    if ( !empty($banner_shape_image) ) {
        
    }
    else {
        
    }
    ?>
    <div class="container">
        
    </div>
</section>