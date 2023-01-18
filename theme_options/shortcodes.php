<?php

add_shortcode('aadh_quick_links', 'aadh_quick_links');
function aadh_quick_links($atts = [], $content = '', $tag = '') {

    $content = '';
    $counter = 0;

    foreach ($atts as $page_id) {
        $even = $counter % 2 == 0;
		$p = get_post($page_id);
		if (!$p) {
			continue;
		}

        $img = get_post_meta($p->ID, 'aadh_header_img', true);
		$event_img = get_post_meta($p->ID, '_ad_ev_meta_image', true);
		$img_id = !!$event_img ? $event_img : $img;

        if (!!$img) {
			$img = wp_get_attachment_image_src($img_id, 'medium_large')[0];
		} else {
			$img = '';
		}


		$brightness = get_post_meta($p->ID, 'aadh_brightness', true);
		$brightness = !!$brightness ? $brightness : 0.2;

        $preload = mt_rand(0,3);
        $un = '';
        if($even) {
            $un = 'un';
        }

        $content .= '
        <div class="home-links-wrapper">
            <div class="home-links-'.$un.'even  preload'. $preload .'" style="background-image: url('.$img.')">
                <a href="'. get_post_permalink($p->ID) .'">
                    <div class="inner-link" style="background: rgba(0,0,0,'. $brightness .');">
                        <h2>'.strtoupper($p->post_title).'</h2>
                    </div>
                </a>
            </div>
        </div>';

        $counter++;
    }

    return $content;
}

add_shortcode('aadh_posts_preview', 'aadh_posts_preview');
function aadh_posts_preview($atts = [], $content = '', $tag = '') {

    //////////// prepare $atts /////////////////
    // normalize attribute keys, lowercase
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );

	// override default attributes with user attributes
	$atts = shortcode_atts(
		array(
			'searchbar' => 'TRUE',
            'categories' => array_column(get_categories(), 'name'),
            'delimiter' => ',',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
            'date_format' => 'd. M Y, H:m'
		), $atts, $tag
	);

    if (is_string($atts['categories'])) {
        $atts['categories'] = explode($atts['delimiter'], $atts['categories']);
    }



    /**************** searchbar ******************/
    if ($atts['searchbar'] === 'TRUE') {
        wp_localize_script( 'search', 'myAjax', ['container' => 'addh-search']);

        $content .= '<div class="aadh-search">
        <input class="aadh-search-bar">
            <div class="aadh-clear-all" onclick="clear_search()">X</div>
            <div class="aadh-search-title">Suchen</div>
        </div>
        <div class="hash_wrapper">
        ';
    
        foreach($atts['categories'] as $term){
            $content .= "<div class='aadh-hash'>#".trim($term)."</div>";
        }
        
        $content .= '</div>';
    }


    /**************** previews ******************/
    $paged = $_GET['paged'] ?? 1;
    $cat_ids = [];

    foreach ($atts['categories'] as $cat) {
        array_push($cat_ids, get_cat_id($cat));
    }


    $_nonce = wp_create_nonce( 'paged_posts' );
    wp_localize_script( 'get_posts', 'args', array_merge([
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => $_nonce,
        'cat_ids' => $cat_ids,
        'paged' => $paged,
        'container' => '#previews',
    ], $atts));

    $content .= '<div id="previews"></div>';

    return $content;
}