<?php
/**
 * Template Name: ICIGAI - Form1
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
                <form1 inline-template>
                    <div class="" v-cloak>
                        <loading
                                :active.sync="isLoading"
                                :can-cancel="true"
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
                                    <div v-for="(step, index) in steps" :class="{'progress-step': true, 'active': index <= 0}" @click="goToStep(index, 0)">
                                        <span class="step-one">{{index+1}}</span>
                                        <p class="progress-step__text">{{step.name}}</p>
                                    </div>
                                </div>
                            </header-wizard>
                            <div class="row2">
                                <h1>Basic information</h1>
                            </div>
                            <div class="row2">
                                <div class="form-element">
                                    <label for=""
                                    >What is the product type ? <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <input v-model="product_type" disabled placeholder="Product Type" type="text" />
                                        <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/contract-icon.png" alt="" />
                                    </div>
                                </div>
                                <div class="form-element">
                                    <label for=""
                                    >Feature Plan you choose <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <input v-model="plan" disabled type="text" />
                                        <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/puzzle-icon.png" alt="" />
                                    </div>
                                </div>
                            </div>
                            <div class="row2">
                                <div class="form-element row2">
                                    <label for=""
                                    >What is the type of institution?
                                        <span class="required">*</span></label
                                    >
                                    <label class="radio" v-for="institutionType in institutionTypes">
                                        <input type="radio" :value="institutionType.ID" v-model="institution" />
                                        {{institutionType.Institution_Type}}
                                    </label>
                                </div>
                            </div>
                            <div class="row2">
<!--                                <div class="form-element">-->
<!--                                    <label for=""-->
<!--                                    >Number of licenses you need-->
<!--                                        <span class="required">*</span></label-->
<!--                                    >-->
<!--                                    <div class="input-container">-->
<!--                                        <input placeholder="Select a number" type="number" v-model="licenses" />-->
<!--                                    </div>-->
<!--                                </div>-->
                                <div class="form-element" v-if="planData.Test_Vouchers_Applicable && planData.Test_Vouchers_Applicable !== 'false'">
                                    <label for=""
                                    >Number of vouchers you need
                                        <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <input placeholder="Select a number" type="number" v-model="vouchers" />
                                    </div>
                                </div>
                            </div>
                            <div class="row2" v-if="product_type_id === '4046528000000040019'" style="margin-top: 0">
                                <div class="form-element" style="margin-top: 0">
                                    <label for=""
                                    >Monthly / Yearly? <span class="required">*</span></label
                                    >
                                    <label class="radio">
                                        <input type="radio" name="bundle" value="Monthly" v-model="Monthly_Yearly" />
                                        Monthly
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="bundle" value="Yearly" v-model="Monthly_Yearly" />
                                        Yearly
                                    </label>
                                </div>
                            </div>
                            <div class="row2" v-if="product_type_id === '4046528000000040019'" style="margin-top: 0">
                                <div class="form-element" style="margin-top: 0">
                                    <div class="form-element" style="margin-top: 0">
                                        <label for=""
                                        >Voucher Bundles
                                            <span class="required"></span></label
                                        >
                                        <div class="input-container">
                                            <select name="" v-model="bundle" id="">
                                                <option value="">Select</option>
                                                <option v-for="option in vouchersBundle" :key="option.ID" :value="option.ID">{{option.Voucher_Bundle_Name}} - {{option.Price}}$</option>
                                            </select>
                                            <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/arrow-down.png" alt="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="row2">
                                <div class="form-element">
                                    <label for=""
                                    >No of Admins/Teachers
                                        <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <input placeholder="Select a number" type="number" v-model="teachers" />
                                    </div>
                                </div>
                                <div class="form-element">
                                    <label for=""
                                    >No of Students
                                        <span class="required">*</span></label
                                    >
                                    <div class="input-container">
                                        <input placeholder="Select a number" type="number" v-model="students" />
                                    </div>
                                </div>
                            </div>
                            <div class="row2" style="margin-top: 40px">
                                <h1>Training Requirements</h1>
                            </div>
                            <div class="row2" style="margin-top: 0">
                                <div class="form-element row2" style="margin-top: 0">
                                    <label for=""
                                    >Is training required? <span class="required">*</span></label
                                    >
                                    <label class="radio">
                                        <input type="radio" name="required" value="Yes" v-model="required" />
                                        Yes
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="required" value="No" v-model="required" />
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="row2" v-if="required === 'Yes'">
                                <div class="form-element">
                                    <label for=""
                                    >Type of training
                                        <span class="required"></span></label
                                    >
                                    <div class="input-container">
                                        <select name="" v-model="training_type" id="">
                                            <option value="">Select</option>
                                            <option v-for="option in productTrainingTypes" :key="option.ID" :value="option.ID">{{option.Training_Name}}</option>
                                        </select>
                                        <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/arrow-down.png" alt="" />
                                    </div>
                                </div>
                                <div class="form-element">
                                    <label for=""
                                    >No. of users needs to be trained
                                        <span class="required"></span></label
                                    >
                                    <div class="input-container">
                                        <input placeholder="Select a number" type="number" v-model="n_users_training" />
                                    </div>
                                </div>
                            </div>
                            <div class="row2">
                                <p class="note">
                                    In order to proceed to the next step, it is necessary to fill in the
                                    information above. Please note that all fields that are marked with an
                                    asterisk (*) are required!
                                </p>
                            </div>
                            <div class="row2 btn-container">
                                <button class="back-btn" @click="goBack()">Back</button>
                                <a :href="buildQueryString('/packages', {
                                                                            required,
                                                                            institution,
                                                                            licenses,
                                                                            vouchers,
                                                                            teachers,
                                                                            students,
                                                                            training_type,
                                                                            Monthly_Yearly,
                                                                            bundle,
                                                                            n_users_training,
                                })">
                                    <button class="proceed-btn">Proceed</button>
                                </a>
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
