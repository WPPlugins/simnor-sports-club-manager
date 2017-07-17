<?php 

add_action('init', 'fixtures_register');
 
function fixtures_register() {
 
	$labels = array(
		'name' => _x('Fixtures', 'post type general name', 'snplugin'),
		'singular_name' => _x('Fixture', 'post type singular name', 'snplugin'),
		'add_new' => _x('Add New', 'fixtures', 'snplugin'),
		'add_new_item' => __('Add New Fixture', 'snplugin'),
		'edit_item' => __('Edit Fixture', 'snplugin'),
		'new_item' => __('New Fixture', 'snplugin'),
		'view_item' => __('View Fixture', 'snplugin'),
		'search_items' => __('Search Fixtures', 'snplugin'),
		'not_found' =>  __('Nothing found', 'snplugin'),
		'not_found_in_trash' => __('Nothing found in Trash', 'snplugin'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'has_archive' => false,
		'menu_position' => null,
		'supports' => array('title','editor')
	  ); 
 
	register_post_type( 'fixture' , $args );
		
	$fixture_taxonomy_labels = array(
		'name' => _x('Seasons', 'post type general name', 'snplugin'),
		'singular_name' => _x('Season', 'post type singular name', 'snplugin'),
		'add_new' => _x('Add New', 'fixtures', 'snplugin'),
		'add_new_item' => __('Add New Season', 'snplugin'),
		'edit_item' => __('Edit Season', 'snplugin'),
		'new_item' => __('New Season', 'snplugin'),
		'view_item' => __('View Season', 'snplugin'),
		'search_items' => __('Search Seasons', 'snplugin'),
		'not_found' =>  __('Nothing found', 'snplugin'),
		'not_found_in_trash' => __('Nothing found in Trash', 'snplugin'),
		'parent_item_colon' => ''
	);
		
	register_taxonomy('season','fixture',array(
		'hierarchical' => true,
		'labels' => $fixture_taxonomy_labels,
		'show_ui' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'season' ),
	));
	
}

/* Fixture Date Column */

add_filter('manage_fixture_posts_columns', 'custom_manage_fixture_posts_columns', 10);
add_action('manage_fixture_posts_custom_column', 'custom_manage_fixture_posts_custom_column', 10, 2);
function custom_manage_fixture_posts_columns($defaults) {
	$defaults['fixture_date'] = 'Fixture Date';
	return $defaults;
}
function custom_manage_fixture_posts_custom_column($column_name, $post_ID) {
	if($column_name == 'fixture_date') {
		$fixture_date = get_post_meta($post_ID, 'fixture_date', true);
		if($fixture_date) {
			$fixture_date = str_split($fixture_date);
			$fixture_date = date('jS M Y', strtotime($fixture_date[0].$fixture_date[1].$fixture_date[2].$fixture_date[3].'-'.$fixture_date[4].$fixture_date[5].'-'.$fixture_date[6].$fixture_date[7]));
		}
		echo $fixture_date;
	}
}


/* Meta Fields */

/* Select and Radios Arrays */ 
$select_options = array(
	'yes' => array(
		'value' => 'yes',
		'label' => __( 'Yes', 'snplugin' )
	),
	'no' => array(
		'value' => 'no',
		'label' => __( 'No', 'snplugin' )
	),
	'maybe' => array(
		'value' => 'maybe',
		'label' => __( 'Maybe', 'snplugin' )
	)
); 

/* Options Fields Array */

$post_type_fixture_meta_fields = array();

$post_type_fixture_meta_fields[] = array(	"label" =>				__( "Fixture Details", 'snplugin' ), 
									"description" =>		"",
									"field" => 				"heading" );

$post_type_fixture_meta_fields[] = array(	"label" => 				__( "Date", 'snplugin' ), 
									"name" =>					"fixture_date", 
									"default" =>			"",
									"description" => 		"Must be in format YYYY-MM-DD, this is used for the sorting of the fixtures.",
									"field" => 				"datepicker",
									"validate_as" => 		"date" );

$post_type_fixture_meta_fields[] = array(	"label" => 				__( "Time", 'snplugin' ), 
									"name" =>					"fixture_time", 
									"default" =>			"",
									"description" => 		"Must be in 24 hour format, eg 18:00 for a 6PM game, this is used for the sorting of the fixtures",
									"field" => 				"text",
									"validate_as" => 		"text" );

$post_type_fixture_meta_fields[] = array(	"label" => 				__( "Date as appears on the site", 'snplugin' ), 
									"name" =>					"fixture_date_nicename", 
									"default" =>			"",
									"description" => 		"This is how you want the date to show on your site, eg. 16th March 2014 at 7:00PM",
									"field" => 				"text",
									"validate_as" => 		"text" );

$post_type_fixture_meta_fields[] = array(	"label" => 				__( "Location", 'snplugin' ), 
									"name" =>					"fixture_location", 
									"default" =>			"",
									"description" => 		"Where the fixture will be played, Eg, Evergreen Stadium",
									"field" => 				"text",
									"validate_as" => 		"text" );

$post_type_fixture_meta_fields[] = array(	"label" => 				__( "Fixture Type", 'snplugin' ), 
									"name" =>					"fixture_game_type", 
									"default" =>			"",
									"description" => 		"Eg, League Game, Playoff etc. Appears next to location on fixture single post.",
									"field" => 				"text",
									"validate_as" => 		"text" );

$post_type_fixture_meta_fields[] = array(	"label" =>				__( "Home Team", 'snplugin' ), 
									"description" =>		"",
									"field" => 				"heading" );

$post_type_fixture_meta_fields[] = array(	"label" => 				__( "Team Logo", 'snplugin' ), 
									"name" =>					"fixture_home_team_logo", 
									"default" =>			"",
									"description" => 		"",
									"field" => 				"file",
									"validate_as" => 		"" );

$post_type_fixture_meta_fields[] = array(	"label" => 				__( "Team Name", 'snplugin' ), 
									"name" =>					"fixture_home_team_name", 
									"default" =>			"",
									"description" => 		"",
									"field" => 				"text",
									"validate_as" => 		"" );

/*$post_type_fixture_meta_fields[] = array(	"label" => 				__( "or pick your team", 'snplugin' ), 
									"name" =>					"fixture_home_team_id", 
									"default" =>			"",
									"description" => 		"",
									"taxonomy" => 			"team",
									"field" => 				"select_taxonomy",
									"validate_as" => 		"" );*/
							
$post_type_fixture_meta_fields[] = array(	"label" =>				__( "Score", 'snplugin' ), 
									"name" => 				"fixture_home_score", 
									"default" =>			"",
									"description" =>		"",
									"field" => 				"text" );

$post_type_fixture_meta_fields[] = array(	"label" =>				__( "Away Team", 'snplugin' ), 
									"description" =>		"",
									"field" => 				"heading" );

$post_type_fixture_meta_fields[] = array(	"label" => 				__( "Away Logo", 'snplugin' ), 
									"name" =>					"fixture_home_away_logo", 
									"default" =>			"",
									"description" => 		"",
									"field" => 				"file",
									"validate_as" => 		"" );

$post_type_fixture_meta_fields[] = array(	"label" => 				__( "Team Name", 'snplugin' ), 
									"name" =>					"fixture_away_team_name", 
									"default" =>			"",
									"description" => 		"",
									"field" => 				"text",
									"validate_as" => 		"" );

/*$post_type_fixture_meta_fields[] = array(	"label" => 				__( "or pick your team", 'snplugin' ), 
									"name" =>					"fixture_away_team_id", 
									"default" =>			"",
									"description" => 		"",
									"taxonomy" => 			"team",
									"field" => 				"select_taxonomy",
									"validate_as" => 		"" );*/
							
$post_type_fixture_meta_fields[] = array(	"label" =>				__( "Score", 'snplugin' ), 
									"name" => 				"fixture_away_score", 
									"default" =>			"",
									"description" =>		"",
									"field" => 				"text" );


/* Add meta form */
function sn_fixture_meta_form() {
	global $post_type_fixture_meta_fields, $sn_sports_club_manager_path;
	
	/* Include the fields file */
	require_once ($sn_sports_club_manager_path.'/includes/admin-fields.php');
	
	/* Enqueue some scripts */
	$scripts_to_include = array('media_upload', 'chosen', 'sortable', 'datepicker', 'colourpicker', 'icon_fonts', 'admin');
	sn_sports_club_manager_include_scripts($scripts_to_include);
	
	/* Make the options form */
	?>
	<div class="wrap sn-post-options-wrap">
	
		<?php
		/* Theme Options Tabs and Headings */
		echo '<h2 class="nav-tab-wrapper">';
		
			/* Loop through options array to get the heading tabs */
			$heading_i = 0; $i = 0; foreach($post_type_fixture_meta_fields as $options_field) {
				if($options_field['field'] == "heading") { $i++;
					if($i == 1) { $nav_tab_classes = "nav-tab nav-tab-active"; } else { $nav_tab_classes = "nav-tab"; }
					echo '<a href="#options_tab_'.$i.'" class="' . $nav_tab_classes . '">' . $options_field['label'] . "</a>";
			} } 
		
		echo '</h2>';
		
		/* Loop through the options array */
		$i = 0; foreach($post_type_fixture_meta_fields as $options_field) { $i++;
		
		global $repeater_counter; $repeater_counter = -1;
		
			/* Get field settings from array */
			$field_args = array();
			
			if(isset($options_field['field'])) { $field_args['type'] = $options_field['field']; }
			if(isset($options_field['label'])) { $field_args['label'] = $options_field['label']; }
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
			$field_args['meta'] = 1;
			sn_sports_club_manager_get_field($field_args, $i); 
		
		} ?>
		
	</div>
	
	<?php
}


/* Create meta box */
function sn_create_fixture_meta_box() {
	if(function_exists('add_meta_box')) {
		add_meta_box( 'sn-fixture-meta', 'Fixture Options', 'sn_fixture_meta_form', 'fixture', 'normal', 'high' );
	}
}
add_action('admin_menu', 'sn_create_fixture_meta_box');


/* Save meta fields */
function sn_fixture_save_meta_fields( $post_id ) {

	if(isset($_POST['post_type'])) { $saved_post_type = $_POST['post_type']; } else { $saved_post_type = ""; }
	
	if($saved_post_type == "fixture") {
		global $post_type_fixture_meta_fields;
		if(isset($post_type_fixture_meta_fields)) { $post_meta_fields_to_save = $post_type_fixture_meta_fields; }
	}
			
	if(isset($post_meta_fields_to_save)) {
	
	    $is_autosave = wp_is_post_autosave( $post_id );
	    $is_revision = wp_is_post_revision( $post_id );
	 
	    if($is_autosave || $is_revision) {
	        return;
	    }
	    
	    foreach($post_meta_fields_to_save as $options_field) {
	    
	    	if($options_field['field'] == "repeater") {
		    	$repeater_fields = $options_field['repeater_options'];
		    	$repeater_prefix = $options_field['repeater_prefix'];
		    	
		    	foreach($repeater_fields as $repeater_field) {
			    	$i = 0; while($i < 100) { $i++;
			    		$repeater_field_name = $repeater_prefix.'_'.$repeater_field['name'].'__'.$i;
				    	if(isset($_POST[$repeater_field_name])) {
					    	$validate_as = "";
					    	if(isset($repeater_field['validate_as'])) { $validate_as = $repeater_field['validate_as']; }
					    	if(isset($repeater_field['name'])) {
						    	if($repeater_field['field'] == "checkbox") {
						    		if($_POST[$repeater_field_name] == 1) {
								    	update_post_meta($post_id, $repeater_field_name, $_POST[$repeater_field_name]);	
						    		} else {
								    	delete_post_meta($post_id, $repeater_field_name);
						    		}
						    	} else if($validate_as == "text") {
							    	update_post_meta($post_id, $repeater_field_name, sanitize_text_field($_POST[$repeater_field_name]));
						    	} else if($validate_as == "textarea") {
							    	update_post_meta($post_id, $repeater_field_name, esc_textarea($_POST[$repeater_field_name]));
						    	} else {
							    	update_post_meta($post_id, $repeater_field_name, $_POST[$repeater_field_name]);			    	
						    	}
						    }
				    	} else {
					    	delete_post_meta($post_id, $repeater_field_name);
				    	}
			    	}
		    	}
		    	
		    	
	    	}
	    
		    if(isset($options_field['name'])) {
				$field_id = $options_field['name'];
			}
		
			if(isset($field_id) && isset($_POST[$field_id])) {
			
		    	$validate_as = "";
		    	if(isset($options_field['validate_as'])) { $validate_as = $options_field['validate_as']; }
		    	if(isset($options_field['name'])) {
			    	if($options_field['field'] == "checkbox") {
			    		if($_POST[$options_field['name']] == 1) {
					    	update_post_meta($post_id, $options_field['name'], $_POST[$options_field['name']]);	
			    		} else {
					    	delete_post_meta($post_id, $options_field['name']);
			    		}
			    	} else if($validate_as == "text") {
				    	update_post_meta($post_id, $options_field['name'], sanitize_text_field($_POST[$options_field['name']]));
			    	} else if($validate_as == "date") {
				    	update_post_meta($post_id, $options_field['name'], sanitize_text_field(str_replace('-', '', $_POST[$options_field['name']])));
			    	} else if($validate_as == "textarea") {
				    	update_post_meta($post_id, $options_field['name'], esc_textarea($_POST[$options_field['name']]));
			    	} else {
				    	update_post_meta($post_id, $options_field['name'], $_POST[$options_field['name']]);			    	
			    	}
		    	}
		    	
		    }
		}
    }    
 
}
add_action( 'save_post', 'sn_fixture_save_meta_fields' );