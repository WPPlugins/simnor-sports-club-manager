<?php
/* Include admin files */
	global $sn_sports_club_manager_path;
	include($sn_sports_club_manager_path.'/includes/admin-options.php');

	
/* Theme Options */
	
	/* Initialise the options */
	function sn_sports_club_manager_init() { register_setting( 'sn_sports_club_manager_options_group', 'sn_sports_club_manager_options', '' ); }
	add_action( 'admin_init', 'sn_sports_club_manager_init' );
	
	/* Add options page to Appearance Menu */
	function sn_sports_club_manager_add_page() {
		global $sn_sports_club_manager_name;
		add_options_page( $sn_sports_club_manager_name.__( ' Options', 'snplugin' ), $sn_sports_club_manager_name.__( ' Options', 'snplugin' ), 'manage_options', 'sn_sports_club_manager_options', 'sn_sports_club_manager_do_page' );
	}
	global $sports_club_manager_options_fields;
	add_action( 'admin_menu', 'sn_sports_club_manager_add_page' );
	
	/* Create the options page */
	function sn_sports_club_manager_do_page() {
		global $sports_club_manager_options_fields;
		if(!isset($_REQUEST['settings-updated'])) { $_REQUEST['settings-updated'] = false; }
		
		/* Include the fields file */
		global $sn_sports_club_manager_path;
		require_once ($sn_sports_club_manager_path.'/includes/admin-fields.php');
		
		/* Enqueue some scripts */
		$scripts_to_include = array('media_upload', 'chosen', 'sortable', 'datepicker', 'colourpicker', 'icon_fonts', 'admin');
		sn_sports_club_manager_include_scripts($scripts_to_include);
		
		/* Make the options form */
		?>
		<div class="wrap sn-plugin-options-wrap">
		
			<?php
			/* Theme Options Tabs and Headings */
			screen_icon();
			global $sn_sports_club_manager_name;
			echo '<h2 class="nav-tab-wrapper">'; echo $sn_sports_club_manager_name; _e(' Options', 'snplugin'); echo '&nbsp;';
			
				/* Loop through options array to get the heading tabs */
				$heading_i = 0; $i = 0; foreach($sports_club_manager_options_fields as $options_field) {
					if($options_field['field'] == "heading") { $i++;
						if($i == 1) { $nav_tab_classes = "nav-tab nav-tab-active"; } else { $nav_tab_classes = "nav-tab"; }
						echo '<a href="#options_tab_'.$i.'" class="' . $nav_tab_classes . '">' . $options_field['label'] . "</a>";
				} } 
			
			echo '</h2>'; ?>
	
			<?php
			/* Updated message */
				if ( ! isset( $_REQUEST['settings-updated'] ) )
					$_REQUEST['settings-updated'] = false;
			 ?>
				
			<form method="post" action="options.php">
				<?php settings_fields('sn_sports_club_manager_options_group');
				$options = get_option('sn_sports_club_manager_options'); ?>
				
				<?php 
				/* Loop through the options array */
				$i = 0; foreach($sports_club_manager_options_fields as $options_field) { $i++;
				
				global $repeater_counter; $repeater_counter = -1;
				
					/* Get field settings from array */
					$field_args = array();
					
					if(isset($options_field['field'])) { $field_args['type'] = $options_field['field']; }
					if(isset($options_field['label'])) { $field_args['label'] = $options_field['label']; }
					if(isset($options_field['class'])) { $field_args['class'] = $options_field['class']; }
					if(isset($options_field['name'])) { $field_args['name'] = $options_field['name']; }
					if(isset($options_field['default'])) { $field_args['default'] = $options_field['default']; }
					if(isset($options_field['description'])) { $field_args['description'] = $options_field['description']; }
					if(isset($options_field['choices'])) { $field_args['choices'] = $options_field['choices']; }
					if(isset($options_field['taxonomy'])) { $field_args['taxonomy'] = $options_field['taxonomy']; }
					if(isset($options_field['posttype'])) { $field_args['posttype'] = $options_field['posttype']; }
					if(isset($options_field['taxonomy'])) { $field_args['taxonomy'] = $options_field['taxonomy']; }
					if(isset($options_field['button_label'])) { $field_args['button_label'] = $options_field['button_label']; }
					if(isset($options_field['validate_as'])) { $field_args['validate_as'] = $options_field['validate_as']; }
					if(isset($options_field['repeater_prefix'])) { $field_args['repeater_prefix'] = $options_field['repeater_prefix']; }
					if(isset($options_field['repeater_options'])) { $field_args['repeater_options'] = $options_field['repeater_options']; }
					if(isset($options_field['repeater_showon'])) { $field_args['repeater_showon'] = $options_field['repeater_showon']; }
					if(isset($options_field['repeater_heading'])) { $field_args['repeater_heading'] = $options_field['repeater_heading']; }
									
					/* Get the field (includes/admin/admin-fields.php) */
					sn_sports_club_manager_get_field($field_args, $i); 
				
				} ?>
				
				</div><!-- end tabpane -->
			
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Options', 'snplugin'); ?>" />
				</p>
			</form>
			</div>
				
		
		<?php
	}