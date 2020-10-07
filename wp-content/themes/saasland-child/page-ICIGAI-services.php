<?php
/**
 * Template Name: ICIGAI - Services
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
                <plan-services inline-template>
                    <div class="" v-cloak>
                        <loading
                                :active.sync="isLoading"
                                :can-cancel="true"
                                :is-full-page="true"
                        ></loading>

                        <div v-for="(service, index) in services" class="modal fade" :id="'exampleModal'+service.ID" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="">
                                            <h1>{{service.Sevice_Name}}</h1>
                                        </div>
                                        <div class="row2 main-container">
                                            <div class="img-container">
<!--                                                <img-->
<!--                                                        v-if="service.Image_URL"-->
<!--                                                        class="status-icon"-->
<!--                                                        :src="service.Image_URL.url"-->
<!--                                                        alt=""-->
<!--                                                />-->
                                            </div>
                                            <div class="form-container">
<!--                                                <div class="row2 form1">-->
<!--                                                    <div class="form-element">-->
<!--                                                        <label for="">How many users do you need this for ?</label>-->
<!--                                                        <div class="input-container">-->
<!--                                                            <input v-model="usersNum" placeholder="Product Type" type="number" />-->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                </div>-->
                                                <div class="row2 form2">
                                                    <div class="form-element">
                                                        <label for="">Please place your requirements here (Optional)</label>
                                                        <div class="input-container">
                                                            <textarea v-model="requirements"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row2  button-container">
                                            <button @click="selectService(service)" data-dismiss="modal">Select</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<!--                                </div>-->
<!--                            </div>-->
<!--                        </header-component>-->
                        <div class="services-content">
                            <header-wizard inline-template>
                                <div class="progress-field">
                                    <div v-for="(step, index) in steps" :class="{'progress-step': true, 'active': index <= 2}" @click="goToStep(index, 2)">
                                        <span class="step-one">{{index+1}}</span>
                                        <p class="progress-step__text">{{step.name}}</p>
                                    </div>
                                </div>
                            </header-wizard>
                            <div class="row2">
                                <h1>Professional Services</h1>
                            </div>
                            <div class="row2" v-if="!isLoading && !services">
                                <h2>Services are not available.</h2>
                            </div>
                            <div class="row2 services">
                                    <div
                                            v-for="(service, index) in paginatedServices"
                                            :key="index"
                                            :index="index"
                                            :class="{'service-card': true, active: selectedServices.filter(s => service.ID === s.service).length !== 0}">
                                        <div class="row2">
                                            <img
                                                    v-if="service.Image_URL"
                                                    class="status-icon"
                                                    :src="service.Image_URL.url"
                                                    alt=""
                                            />
                                            <h1 class="title2">{{ service.Sevice_Name }}
                                                <span v-if="selectedServices.filter(s => service.ID === s.service).length !== 0" @click="editService(service)" class="edit-icon"><i class="fa fa-pencil"></i></span></h1>
                                        </div>
                                        <div class="row2">
                                            <img
                                                    v-if="service.Type === 'Highly Recommended'"
                                                    class="status-name"
                                                    src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/status1-name.png"
                                                    alt=""
                                            />
                                            <img
                                                    v-else-if="service.Type === 'Most Popular Service'"
                                                    class="status-name"
                                                    src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/status2-name.png"
                                                    alt=""
                                            />
                                            <img
                                                    v-else-if="service.Type === 'What People like more'"
                                                    class="status-name"
                                                    src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/status3-name.png"
                                                    alt=""
                                            />
                                        </div>
                                        <div class="row2">
                                            <p class="description" v-html="truncate(service.Description, 100)" data-toggle="tooltip" data-html="true" data-placement="right" :title="service.Description"></p>
                                        </div>
                                        <div class="row btn-container">
                                            <button v-show="selectedServices.filter(s => service.ID === s.service).length === 0" data-toggle="modal" @click="openModal('#exampleModal'+service.ID)">Get It Now</button>
                                            <button v-if="selectedServices.filter(s => service.ID === s.service).length !== 0" @click="unSelectService(service)" ><i class="fa fa-check"></i> Checked</button>
                                        </div>
                                    </div>

                            </div>
                                <paginate
                                        v-if="!isLoading && services"
                                        :page-count="pages"
                                        :prev-text="'<<'"
                                        :next-text="'>>'"
                                        container-class="pagination"
                                        page-class="page-item"
                                        :click-handler="clickCallback">
                                </paginate>
                            <div class="row2 btn-container">
                                <button @click="goBack" class="back-btn">Back</button>
                                <button @click="proceed" class="proceed-btn">Proceed</button>
                            </div>
                        </div>
                    </div>
                </plan-services>
            </div>

        </div>
    </div>
<?php
endwhile; // End of the loop.

get_footer();
