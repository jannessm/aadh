<?php
	/*
	add theme option menu to modify the text displayed in the footer
	*/

	add_action('admin_init', 'theme_option_footer_init');
	add_action('admin_menu', 'theme_option_footer_add');

	//register optionpage
	function theme_option_footer_init(){
		register_setting('footer_options', 'footer_theme_options', 'footer_validate_options');
	}

	//add optionpage
	function theme_option_footer_add(){
		add_theme_page('Footer', 'Footer', 'edit_theme_options', 'footer_options', 'theme_option_footer_page');
	}

	//actual page
	function theme_option_footer_page(){
		global $select_options, $radio_options;

		if(!isset( $_REQUEST['settings-updated'] ))
			$_REQUEST['settings-updated'] = false;
		?>
		
		<div class="wrap">
			<?php screen_icon(); ?><h2>Footer Optionen</h2>
			<?php if(false !== $_REQUEST['settings-updated']): ?>
				<div class="updated fade">
					<p><strong>Deine Einstellungen wurden aktualisiert.</strong></p>
				</div>
			<?php endif; ?>

			<form method="post" action="options.php">
				<?php settings_fields( 'footer_options' ); $options = get_option('footer_theme_options');?>
				
				<table class="form-table">
					<tr valign="top">
						<th scope="top">Social Medias</th>
							<td>
							<?php

							$socials = [
								['facebook', 'Facebook'],
								['youtube', 'Youtube'],
								['email', 'Email'],
								['custom', 'Custom'],
								['calendar', 'Calendar'],
							];

							foreach($socials as $s) {
								?>
								<input id="footer_theme_options[<?php echo $s[0];?>_bool]"
									type="checkbox"
									name="footer_theme_options[<?php echo $s[0];?>_bool]"
									value="<?php echo $s[0];?>"
									<?php checked(isset($options[$s[0] . '_bool']) && $options[$s[0] . '_bool'] === $s[0]) ?>
								><?php echo $s[1];?>:
								<input id="footer_theme_options[<?php echo $s[0];?>_link]"
									type="text"
									name="footer_theme_options[<?php echo $s[0];?>_link]"
									value="<?php isset($options[$s[0] . '_link']) ? esc_attr_e($options[$s[0] . '_link']) : ''?>"
								>
								<br>
								<?php
							}
							?>
							</td>
					</tr>
					<tr>
						<th>Text</th>
							<td>
								<textarea id="footer_theme_options[text]" 
									class="large-text"
									cols="50"
									rows="4"
									name="footer_theme_options[text]"
								><?php if (!!$options) echo esc_textarea($options['text']) ?></textarea>
							</td>
					</tr>
					<tr>
						<th>Adresse</th>
							<td>
								<textarea id="footer_theme_options[address]" 
									class="large-text"
									cols="50"
									rows="4"
									name="footer_theme_options[address]"
								><?php if (!!$options) echo esc_textarea($options['address']) ?></textarea>
							</td>
					</tr>
				</table>

				<!-- submit -->
				<p class="submit"><input type="submit" class="button-primary" value="Speichern"></p>
			</form>
		</div>
	<?php
	}


	//validate input
	function footer_validate_options($input){
		// $input['copyright'] = wp_filter_nohtml_kses($input['copyright']);
		return $input;
	}
?>