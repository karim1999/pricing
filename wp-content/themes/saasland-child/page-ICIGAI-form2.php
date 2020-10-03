<?php
/**
 * Template Name: ICIGAI - Form2
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
                                    <div v-for="(step, index) in steps" :class="{'progress-step': true, 'active': index <= 4}">
                                        <span class="step-one">{{index+1}}</span>
                                        <p class="progress-step__text">{{step.name}}</p>
                                    </div>
                                </div>
                            </header-wizard>
                            <div class="row">
                                <h1>Basic information</h1>
                            </div>
                            <div class="row">
                                <div class="form-element">
                                    <label for=""
                                    >First Name <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <input v-model="firstName" placeholder="First Name" type="text" />
                                        <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/first-name-icon.png" alt="" />
                                    </div>
                                </div>
                                <div class="form-element">
                                    <label for=""
                                    >Last Name <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <input v-model="lastName" placeholder="Last Name" type="text" />
                                        <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/contract-icon.png" alt="" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-element">
                                    <label for=""
                                    >Email Address <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <input v-model="email" placeholder="Email Address" type="text" />
                                        <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/email-icon.png" alt="" />
                                    </div>
                                </div>
                                <div class="form-element">
                                    <label for=""
                                    >Phone <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <input v-model="phone" placeholder="Phone" type="text" />
                                        <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/phone-icon.png" alt="" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-element">
                                    <label for=""
                                    >Institution type <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <select name="" v-model="institutionType" id="">
                                            <option value="">Select</option>
                                            <option v-for="option in institutionTypes" :key="option.value" :value="option.value">{{option.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-element">
                                    <label for=""
                                    >Institution <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <input v-model="institutionName" placeholder="Institution Name" type="text" />
                                        <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/institution-icon.png" alt="" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <p class="note">
                                    In order to proceed to the next step, it is necessary to fill in the
                                    information above. Please note that all fields that are marked with an
                                    asterisk (*) are required!
                                </p>
                            </div>
                            <div class="row btn-container">
                                <button class="back-btn" @click="goBack()">Back</button>
                                <button class="proceed-btn" @click="proceed">Summary</button>
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
