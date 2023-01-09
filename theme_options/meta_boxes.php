<?php
	/*
	add theme option menu to modify the text displayed in the footer
	*/
	// Add to the admin_init action hook
	const META_BOX = 'aadh_';
	abstract class PageMetaBox {
		const fields = ['header_img', 'brightness', 'event_sidebar', 'title'];

		/**
		 * Set up and add the meta box.
		 */
		public static function add() {
			add_meta_box(
				'aadh_meta_box',  // Unique ID
				'Theme Einstellungen', 			// Box title
				[ self::class, 'html' ],    // Content callback, must be of type callable
			);
		}
	
	
		/**
		 * Save the meta box selections.
		 *
		 * @param int $post_id  The post ID.
		 */
		public static function save( int $post_id ) {
			foreach (PageMetaBox::fields as $key) {
				$name = META_BOX . $key;
				if ( array_key_exists( $name, $_POST ) ) {
					update_post_meta(
						$post_id,
						$name,
						$_POST[$name]
					);
				} elseif ($key === 'event_sidebar') {
					update_post_meta(
						$post_id,
						$name,
						'off'
					);
				}
			}
		}
	
	
		/**
		 * Display the meta box HTML to the user.
		 *
		 * @param \WP_Post $post   Post object.
		 */
		public static function html( $post ) {			
			$title = get_post_meta( $post->ID, META_BOX . 'title', true);

			$brightness = get_post_meta( $post->ID, META_BOX . 'brightness', true);
			$brightness = !!$brightness ? (float) $brightness : 0.0;

			$sidebar = self::sidebar($post->ID);
	
			if (get_post_type($post) !== 'event') {
				$image_id = get_post_meta( $post->ID, META_BOX . 'header_img', true);
				$image_id = !!$image_id ? $image_id : '';

				self::get_image_selector( $post, $image_id );
			}

			?>
			<label for="<?php echo META_BOX;?>brightness">Helligkeit</label>
			<input name="<?php echo META_BOX; ?>brightness" type="range" min="0.0" max="1.0" step="0.05" value="<?php echo $brightness;?>"><br>

			<label for="<?php echo META_BOX;?>event_sidebar">Sidebar</label>
			<input name="<?php echo META_BOX; ?>event_sidebar" type="checkbox" <?php checked($sidebar);?>><br>

			<label for="<?php echo META_BOX;?>title">Titel</label>
			<textarea name="<?php echo META_BOX; ?>title"><?php echo $title;?></textarea><br>
			<?php
		}

		public static function sidebar($post_id) {
			$sidebar = get_post_meta( $post_id, META_BOX . 'event_sidebar', true);

			$sidebar = !$sidebar ? true : $sidebar == 'on';
			return $sidebar;
		}
	
	
		static function get_image_selector( $post, $image_id ) {
	
			wp_localize_script(
				'image-select',
				'args',
				array(
					'image_id' => $image_id,
					'image_container' => ".img-preview-container"
				)
			);
			
			// Get WordPress' media upload URL
			$upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
			
			// Get the image src
			$image_src = wp_get_attachment_image_src( $image_id );
	
			// For convenience, see if the array is valid
			$is_image = is_array( $image_src );

			?>
	
			<label for="<?php echo META_BOX; ?>image" class="ad_ev_meta_box">Titelbild</label>
			<div class="ad_ev_meta_box" style="display: inline-block">
				<!-- Your image container, which can be manipulated with js -->
				<div class="img-preview-container" style="max-width:300px !important">
					<?php if ( $is_image ) : ?>
						<img src="<?php echo $image_src[0] ?>" alt="" style="" />
					<?php endif; ?>
				</div>
	
				<!-- Your add & remove image links -->
				<p class="hide-if-no-js">
					<a class="upload-custom-img <?php if ( $is_image  ) { echo 'hidden'; } ?>" 
					href="<?php echo $upload_link ?>">
						<?php _e('Set custom image') ?>
					</a>
					<a class="delete-custom-img <?php if ( ! $is_image  ) { echo 'hidden'; } ?>" 
					href="#">
						<?php _e('Remove this image') ?>
					</a>
				</p>
	
				<!-- A hidden input to set and post the chosen image id -->
				<input class="image-id" name="<?php echo META_BOX; ?>header_img" type="hidden" value="<?php echo esc_attr( $image_id ); ?>">
			</div>
			<br>
			
			<?php
		}
	}
