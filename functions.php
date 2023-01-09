<?php 
	/*
		add js
	*/
	function scripts() {
    	wp_enqueue_script( 'animations', get_template_directory_uri() . '/js/animations.js', array( 'jquery' ), '1.0.0', true );
    	wp_enqueue_script( 'search', get_template_directory_uri() . '/js/search.js', array( 'jquery' ), '1.0.0', true );
    	wp_enqueue_script( 'get_posts', get_template_directory_uri() . '/js/get_posts.js', array( 'jquery' ), '1.0.0', true );
	}
	add_action( 'wp_enqueue_scripts', 'scripts' );

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

	require_once (get_stylesheet_directory() . '/theme_options/paged_posts_ajax.php');
	require_once (get_stylesheet_directory() . '/theme_options/walker.php');
	require_once (get_stylesheet_directory() . '/theme_options/footer.php');
	require_once (get_stylesheet_directory() . '/theme_options/meta_boxes.php');
	add_action('add_meta_boxes', ['PageMetaBox', 'add']);
	add_action('save_post', ['PageMetaBox', 'save']);
	require_once (get_stylesheet_directory() . '/theme_options/shortcodes.php');


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

?>