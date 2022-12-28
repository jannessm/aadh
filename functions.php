<?php 
	/*
		add js
	*/
	function scripts() {
    	wp_enqueue_script( 'animations', get_template_directory_uri() . '/animations.js', array( 'jquery' ), '1.0.0', true );
	}
	add_action( 'wp_enqueue_scripts', 'scripts' );

	function ajax_scripts(){
	   wp_register_script( "get_news", get_template_directory_uri() . '/search.js', array('jquery') );
	   wp_localize_script( 'get_news', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        

	   wp_enqueue_script( 'jquery' );
	   wp_enqueue_script( 'get_news' );
	}

	//add menus
	add_action('init', 'reg_menus');
	function reg_menus(){
		register_nav_menus(
			array(
				'main-menu' =>  __('Main Menu'),
				'collapse-menu' => __('Collapse Menu'),
				'footer-menu' => __('Footer Menu')
			)
		);
	}
	// add_action('init', 'ajax_scripts');

	require_once (get_stylesheet_directory() . '/theme_options/walker.php');
	require_once (get_stylesheet_directory() . '/theme_options/footer.php');
	require_once (get_stylesheet_directory() . '/theme_options/meta_boxes.php');
	add_action('add_meta_boxes', ['PageMetaBox', 'add']);
	add_action('save_post', ['PageMetaBox', 'save']);
	// require_once (get_stylesheet_directory() . '/theme_options/rss.php');
	require_once (get_stylesheet_directory() . '/paginated_posts.php');
	require_once (get_stylesheet_directory() . '/theme_options/shortcodes.php');
	// add_action( 'wp_footer', 'update_rss_posts' );
	// add_action('wp_ajax_get_news', 'get_news');
	// add_action('wp_ajax_nopriv_get_news', 'get_news');


function needs_excerpt( $more_link_text = null, $stripteaser = false )
{
    $excerpt = apply_filters( 'the_excerpt', get_the_excerpt() );

    $content = get_the_content( $more_link_text, $stripteaser );
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);

    $stripped_content = strip_tags( $content );
    $content_length   = mb_strlen( $stripped_content, 'utf-8' );
    $excerpt_length   = mb_strlen( $excerpt, 'utf-8' );

    // $content is just 20% longer than excerpt. Adjust this to your needs.
    if ( ( $excerpt_length * 1.2 ) >= $content_length )
    {
        return strip_tags($stripped_content);
    }
    return strip_tags($excerpt) . $more_link_text;
}

// retrieves the attachment ID from the file URL
function get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid RLIKE '%s';", wp_make_link_relative($image_url) ));
    return $attachment[0];
}
?>