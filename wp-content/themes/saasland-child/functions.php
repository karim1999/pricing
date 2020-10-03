<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Setup My Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
 
function saasland_child_theme_setup() {
    load_child_theme_textdomain( 'saasland-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'saasland_child_theme_setup' );


// BEGIN ENQUEUE PARENT ACTION
if ( !function_exists( 'saasland_child_thm_parent_css' ) ):
    function saasland_child_thm_parent_css() {

        if ( is_page() ) {
            wp_enqueue_style(
                'saasland-child-parent-theme-root',
                trailingslashit(get_template_directory_uri()) . 'style.css',
                array('saasland-root', 'saasland-elements', 'saasland-fonts', 'bootstrap', 'eleganticons', 'themify-icon', 'saasland-main', 'saasland-wpd', 'saasland-gutenberg')
            );
        }

        if ( is_singular('case_study') ) {
            wp_enqueue_style(
                'saasland-child-parent-theme-root',
                trailingslashit(get_template_directory_uri()) . 'style.css',
                array('saasland-root', 'saasland-cstudy', 'saasland-fonts', 'bootstrap', 'eleganticons', 'themify-icon', 'saasland-main', 'saasland-wpd', 'saasland-gutenberg')
            );
        }

        if ( is_singular( 'job' ) || is_page_template('page-job-apply-form.php') || is_singular( 'service' ) ) {
            wp_enqueue_style(
                'saasland-child-parent-theme-root',
                trailingslashit(get_template_directory_uri()) . 'style.css',
                array('saasland-root', 'saasland-job', 'saasland-fonts', 'bootstrap', 'eleganticons', 'themify-icon', 'saasland-main', 'saasland-wpd', 'saasland-gutenberg')
            );
        }

        if ( is_singular('portfolio') ) {
            wp_enqueue_style(
                'saasland-child-parent-theme-root',
                trailingslashit(get_template_directory_uri()) . 'style.css',
                array('saasland-root', 'saasland-portfolio', 'saasland-fonts', 'bootstrap', 'eleganticons', 'themify-icon', 'saasland-main', 'saasland-wpd', 'saasland-gutenberg')
            );
        }

        if ( is_home() || is_singular( 'post' ) || is_search() || is_archive() ) {
            wp_enqueue_style (
                'saasland-child-parent-theme-root',
                trailingslashit ( get_template_directory_uri() ) . 'style.css',
                array ( 'saasland-root', 'saasland-blog', 'saasland-fonts', 'bootstrap', 'eleganticons', 'themify-icon', 'saasland-main', 'saasland-wpd', 'saasland-gutenberg' )
            );
        }
    }
endif;
add_action( 'wp_enqueue_scripts', 'saasland_child_thm_parent_css', 12 );

add_action( 'wp_enqueue_scripts', function() {
	if(
		is_page_template("page-ICIGAI-summary.php") ||
		is_page_template("page-ICIGAI-hosting.php") ||
		is_page_template("page-ICIGAI-addons.php") ||
		is_page_template("page-ICIGAI-form2.php") ||
		is_page_template("page-ICIGAI-services.php") ||
		is_page_template("page-ICIGAI-product-types.php") ||
	   is_page_template("page-ICIGAI-plan-all.php") ||
	   is_page_template("page-ICIGAI-form1.php") ||
	   is_page_template("page-ICIGAI-packages.php")
	){
		wp_enqueue_script( 'axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js', null, null, true ); // change to vue.min.js for production
		wp_enqueue_script( 'api_file', get_bloginfo('stylesheet_directory') . '/assets/js/api/Product.js', 'axios', null, true );
		wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', 'api_file', null, true ); // change to vue.min.js for production
		wp_enqueue_script( 'vue_loading', 'https://cdn.jsdelivr.net/npm/vue-loading-overlay@3', 'vue', null, true );
		wp_enqueue_style ( 'vue_loading_css', 'https://cdn.jsdelivr.net/npm/vue-loading-overlay@3/dist/vue-loading.css' );
		wp_enqueue_script( 'components_js', get_bloginfo('stylesheet_directory') . '/assets/js/components.js', 'vue', null, true );
	}
	if(is_page_template("page-ICIGAI-product-types.php")){
		wp_enqueue_style ( 'product_types_css', get_bloginfo('stylesheet_directory') . '/assets/sass/product_types.css' );
		wp_enqueue_script( 'product_types_js', get_bloginfo('stylesheet_directory') . '/assets/js/product_types.js', 'vue', null, true );
	}
	if(is_page_template("page-ICIGAI-plan-all.php")){
		wp_enqueue_style ( 'plan_all_css', get_bloginfo('stylesheet_directory') . '/assets/sass/plan_all.css' );
		wp_enqueue_script( 'plan_all_js', get_bloginfo('stylesheet_directory') . '/assets/js/plan_all.js', 'vue', null, true );
	}
	if(is_page_template("page-ICIGAI-form1.php")){
		wp_enqueue_style ( 'form1_css', get_bloginfo('stylesheet_directory') . '/assets/sass/form1.css' );
		wp_enqueue_script( 'form1_js', get_bloginfo('stylesheet_directory') . '/assets/js/form1.js', 'components_js', null, true );
	}
	if(is_page_template("page-ICIGAI-packages.php")){
		wp_enqueue_style ( 'packages_css', get_bloginfo('stylesheet_directory') . '/assets/sass/packages.css' );
		wp_enqueue_script( 'packages_js', get_bloginfo('stylesheet_directory') . '/assets/js/packages.js', 'components_js', null, true );
	}
	if(is_page_template("page-ICIGAI-form2.php")){
		wp_enqueue_style ( 'form2_css', get_bloginfo('stylesheet_directory') . '/assets/sass/form2.css' );
		wp_enqueue_script( 'form2_js', get_bloginfo('stylesheet_directory') . '/assets/js/form2.js', 'components_js', null, true );
	}
	if(is_page_template("page-ICIGAI-services.php")){
		wp_enqueue_style ( 'services_css', get_bloginfo('stylesheet_directory') . '/assets/sass/services.css' );
		wp_enqueue_script( 'services_js', get_bloginfo('stylesheet_directory') . '/assets/js/services.js', 'components_js', null, true );
	}
	if(is_page_template("page-ICIGAI-addons.php")){
		wp_enqueue_style ( 'addons_css', get_bloginfo('stylesheet_directory') . '/assets/sass/addons.css' );
		wp_enqueue_script( 'addons_js', get_bloginfo('stylesheet_directory') . '/assets/js/addons.js', 'components_js', null, true );
	}
	if(is_page_template("page-ICIGAI-hosting.php")){
		wp_enqueue_style ( 'hosting_css', get_bloginfo('stylesheet_directory'). '/assets/sass/hosting.css' );
		wp_enqueue_script( 'hosting_js', get_bloginfo('stylesheet_directory') . '/assets/js/hosting.js', 'components_js', null, true );
	}
	if(is_page_template("page-ICIGAI-summary.php")){
		wp_enqueue_style ( 'summary_css', get_bloginfo('stylesheet_directory'). '/assets/sass/summary.css' );
		wp_enqueue_script( 'summary_js', get_bloginfo('stylesheet_directory') . '/assets/js/summary.js', 'components_js', null, true );
	}
}, 14);

//add_action( 'wp_print_scripts', 'pp_deregister_javascript', 99 );
// END ENQUEUE PARENT ACTION