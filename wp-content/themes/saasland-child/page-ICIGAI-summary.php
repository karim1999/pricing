<?php
/**
 * Template Name: ICIGAI - Summary
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
                <form1 inline-template :previous-data='JSON.parse("<?php echo $_POST['api_data']; ?>")'>
                    <div class="" v-cloak>
                        <loading
                                :active.sync="isLoading"
                                :can-cancel="false"
                                :is-full-page="true"
                        ></loading>
<!--                        <header-component-->
<!--                                :title="title"-->
<!--                                :description="description"-->
<!--                                :img="img"-->
<!--                                inline-template-->
<!--                        >-->
<!--                            <div class="header-container">-->
<!--                                <div class="text">-->
<!--                                    <h1>{{ title }}</h1>-->
<!--                                    <h2>{{ description }}</h2>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </header-component>-->
                        <div class="form-content">
                            <header-wizard inline-template>
                                <div class="progress-field">
                                    <div v-for="(step, index) in steps" :class="{'progress-step': true, 'active': index <= 5}" @click="goToStep(index, 5)">
                                        <span class="step-one">{{index+1}}</span>
                                        <p class="progress-step__text">{{step.name}}</p>
                                    </div>
                                </div>
                            </header-wizard>
                            <div class="row summary-title">
                                <h1>Basic information</h1>
                            </div>
                            <div class="row">
                                <div class="form-element">
                                    <label for="">First Name: </label>
                                    <h2>{{firstName}}</h2>
                                </div>
                                <div class="form-element">
                                    <label for="">Last Name: </label>
                                    <h2>{{lastName}}</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-element">
                                    <label for="">Email Address: </label>
                                    <h2>{{email}}</h2>
                                </div>
                                <div class="form-element">
                                    <label for="">Phone Number: </label>
                                    <h2>{{phone}}</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-element">
                                    <label for="">Institution Type: </label>
                                    <h2>{{institutionType}}</h2>
                                </div>
                                <div class="form-element">
                                    <label for="">Institution Name: </label>
                                    <h2>{{institutionName}}</h2>
                                </div>
                            </div>
                            <div class="row summary-title">
                                <h1>Plan information</h1>
                            </div>
                            <div class="row">
                                <div class="form-element">
                                    <label for="">Product Type: </label>
                                    <h2>{{product_type}}</h2>
                                </div>
                                <div class="form-element">
                                    <label for="">Plan: </label>
                                    <h2>{{plan}}</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-element">
                                    <label for="">Number of Licenses: </label>
                                    <h2>{{licenses}}</h2>
                                </div>
                                <div v-if="vouchers" class="form-element">
                                    <label for="">Number of Vouchers: </label>
                                    <h2>{{vouchers}}</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-element">
                                    <label for="">Selected Package: </label>
                                    <h2>{{package_name}}</h2>
                                </div>
                                <div class="form-element">
                                    <label for="">Is training required? </label>
                                    <h2>{{required}}</h2>
                                </div>
                            </div>
                            <div v-if="required === 'Yes'" class="row">
<!--                                <div class="form-element">-->
<!--                                    <label for="">Training Type: </label>-->
<!--                                    <h2>{{training_type}}</h2>-->
<!--                                </div>-->
                                <div class="form-element">
                                    <label for="">Number of users: </label>
                                    <h2>{{n_users_training}}</h2>
                                </div>
                            </div>
                            <div class="row summary-title">
                                <h1>Selected Services: </h1>
                            </div>
                            <div class="row" v-for="(service, index) in previousData.selectedServices">
                                <div class="form-element">
                                    <label for="">{{index+1}}: </label>
                                    <h2>{{service.name}}</h2>
                                </div>
                            </div>
                            <div class="row summary-title">
                                <h1>Selected Addons: </h1>
                            </div>
                            <div class="row" v-for="(service, index) in previousData.selectedAddons">
                                <div class="form-element">
                                    <label for="">{{index+1}}: </label>
                                    <h2>{{service.name}}</h2>
                                </div>
                            </div>

                            <div class="row btn-container">
                                <button class="back-btn" @click="goBack()">Back</button>
                                <button class="proceed-btn" @click="proceed">Get My Quote</button>
                            </div>

                        </div>
                    </div>
                </form1>
            </div>

        </div>
    </div>
<?php
endwhile; // End of the loop.

get_footer();
