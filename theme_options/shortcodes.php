<?php

add_shortcode('lichtenberg_quick_links', 'lichtenberg_quick_links');
function lichtenberg_quick_links($atts = [], $content = '', $tag = '') {

    $content = '';
    $counter = 0;

    foreach ($atts as $page_id) {
        $even = $counter % 2 == 0;
		$p = get_post($page_id);

        $img = get_post_meta($p->ID, 'lichtenberg_header_img', true);
		$event_img = get_post_meta($p->ID, '_ad_ev_meta_image', true);
		$img_id = !!$event_img ? $event_img : $img;

        if (!!$img) {
			$img = wp_get_attachment_image_src($img_id, 'medium_large')[0];
		} else {
			$img = '';
		}


		$brightness = get_post_meta($p->ID, 'lichtenberg_brightness', true);
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