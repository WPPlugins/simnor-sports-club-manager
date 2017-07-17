<?php 

add_action('init', 'players_register');
 
function players_register() {
 
	$labels = array(
		'name' => _x('Players', 'post type general name', 'snplugin'),
		'singular_name' => _x('Player', 'post type singular name', 'snplugin'),
		'add_new' => _x('Add New', 'players', 'snplugin'),
		'add_new_item' => __('Add New Player', 'snplugin'),
		'edit_item' => __('Edit Player', 'snplugin'),
		'new_item' => __('New Player', 'snplugin'),
		'view_item' => __('View Player', 'snplugin'),
		'search_items' => __('Search Players', 'snplugin'),
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
		'supports' => array('title','editor', 'thumbnail')
	  ); 
 
	register_post_type( 'player' , $args );
		
	$player_taxonomy_labels = array(
		'name' => _x('Teams', 'post type general name', 'snplugin'),
		'singular_name' => _x('Team', 'post type singular name', 'snplugin'),
		'add_new' => _x('Add New', 'players', 'snplugin'),
		'add_new_item' => __('Add New Team', 'snplugin'),
		'edit_item' => __('Edit Team', 'snplugin'),
		'new_item' => __('New Team', 'snplugin'),
		'view_item' => __('View Team', 'snplugin'),
		'search_items' => __('Search Teams', 'snplugin'),
		'not_found' =>  __('Nothing found', 'snplugin'),
		'not_found_in_trash' => __('Nothing found in Trash', 'snplugin'),
		'parent_item_colon' => ''
	);
		
	register_taxonomy('team',array('player', 'fixture'),array(
		'hierarchical' => true,
		'labels' => $player_taxonomy_labels,
		'show_ui' => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'team' ),
	));
	
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

$post_type_player_meta_fields = array();

$post_type_player_meta_fields[] = array(	"label" => __( "Position", 'snplugin' ),
											'default' => "",
											"name" => 'player_position',
											"field" => "text" );

$player_facts_prefix = "player_facts";
$player_facts_options = array();
$player_facts_options[] = array(	"label" => __( "Label", 'snplugin' ),
									"name" => 'label',
									"description" => 'Eg, Age',
									"field" => "text" );
									
$player_facts_options[] = array(	"label" => __( "Value", 'snplugin' ),
									"name" => 'value',
									"description" => 'Eg, 22',
									"field" => "text" );

$post_type_player_meta_fields[] = array(	"label" => __( "Player Facts", 'snplugin' ),
									'default' => "",
									'repeater_options' => $player_facts_options,
									'repeater_prefix' => $player_facts_prefix,
									'repeater_showon' => array('label'),
									'repeater_heading' => 'label',
									'button_label' => "Add new fact",
									"name" => $player_facts_prefix.'_order',
									"field" => "repeater" );

/* Add meta form */
function sn_player_meta_form() {
	global $post_type_player_meta_fields, $sn_sports_club_manager_path;
	
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
			$heading_i = 0; $i = 0; foreach($post_type_player_meta_fields as $options_field) {
				if($options_field['field'] == "heading") { $i++;
					if($i == 1) { $nav_tab_classes = "nav-tab nav-tab-active"; } else { $nav_tab_classes = "nav-tab"; }
					echo '<a href="#options_tab_'.$i.'" class="' . $nav_tab_classes . '">' . $options_field['label'] . "</a>";
			} } 
		
		echo '</h2>';
		
		/* Loop through the options array */
		$i = 0; foreach($post_type_player_meta_fields as $options_field) { $i++;
		
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
function sn_create_player_meta_box() {
	if(function_exists('add_meta_box')) {
		add_meta_box( 'sn-player-meta', 'Player Options', 'sn_player_meta_form', 'player', 'normal', 'high' );
	}
}
add_action('admin_menu', 'sn_create_player_meta_box');


/* Save meta fields */
function sn_player_save_meta_fields( $post_id ) {

	if(isset($_POST['post_type'])) { $saved_post_type = $_POST['post_type']; } else { $saved_post_type = ""; }
	
	if($saved_post_type == "player") {
		global $post_type_player_meta_fields;
		if(isset($post_type_player_meta_fields)) { $post_meta_fields_to_save = $post_type_player_meta_fields; }
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
add_action( 'save_post', 'sn_player_save_meta_fields' );