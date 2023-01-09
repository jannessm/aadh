	<?php 
	/*
		Template Name: Single Post
	*/
	get_header();
	?>

	<div id="content-wrapper">
	<div id="content">
		<?php if (have_posts()) :
				while (have_posts()) :
					the_post(); ?>
					<h6><?php
						$cats = array();
						foreach(get_the_category() as $cat){
							array_push($cats, '<a href="?page_id=7&q=%23'.$cat->name.'">#'.$cat->name.'</a>');
						}
						echo join(', ', $cats);
						?> vom <?php the_date()?>
					</h6>
					<div class="entry">
						<?php the_content(); ?>
					</div>
				</div>
		<?php 	endwhile;
			endif;
		?>
		<?php
		$sidebar = get_post_meta( $post->ID, 'aadh_sidebar', true);

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