<?php

add_action( 'wp_ajax_paged_posts', 'aadh_paged_posts_handler' );
function aadh_paged_posts_handler() {
    check_ajax_referer( 'paged_posts' );
    global $wp_query;

    $query = [
        'posts_per_page' => $_POST['numberposts'],
        'order' => $_POST['order'],
        'orderby' => $_POST['orderby'],
        'category__in' => $_POST['categories'],
        'paged' => $_POST['paged'],
        's' => $_POST['query']
    ];

    $final_posts = [];
    $posts = query_posts($query);

    foreach($posts as $p) {
        array_push($final_posts, [
            'title' => get_the_title($p),
            'content' => get_the_excerpt($p),
            'image_url' => get_post_meta( $p->ID, 'feedzy_item_external_url', true ),
            'link' => get_post_meta( $p->ID, 'feedzy_item_url', true ),
            'date' => get_the_date($_POST['date_format'], $p),
            'categories' => get_the_category($p),
        ]);
    }

    $pages = $wp_query->max_num_pages;
    
    wp_send_json(['posts' => $final_posts, 'pages' => $pages]);
}