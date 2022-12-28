<?php

function update_rss_posts(){
  if(!isset($_GET['update_rss'])){
  	echo '';
  	return;
  }
  
  $file = get_stylesheet_directory() . '/theme_options/rss_post_ids.php';

  $new_ids = array();
  delete_old_posts();
  
  foreach (["https://heilig.berlin/feed/" => 8, "https://www.apd.info/feed/" => 9] as $url => $tag){
   
   $xml = get_xml($url);

   foreach ($xml->channel->item as $post) {
    
    $p = new MyPostClass(0);
    $p->tag = array($tag,);
    $p->title = (string)$post->title;
    $p->link = (string)$post->link;
    if (strpos($url, 'apd') > -1)
     $p->content = MyPostClass::parse_descr($post->description, '<div>', '</div>');
    else
     $p->content = MyPostClass::parse_descr($post->description);
    
    $p->img = MyPostClass::parse_img($post->description);
    $p->time = date( 'Y-m-d H:i:s', strtotime($post->pubDate));
    array_push($new_ids, wp_insert_post($p->export(), true));
   }
  }

  // save added ids
  file_put_contents($file, '<?php $ids = '.var_export($new_ids, true).'; ?>');
  echo '';
}


// ##############  utils ##################
function disp_err(){
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

function delete_old_posts(){
  if (file_exists(get_stylesheet_directory() . '/theme_options/rss_post_ids.php')){
    // load $ids
    require_once( get_stylesheet_directory() . '/theme_options/rss_post_ids.php');

    foreach($ids as $post_id){
      var_dump(wp_delete_post($post_id, true));
    }
  }
}

function get_xml($url){
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $html_content = curl_exec($ch);
   curl_close($ch);

   return simplexml_load_string($html_content);
}

// helper classes
class MyPostClass{

 function __construct($id){
  $this->id = $id;
 }

 function export(){
  return array(
   'ID' => $this->id,
   'post_title' => $this->title,
   'post_content' => $this->content,
   'post_status' => 'publish',
   'post_date' => $this->time,
   'post_category' => $this->tag,
   'meta_input' => array(
        'header-meta-img' => $this->img, 
        'post-meta-link' => $this->link
        
       )
  );
 }

 static function parse_descr($description, $open_tag='<img', $close_tag='>'){
  $string = (string)$description;
  var_dump($string);
  while(strpos($string, $open_tag) !== false){
    $start_img = strpos($string, $open_tag);
    $string = substr($string, 0, $start_img) . substr($string, strpos($string, $close_tag, $start_img) + strlen($close_tag));
  }
  var_dump($string);
  echo '';
  return $string;
 }

 static function parse_img($description){
  $string = (string)$description;

  do{

    $start_img = strpos($string, '<img');
    $start_src = strpos($string, 'src=', $start_img) + 5;
    $end_img = strpos($string, '"', $start_src) - $start_src;
    $tmp = substr($string, $start_src, $end_img);

    if (strpos($tmp, 'piwik') === false)
      $img = $tmp;
    
    $string = substr($string, 0, $start_img) . substr($string, strpos($string, '>', $start_img));
  }while(strpos($string, '<img') !== false);

  return $img;
 }
}

?>