<div class="wrap">
	<form method="POST" action="">
		<h2>Easy Reader Settings</h2>
		
		<?php if($message): ?>
			<div class="updated"><p><?php print $message ?></p></div>
		<?php endif; ?>
		
		<h3>Insert Button</h3>
		<p>
			This will automatically insert an Easy Reader button into your posts.
		</p>
		<table class="form-table">
			<tbody>
				<?php
					$filter_settings = get_option('easyreader-filter-settings');
					foreach(array_keys($filter_setting_options) as $key){
						?>
							<tr valign="top">
								<th scope="row">
									<label for="easyreader-setting-<?php print $key ?>"><?php print $filter_setting_titles[$key] ?></label>
								</th>
								<td>
									<select name="setting_<?php print $key?>" id="easyreader-setting-<?php print $key ?>">
										<?php foreach($filter_setting_options[$key] as $i => $value) : ?>
											<option <?php if($filter_settings[$key] == $i) print 'selected'; ?> value="<?php print $i ?>"><?php print $value ?></option>
										<?php endforeach; ?>
									</select>
									<?php if(isset($filter_setting_descriptions[$key])): ?>
										<span class="description"><?php print $filter_setting_descriptions[$key] ?></span>
									<?php endif; ?>
								</td>
							</tr>
						<?php
					}
				?>
				
				
				<tr valign="top">
					<th scope="row">Share and Enjoy buttons</th>
					<td id="easy-reader-share-icons">
						<?php
							$enabled_icons = get_option('easyreader-share-icons');
							foreach($icons as $icon){
								?>
								<label for="easy-reader-button-<?php print $icon ?>"><img src="<?php print WP_PLUGIN_URL ?>/easy-reader/images/share/<?php print $icon ?>.png" /></label>
								<input type="checkbox" name="share_button_<?php print $icon ?>" value="enabled"<?php print in_array($icon, $enabled_icons) ? ' checked' : ''; ?> id="easy-reader-button-<?php print $icon ?>" />
								<?php
							}
						?>
						<div class="description">
							<?php print __('We\'ll insert these under your post in easy reader mode.') ?><br/>
							<?php print __('Icons by ') ?><a href="http://www.blogperfume.com/new-27-circular-social-media-icons-in-3-sizes/">blogperfume</a>.
							
						</div>
					</td>
				</tr>
				
				
			</tbody>
		</table>
		
		<p class="submit">
			<input type="hidden" name="_nonce" value="<?php print wp_create_nonce('easyreader-settings'); ?>" />
			<input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>
	</form>
</div>
