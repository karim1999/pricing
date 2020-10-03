<?php
/**
 * Template Name: ICIGAI - Hosting
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
                <div class="" v-cloak>
                    <header-component
                            :title="title"
                            :description="description"
                            :img="img"
                    ></header-component>
                    <div class="content">
                        <h1 class="learn-more">Learn more about SwiftAssess</h1>
                        <div class="hosting-plans">
                            <hosting-card
                                    v-for="hosting in hostings"
                                    :key="hosting.type"
                                    :hosting="hosting"
                            ></hosting-card>
                        </div>
                        <h1 class="why">Why use Microsoft Azure?</h1>
                        <div class="">
                            <div class="why-images">
                                <img src="../assets/why1.png" alt="" />
                                <img src="../assets/why2.png" alt="" />
                            </div>
                            <div class="why-images">
                                <img src="../assets/why3.png" alt="" />
                                <img src="../assets/why4.png" alt="" />
                            </div>
                        </div>
                    </div>
                    <header-component
                            title="Interested in this Service?"
                            description="You can request this service"
                            img="header-img3.png"
                    >
                        <div class="header-btns">
                            <button class="btn1">Tell us what you think</button>
                            <button class="btn2">Get this service</button>
                        </div>
                    </header-component>
                </div>
            </div>

        </div>
    </div>
<?php
endwhile; // End of the loop.

get_footer();
