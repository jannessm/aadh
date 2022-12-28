<?php
/*
   Template Name: Posts
*/
?>
	<?php get_header();?>

	<div id="content-wrapper">
	  <div id="content">
      <div class="search">
         <input class="search_bar">
         <div class="clear_all" onclick="clear_search()">X</div>
         <div class="search_title">Suchen</div>
      </div>
      <div class="hash_wrapper">
      <?php
         foreach(get_categories() as $term){
            echo "<div class='hash'>#".$term->name."</div>";
         }
      ?>
      </div>

      <div id="post_wrapper"></div>

      </div>
      <?php
		$sidebar = get_post_meta( $post->ID, 'lichtenberg_sidebar', true);
		var_dump($sidebar);
		if (!!$sidebar) {
			?>
			<div class="sidebar">
				<?php echo do_shortcode('[ad_ev_sidebar]');?>
			</div>
			<?php
		}
		?>
   </div>
	<?php	get_footer();?>