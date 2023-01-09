<?php 



// function get_news(){
// 	$echo = '';
	
// 	//parse all posts
// 	$paged = $_REQUEST['paged'] ? absint( $_REQUEST['paged'] ) : 1;
// 	$search_key = $_REQUEST['search_key'] ? trim(urldecode($_REQUEST['search_key'])) : '';
// 	$parsed = parse_terms_search_keys($search_key);

// 	$args = array(
// 	    'post_type' => 'post',
// 	    'posts_per_page' => 3,
// 	    'paged' => $paged
// 	);

// 	if($parsed['terms'] !== '')
// 		$args['category_name'] = $parsed['terms'];
// 	if($parsed['search_keys'] !== '')
// 		$args['s'] = $parsed['search_keys'];
	
// 	$query = new WP_Query($args);
// 	$i = 0;
// 	//the loop
// 	if ($query->have_posts()){
// 		while ($query->have_posts()){
// 			$query->the_post();

// 			$echo .= get_the_post($i%2==0);
// 			$i++;
// 		}
// 	}else
// 		$echo .= '<h5 style="text-align: center">Keine Beiträge verfügbar.</h5>';



// 	$echo .= '<div id="page-links">';
// 	//page links

// 	$pagination = paginate_links( array(
// 	    'base' => '',
// 	    'current' => max( 1, $paged ),
// 	    'total' => $query->max_num_pages,
// 	    'type' => 'array',
// 	    'prev_text' => __('< Zur&uuml;ck '),
// 	    'next_text' => __(' N&auml;chste >'),
// 	));

// 	$pages = 0;

// 	$total = count($pagination)-count(preg_grep('/prev|next/', $pagination));
// 	$separator = ' &#8901; ';
// 	foreach ($pagination as $page) {
// 	    //if next or prev
// 	    if(strpos($page, 'prev')>0 || strpos($page, 'next')>0)
// 		    $echo .= $page.'  ';
// 	    elseif((strpos($page, 'prev')==0 || strcmp($page, 'next')==0) && $pages==$total-1){
// 	        $echo .= $page.' ';
// 	        $pages++;
// 	    }else{
// 	        $pages++;
// 	        $echo .= $page.$separator;
// 	    }
// 	}
// 	$echo .= '</div>';

//     echo json_encode(array( "content" => $echo));
	
//     die();
// } 


// function get_the_post($even){

// 	$preload = mt_rand(0,3);
// 	$hash = '<div class="hash" onclick="add_hash(\'#'.get_the_category()[0]->name.'\')">#'.get_the_category()[0]->name.'</div>';
// 	$meta_link = rwmb_meta('post-meta-link') ? rwmb_meta('post-meta-link') : get_permalink();
// 	$meta_img = rwmb_meta('header-meta-img') ? rwmb_meta('header-meta-img') : 'http://sta-licht.de/wp-content/uploads/2020/10/priscilla-du-preez-nF8xhLMmg0c-unsplash-1.jpg';
// 	if($even){
// 		return '<div class="lr-wrapper">
// 	            <a class="left headline preload'.$preload.'" style="background-image: url('.$meta_img.');" href="'.rwmb_meta('post-meta-link').'" target="_blank">
// 	            </a>
	            
// 	            <div class="right"><a class="no_hover" href="'.$meta_link.'" target="_blank">
// 	               <h5>'.strtoupper(the_title('','',false)).'</h5>
// 	               <div class="title"></div>'.needs_excerpt( sprintf( '', $meta_link ) ).'</a>
// 	               '.$hash.'
// 	            </div>
// 	         </div>';
// 	}else{
// 		return '<div class="lr-wrapper" href="'.$meta_link.'" target="_blank">
// 	            <div class="left"><a class="no_hover" href="'.$meta_link.'" target="_blank">
// 	               <h5>'.strtoupper(the_title('','',false)).'</h5>
// 	               <div class="title"></div>'.needs_excerpt( sprintf( '', $meta_link ) ).'</a>
// 	               '.$hash.'
// 	            </div>
	         
// 	            <a class="right headline preload'.$preload.'" style="background-image: url('.$meta_img.');" href="'.$meta_link.'" target="_blank">
// 	            </a>
// 	         </div>';
// 	}

// }

// function parse_terms_search_keys($in){
// 	$search_keys = '';
// 	$terms = '';
//     $hashs = array(
//         'heilig.berlin',
//         'apd.info'
//     );
// 	$hashs = get_categories();

// 	foreach(explode(' ', $in) as $value){
//         // if value is a hash
// 	    if (strpos($value, '#') !== false){
//             $value = str_replace('#', '', $value);

//             foreach ($hashs as $hash) {
// 				if(strpos($hash->name, $value) !== false)
//                     $terms .= $hash->name.',';
//             }
//         // if normal searchkey
//         }else{
//             $search_keys .= $value.' ';
//         }
//     }

// 	return array("search_keys" => $search_keys, "terms" => substr($terms, 0, strlen($terms)-1));
// }

?>
