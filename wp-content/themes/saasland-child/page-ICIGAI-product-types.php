<?php
/**
 * Template Name: ICIGAI - Product Types
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
                <product-types inline-template >
                    <div class="plans" v-cloak>
<!--                        <header-component inline-template :title="title" :description="description">-->
<!--                            <div class="header-container">-->
<!--                                <div class="text">-->
<!--                                    <h1>{{ title }}</h1>-->
<!--                                    <h2>{{ description }}</h2>-->
<!--                                    <div class="row-flex">-->
<!--                                        <div class="row-flex">-->
<!--                                            <img src="--><?php //echo get_bloginfo('stylesheet_directory'); ?><!--/assets/feature-icon.png" alt="" />-->
<!--                                            <h3>Flexible free trial</h3>-->
<!--                                        </div>-->
<!--                                        <div class="row-flex">-->
<!--                                            <img src="--><?php //echo get_bloginfo('stylesheet_directory'); ?><!--/assets/feature-icon.png" alt="" />-->
<!--                                            <h3>Cancel or switch plans anytime</h3>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </header-component>-->
                        <div class="content">
                            <loading
                                    :active.sync="isLoading"
                                    :can-cancel="true"
                                    :is-full-page="true"
                            ></loading>
                            <ul class="top-nav">
                                <li
                                        v-for="productType in productTypes"
                                        :key="productType.ID"
                                        :class="{ active: currentType === productType.ID }"
                                        @click="changeType(productType.ID)"
                                >
                                    {{ productType.Product_Type }}
                                </li>
                            </ul>
                            <div class="hosting-plans">
                                <pricing-card inline-template
                                        v-for="(currentPlan, index) in currentPlans"
                                        :key="currentPlan.ID"
                                        :plan="currentPlan"
                                              :index="index"
                                              :features="features"
                                >
                                    <div class="pricing-card">
                                        <div class="card2">
                                            <img
                                                    v-if="plan.Is_Most_Popular === 'Yes'"
                                                    class="default-img"
                                                    src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/default.png"
                                                    alt=""
                                            />
                                            <h1 class="title">{{ plan.Feature_Plan }}</h1>
                                            <h2
                                                    v-if="plan.Description"
                                                    v-html="plan.Description"
                                                    class="description"
                                            ></h2>
                                                  <img v-if="plan.Image_URL" :src="plan.Image_URL.url" alt="" />
                                            <!--      <p class="price">-->
                                            <!--        <span class="price-num"-->
                                            <!--          ><span class="currency">$</span>256<span class="year">/yr</span></span-->
                                            <!--        >-->
                                            <!--      </p>-->
                                            <!--      <h2 class="description">-->
                                            <!--        Annual Plan Per Person-->
                                            <!--      </h2>-->
                                            <ul v-if="features[plan.ID]">
                                                <template v-for="(feature, index) in features[plan.ID]">
<!--                                                    <li v-if="feature.Is_Applicable !== 'false'" :key="feature.ID">-->
<!--                                                        <img src="--><?php //echo get_bloginfo('stylesheet_directory'); ?><!--/assets/feature-icon.png" alt="" />-->
<!--                                                        {{ feature.display_value }}-->
<!--                                                        <div v-if="feature.Tooltip_Information" class="tooltip2">-->
<!--                                                            <img style="float: right; margin-left: 10px" src="--><?php //echo get_bloginfo('stylesheet_directory'); ?><!--/assets/tooltip.png" alt="" />-->
<!--                                                            <span class="tooltiptext2">{{feature.Tooltip_Information}}</span>-->
<!--                                                        </div>-->
<!--                                                    </li>-->
                                                    <template v-for="sub in feature.sub">
                                                        <li v-if="sub.Is_Applicable !== 'false'" :key="sub.ID">
                                                            <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/feature-icon.png" alt="" />
                                                            {{ sub.display_value }}
                                                            <div v-if="sub.Tooltip_Information" class="tooltip2">
                                                                <img style="float: right; margin-left: 10px" src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/tooltip.png" alt="" />
                                                                <span class="tooltiptext2">{{sub.Tooltip_Information}}</span>
                                                            </div>
                                                        </li>
                                                    </template>
                                                </template>
                                            </ul>
                                        </div>
                                        <div
                                                :class="{'btn-container': true, default: plan.Is_Most_Popular === 'Yes'}">
                                            <a :href="buildQueryString('/form1', {
                                                                          plan_id: plan.ID,
                                                                          product_type: plan.Product_Type.display_value,
                                                                          product_type_id: plan.Product_Type.ID,
                                                                          plan: plan.Feature_Plan
                                                                        })">
                                                <button>Request a Quote</button>
                                            </a>
                                        </div>
                                    </div>
                                </pricing-card>
                            </div>
                            <div>
                                <a :href="'/plan-all?type='+currentType"><h1 class="see-all">See Full Comparison <i class="fa fa-long-arrow-alt-right"></i></h1></a>
                            </div>
                        </div>
                    </div>
                </product-types>
            </div>

        </div>
    </div>
<?php
endwhile; // End of the loop.

get_footer();
