<?php
/**
 * Template Name: ICIGAI - Plan All
 */

get_header();
?>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<?php
$padding = "";

$wrap_class = 'page_wrapper';

if ( class_exists('WooCommerce') ) {
	if ( is_cart() ) {
		$wrap_class = 'shopping_cart_area bg_color';
	}
    elseif ( is_checkout() ) {
		$wrap_class = 'checkout_area bg_color';
	}
	else {
		$wrap_class = 'page_wrapper';
	}
	if ( function_exists('is_wishlist') ) {
		if ( is_wishlist() ) {
			$wrap_class = 'shopping_cart_area bg_color';
		}
	}
} else {
	$wrap_class = 'page_wrapper';
}

while ( have_posts() ) : the_post();
	?>
    <div class="sec_pad <?php echo esc_attr($wrap_class) ?>">
        <div>
            <?php
			the_content();
			wp_link_pages(array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'saasland' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'saasland' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			));

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
            <div id="vue-container">
                <plan-all inline-template>
                    <div class="" v-cloak>
                        <loading
                                :active.sync="isLoading"
                                :can-cancel="true"
                                :is-full-page="true"
                        ></loading>
<!--                        <header-component inline-template :title="title" :description="description">-->
<!--                            <div class="header-container">-->
<!--                                <div class="text">-->
<!--                                    <h1>{{ title }}</h1>-->
<!--                                    <h2>{{ description }}</h2>-->
<!--                                    <slot></slot>-->
<!--                                </div>-->
<!--                                <img v-if="img" src="--><?php //echo get_bloginfo('stylesheet_directory'); ?><!--/assets/header-img1.png" alt="" />-->
<!--                            </div>-->
<!--                        </header-component>-->
                        <div class="table-container">
                            <div class="row2">
                                <h1 style="margin-left: 20px; min-width: 200px">Plans/ Features</h1>
                                <div class="plans-container">
                                    <div class="plan" v-for="(plan, index) in plans" :key="plan.ID">
                                        <p class="title">{{ plan.Feature_Plan }}</p>
                                        <img style="width: 150px; margin-bottom: 30px" v-if="plan.Image_URL" :src="plan.Image_URL.url" alt="" />
                                        <!--          <p class="price">-->
                                        <!--            <span class="price-num"><span class="currency">$</span> 256</span>-->
                                        <!--          </p>-->
                                        <!--          <p class="duration">Per Month</p>-->
                                        <a :href="buildQueryString('/form1', {
                                                                          plan_id: plan.ID,
                                                                          product_type: plan.Product_Type.display_value,
                                                                          product_type_id: plan.Product_Type.ID,
                                                                          plan: plan.Feature_Plan
                                                                        })">
                                            <button class="subscribe-btn">Select This Plan</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table2" v-for="(feature, index) in features">
                                        <div class="parent-feature">
<!--                                          <img src="--><?php //echo get_bloginfo('stylesheet_directory'); ?><!--/assets/group-icon.png" alt="" />-->
                                          <h1>{{feature.display_value}}</h1>
                                            <div v-if="feature.Tooltip_Information" class="tooltip2">
                                                <img style="float: right; margin-left: 10px" src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/tooltip.png" alt="" />
                                                <span class="tooltiptext2">{{feature.Tooltip_Information}}</span>
                                            </div>
                                        </div>
                                <div
                                        style="margin-bottom: 27px"
                                        class="row2 feature-container"
                                        v-for="sub in feature.sub"
                                        :key="sub.ID"
                                >
                                    <div class="feature-name">
<!--                                        <img src="--><?php //echo get_bloginfo('stylesheet_directory'); ?><!--/assets/feature-icon.png" alt="" />-->
                                        <h2>{{ sub.display_value }}</h2>
                                        <div v-if="sub.Tooltip_Information" class="tooltip2">
                                            <img style="float: right; margin-left: 10px" src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/tooltip.png" alt="" />
                                            <span class="tooltiptext2">{{sub.Tooltip_Information}}</span>
                                        </div>
                                    </div>
                                    <div class="plans-container">
                                        <div
                                                class="feature-plan-value"
                                                v-for="plan in plans"
                                                :key="plan.ID"
                                        >
                                            <template v-if="planFeatures[plan.ID] && planFeatures[plan.ID][sub.ID]">
                                                <img
                                                        v-if="planFeatures[plan.ID][sub.ID] == 'Tick'"
                                                        class="check"
                                                        src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/check.png"
                                                        alt=""
                                                />
                                                <p v-else>{{planFeatures[plan.ID][sub.ID]}}</p>

                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </plan-all>
            </div>

        </div>
    </div>
<?php
endwhile; // End of the loop.

get_footer();
