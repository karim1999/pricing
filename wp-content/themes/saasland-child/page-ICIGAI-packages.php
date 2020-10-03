<?php
/**
 * Template Name: ICIGAI - Packages
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
                <product-packages inline-template>
                    <div class="" v-cloak>
                        <loading
                                :active.sync="isLoading"
                                :can-cancel="true"
                                :is-full-page="true"
                        ></loading>
<!--                        <header-component-->
<!--                                inline-template-->
<!--                                :title="title"-->
<!--                                :description="description"-->
<!--                                :img="img"-->
<!--                        >-->
<!--                            <div class="header-container">-->
<!--                                <div class="text">-->
<!--                                    <h1>{{ title }}</h1>-->
<!--                                    <h2>{{ description }}</h2>-->
<!--                                    <slot></slot>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </header-component>-->
                        <div class="table-container">
                            <header-wizard inline-template>
                                <div class="progress-field">
                                    <div v-for="(step, index) in steps" :class="{'progress-step': true, 'active': index <= 1}">
                                        <span class="step-one">{{index+1}}</span>
                                        <p class="progress-step__text">{{step.name}}</p>
                                    </div>
                                </div>
                            </header-wizard>

                            <div class="row2">
                                <h1 style="margin-left: 20px">Support Packages</h1>
                                <div class="plans-container">
                                    <div v-for="(plan, index) in plans" :key="plan.ID" :class="{plan: true, active: plan.ID === plan_id}">
                                        <p class="title">{{ plan.Support_Package }}</p>
                                        <img style="width: 150px; margin-bottom: 30px" v-if="plan.Image_URL" :src="plan.Image_URL.url" alt="" />
                                        <a :href="buildQueryString('/plan-services', {package: plan.ID, package_name: plan.Support_Package})">
                                            <button class="subscribe-btn">Select This Package</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table2" v-for="(feature, index) in features">
                                <div
                                        style="margin-bottom: 27px"
                                        class="row2 feature-container"
                                >
                                    <div class="feature-name">
                                        <!--                                        <img src="--><?php //echo get_bloginfo('stylesheet_directory'); ?><!--/assets/feature-icon.png" alt="" />-->
                                        <h2>{{ feature.display_value }}</h2>
                                        <div v-if="feature.Tooltip_Information" class="tooltip2">
                                            <img style="float: right; margin-left: 10px" src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/tooltip.png" alt="" />
                                            <span class="tooltiptext2">{{feature.Tooltip_Information}}</span>
                                        </div>
                                    </div>
                                    <div class="plans-container">
                                        <div
                                                class="feature-plan-value"
                                                v-for="plan in plans"
                                                :key="plan.ID"
                                        >
                                            <template v-if="planFeatures[plan.ID] && planFeatures[plan.ID][feature.ID]">
                                                <img
                                                        v-if="planFeatures[plan.ID][feature.ID] == 'Tick'"
                                                        class="check"
                                                        src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/check.png"
                                                        alt=""
                                                />
                                                <p v-else>{{planFeatures[plan.ID][feature.ID]}}</p>

                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </product-packages>
            </div>

        </div>
    </div>
<?php
endwhile; // End of the loop.

get_footer();
