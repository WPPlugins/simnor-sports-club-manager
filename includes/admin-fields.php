<?php 

function sn_sports_club_manager_get_field($args, $i) {
	global $sn_sports_club_manager_options, $post, $heading_i;
	
	/* Get variables */
	if(isset($args['meta'])) { $option_meta = $args['meta']; } else { $option_meta = 0; }
	if(isset($args['type'])) { $field_type = $args['type']; } else { $field_type = "text"; }
	if(isset($args['name'])) { $field_name = $args['name']; } else { $field_name = ""; }
	if(isset($args['label'])) { $field_label = $args['label']; } else { $field_label = ""; }
	if(isset($args['class'])) { $field_class = $args['class']; } else { $field_class = ""; }
	if(isset($args['default'])) { $field_default = $args['default']; } else { $field_default = ""; }
	if(isset($args['description'])) { $field_description = $args['description']; } else { $field_description = ""; }
	if(isset($args['choices'])) { $field_choices = $args['choices']; } else { $field_choices = ""; }
	if(isset($args['posttype'])) { $field_posttype = $args['posttype']; } else { $field_posttype = "post"; }
	if(isset($args['taxonomy'])) { $field_taxonomy = $args['taxonomy']; } else { $field_taxonomy = "category"; }
	if(isset($args['button_label'])) { $field_button_label = $args['button_label']; } else { $field_button_label = "Upload Image"; }
	if(isset($args['validate_as'])) { $field_validate_as = $args['validate_as']; } else { $field_validate_as = ""; }
	if(isset($args['repeater_prefix'])) { $field_repeater_prefix = $args['repeater_prefix']; } else { $field_repeater_prefix = ""; }
	if(isset($args['repeater_options'])) { $field_repeater_options = $args['repeater_options']; } else { $field_repeater_options = ""; }
	if(isset($args['repeater_showon'])) { $field_repeater_showon = $args['repeater_showon']; } else { $field_repeater_showon = ""; }
	if(isset($args['repeater_heading'])) { $field_repeater_heading = $args['repeater_heading']; } else { $field_repeater_heading = ""; }
	
	if($option_meta) {
		$option_type = "meta";
		if(get_post_meta($post->ID, $field_name, true)) { $field_value = get_post_meta($post->ID, $field_name, true); } else { $field_value = ""; }
		$field_id = $field_name;
	} else {
		$option_type = "option";
		if(isset($sn_sports_club_manager_options[$field_name])) { $field_value = $sn_sports_club_manager_options[$field_name]; } else { $field_value = $field_default; }
		$field_id = 'sn_sports_club_manager_options['.$field_name.']';
	}
	
/* HEADING */

	if($field_type == "heading") {
		$heading_i++;
		if($i != 1) { echo '</div><!-- end tabpane -->'; } ?>
					
					<div class="sn-options-tabpane">
						
						<?php if($field_description) { ?>
						<div class="sn-options-field sn-options-field-heading sn-options-field-<?php echo $field_type.' sn-options-field-class_'.$field_class; ?>">
							<h2 id="options_tab_<?php echo $heading_i; ?>"><?php echo $field_label; ?></h2>
							<?php if($field_description) {  ?><p><?php echo $field_description; ?></p><?php } ?>
						</div>
						<?php } ?>
					
<?php

/* SUB HEADING */ 

	} else if($field_type == "subheading") { ?>
											
						<div class="sn-options-field sn-options-field-sub-heading sn-options-field-<?php echo $field_type.' sn-options-field-class_'.$field_class; ?>">
							<h4 id="options_tab_<?php echo $heading_i; ?>"><?php echo $field_label; ?></h4>
							<?php if($field_description) {  ?><p><?php echo $field_description; ?></p><?php } ?>
						</div>
					
<?php 

/* SPACE */ 

	} else if($field_type == "space") { ?>
				
					<div class="sn-options-field sn-options-field-space"></div>

<?php

/* REPEATER */

	} else if($field_type == "repeater") { ?>
	
					<div class="sn-options-field sn-repeater sn-repeater-<?php echo $field_class; ?>">
					
						<div class="sn-repeater-heading">
							<h4><?php echo $field_label; ?></h4>
						</div>
						
						<ul class="sn-repeater-items">
						
						<?php global $repeater_counter;
						while($repeater_counter < 100) { $repeater_counter++; 
							
							$repeater_show = 0;
							foreach($field_repeater_showon as $field_repeater_showon_field) {
								$repeater_field_name = $field_repeater_prefix.'_'.$field_repeater_showon_field.'__'.$repeater_counter;
								if($option_type == "meta") {
									if(get_post_meta($post->ID, $repeater_field_name, true)) { $repeater_show = 1; }
								} else {
									if(isset($sn_sports_club_manager_options[$repeater_field_name])) { $repeater_show = 1; }
								}
							}
						
							if($repeater_show == 1 || $repeater_counter == 0) { 
							
							if($repeater_counter == 0) { ?>
							<li class="repeater_template">
							<?php } else { ?>
							<li id="repeater__<?php echo $repeater_counter; ?>" class="sn-repeater-item">								
							<?php } ?>
							
								<?php 
								$repeater_heading = '';
								if($field_repeater_heading) {
									$repeater_field_name = $field_repeater_prefix.'_'.$field_repeater_heading.'__'.$repeater_counter;
									if($option_type == "meta") {
										if(get_post_meta($post->ID, $repeater_field_name, true)) { $repeater_heading = str_replace('[i]', $repeater_counter, get_post_meta($post->ID, $repeater_field_name, true)); }
									} else {
										if(isset($sn_sports_club_manager_options[$repeater_field_name])) { $repeater_heading = str_replace('[i]', $repeater_counter, $sn_sports_club_manager_options[$repeater_field_name]); }
									}
								} ?>
								
								<div class="sn-repeater-pane-heading">
									<h4><span></span><?php echo $repeater_heading; ?></h4>
																	
									<div class="actions">
										<a href="#" class="sn-repeater-button sn-repeater-toggle-options">Toggle Options</a>
										<a href="#" class="sn-repeater-button sn-repeater-remove">Remove</a>
									</div>
								</div>
								<div class="sn-repeater-pane-content">
								
								<?php foreach($field_repeater_options as $field_repeater_field) { 
									
										/* Get field settings from array */
										$field_repeater_args = array();
										
										if(isset($field_repeater_field['field'])) { $field_repeater_args['type'] = $field_repeater_field['field']; }
										if(isset($field_repeater_field['label'])) { $field_repeater_args['label'] = $field_repeater_field['label']; }
										if(isset($field_repeater_field['class'])) { $field_repeater_args['class'] = $field_repeater_field['class']; }
										if(isset($field_repeater_field['name'])) { $field_repeater_args['name'] = $field_repeater_prefix.'_'.$field_repeater_field['name'].'__'.$repeater_counter; }
										if(isset($field_repeater_field['default'])) { $field_repeater_args['default'] = $field_repeater_field['default']; }
										if(isset($field_repeater_field['description'])) { $field_repeater_args['description'] = $field_repeater_field['description']; }
										if(isset($field_repeater_field['choices'])) { $field_repeater_args['choices'] = $field_repeater_field['choices']; }
										if(isset($field_repeater_field['taxonomy'])) { $field_repeater_args['taxonomy'] = $field_repeater_field['taxonomy']; }
										if(isset($field_repeater_field['posttype'])) { $field_repeater_args['posttype'] = $field_repeater_field['posttype']; }
										if(isset($field_repeater_field['taxonomy'])) { $field_repeater_args['taxonomy'] = $field_repeater_field['taxonomy']; }
										if(isset($field_repeater_field['button_label'])) { $field_repeater_args['button_label'] = $field_repeater_field['button_label']; }
										if(isset($field_repeater_field['validate_as'])) { $field_repeater_args['validate_as'] = $field_repeater_field['validate_as']; }
										if(isset($field_repeater_field['repeater_prefix'])) { $field_repeater_args['repeater_prefix'] = $field_repeater_field['repeater_prefix']; }
										if(isset($field_repeater_field['repeater_options'])) { $field_repeater_args['repeater_options'] = $field_repeater_field['repeater_options']; }
										
										if($option_type == "meta") { $field_repeater_args['meta'] = 1; }
										sn_sports_club_manager_get_field($field_repeater_args, $i);

								} ?>
		
								</div>
							</li>
							
							<?php } } ?>
							
						</ul>
						
						<div class="sn-repeater-footer">
							<input type="button" class="button add-new-repeater-pane" value="<?php echo $field_button_label; ?>" />
						</div>
						
					</div>

<?php 

/* ACTUAL FIELDS */

	} else { ?>
	
					<div class="sn-options-field sn-options-field-input sn-options-field-<?php echo $field_type.' sn-options-field-class_'.$field_class; ?>">
						<div class="sn-options-field-label"><?php echo $field_label; ?></div>
			
			<?php /* TEXT */ if($field_type == "text") { ?>
					
						<input id="<?php echo $field_id; ?>" class="regular-text" type="<?php echo $field_type; ?>" 
						name="<?php echo $field_id; ?>" value="<?php echo esc_attr_e($field_value); ?>" />
			
			<?php /* TEXTAREA */ } else if($field_type == "textarea") { ?>
			
						<textarea id="<?php echo $field_id; ?>" class="large-text" cols="50" rows="10" 
						name="<?php echo $field_id; ?>"><?php echo esc_textarea($field_value); ?></textarea>
			
			<?php /* TEXTAREA */ } else if($field_type == "wpeditor") { ?>
						
						<?php $settings = array(
							'wpautop' => true, 
							'media_buttons' => true, 
							'quicktags' => false, 
							'editor_class' => 'tinyMCE'
						);
						wp_editor($field_value, $field_id, $settings); ?>
									
			<?php /* CHECKBOX */ } else if($field_type == "checkbox") { ?>
						
						<input id="<?php echo $field_id; ?>" type="checkbox" value="1" 
						<?php if(isset($field_value)) { checked( '1', $field_value ); } ?> />
				
						<input style="display:none" type="text" 
						name="<?php echo $field_id; ?>" value="<?php echo esc_attr_e($field_value); ?>" />
			
			<?php /* FILE UPLOAD */ } else if($field_type == "file") { ?>
			
						<input id="<?php echo $field_id; ?>" class="regular-text sn-options-field-upload-text" type="text" 
						name="<?php echo $field_id; ?>" value="<?php esc_attr_e($field_value); ?>" />
						<div class="sn-options-field-upload-buttons">
							<input type="button" 
							value="<?php if($field_button_label) { echo $field_button_label; } else { echo 'Upload Image'; } ?>" 
							class="button sn-options-field-upload-button" rel="<?php if($post) { echo $post->ID; } else { echo '0'; } ?>" />
							<a href="#" class="sn-options-field-remove-upload">Remove</a>
						</div>
						<?php if($field_value) { ?>
						<div class="show-image"><img src="<?php echo $field_value; ?>" alt="" /></div>
						<?php } ?>
			
			<?php /* COLOUR PICKER */ } else if($field_type == "colorpicker") { ?>
					
						<div class="sn-options-field-color-picker-container">
							<input id="<?php echo $field_id; ?>" class="regular-text sn-options-field-color-picker" type="text" 
							name="<?php echo $field_id; ?>" value="<?php echo esc_attr_e($field_value); ?>" />
					    </div>
			
			<?php /* DATE PICKER */ } else if($field_type == "datepicker") { 
				
				$date_to_use = '';
				if($field_value) {
					$date_array = str_split($field_value);
					$date_to_use = $date_array[0].$date_array[1].$date_array[2].$date_array[3].'-'.$date_array[4].$date_array[5].'-'.$date_array[6].$date_array[7];
				}
			?>
					
						<div class="sn-options-field-date-picker-container">
							<input id="<?php echo $field_id; ?>" class="regular-text sn-options-field-date-picker" type="text" 
							name="<?php echo $field_id; ?>" value="<?php echo esc_attr_e($date_to_use); ?>" />
					    </div>
			
			<?php /* RADIO BUTTONS */ } else if($field_type == "radio") { ?>
				
						<fieldset>
							<legend class="screen-reader-text"><span><?php echo $field_label; ?></span></legend>
							<?php
								if(!isset($checked)) { $checked = ''; }
								foreach($field_choices as $option) {
									if('' != $field_value) {
										if($field_value == $option['value']) {
											$checked = "checked=\"checked\"";
										} else {
											$checked = '';
										}
									}
									?>
									<label class="sn-options-field-radio-label">
										<input type="radio" value="<?php esc_attr_e( $option['value'] ); ?>" 
										name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?>
									</label><br />
									<?php
								}
							?>
						</fieldset>
							
			<?php /* ICON SELECT */ } else if($field_type == "icon_select") { ?>
					
						<div class="sn-icon-select">
							<a href="#" class="button sn-icon-select-toggle"><?php _e('Select Icon', 'snplugin'); ?></a>
							<i class="current-icon admin-<?php echo esc_attr_e($field_value); ?>"></i>
							<div class="sn-icon-select-icons">
								<ul>
								<?php foreach($field_choices as $option) { ?>
									<li<?php if($field_value == $option['value']) { ?> class="active"<?php } ?>>
										<a href="#"><i class="admin-<?php echo $option['value']; ?>"></i></a>
									</li>
								<?php } ?>
								</ul>
							</div>
							<input id="<?php echo $field_id; ?>" class="regular-text" type="text" 
							name="<?php echo $field_id; ?>" value="<?php echo esc_attr_e($field_value); ?>" />
						</div>
			
			<?php /* SELECT */ } else if($field_type == "select") { ?>
				
						<select name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>">
							<?php foreach($field_choices as $option) {
								$label = $option['label']; ?>
									<option <?php if($field_value == $option['value']) { ?>selected='selected'<?php } ?> 
									value='<?php echo esc_attr( $option['value'] ); ?>'><?php echo $label; ?></option>
							<?php } ?>
						</select>
					
			<?php /* SELECT TAXONOMY */ } else if($field_type == "select_taxonomy") { ?>
				
						<select class="chosen" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>">
							<option value=""><?php _e('Select...', 'snplugin'); ?></option>
							<?php $list_terms = get_terms($field_taxonomy); 
								foreach($list_terms as $list_term) { ?>
									<option <?php if($field_value == $list_term->term_id) { ?>selected='selected'<?php } ?> 
									value='<?php echo esc_attr( $list_term->term_id ); ?>'><?php echo $list_term->name; ?></option>
							<?php } ?>
						</select>
			
			<?php /* SELECT POST */ } else if($field_type == "select_post") { ?>
				
						<select class="chosen" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>">
							<option value=""><?php _e('Select...', 'snplugin'); ?></option>
							<?php $list_posts = get_posts('post_type='.$field_posttype.'&numberposts=-1&orderby=title&order=ASC'); 
								foreach($list_posts as $list_post) { ?>
									<option <?php if($field_value == $list_post->ID) { ?>selected='selected'<?php } ?> 
									value='<?php echo esc_attr( $list_post->ID ); ?>'><?php echo $list_post->post_title; ?></option>
							<?php } ?>
						</select>
			
			<?php } ?>
				
						<label class="sn-options-field-description sn-options-field-description-<?php echo $field_type.'_description '; ?>" for="<?php echo $field_id; ?>"><?php echo $field_description; ?></label>
						<div class="sn-options-field-clearfix"></div>
						
					</div><!-- end field container -->
				
	<?php } /* End field checks */
	
}