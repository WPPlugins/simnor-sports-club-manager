<?php
/*
Plugin Name: Simnor Sports Club Manager
Plugin URI: http://simnorlabs.com/plugins/simnor-sports-club-manager/
Description: Manage your sports club easily, manage your teams, fixures and results.
Version: 1.6
Author: Simon North
Author URI: http://simnorlabs.com

/* Variables */
$sn_sports_club_manager_options = get_option('sn_sports_club_manager_options');
$sn_sports_club_manager_path = dirname(__FILE__);
$sn_sports_club_manager_main_file = dirname(__FILE__).'/simnor-sports-club-manager.php';
$sn_sports_club_manager_directory = plugin_dir_url($sn_sports_club_manager_main_file);
$sn_sports_club_manager_name = "Simnor Sports Club Manager";

/* Includes */
function sn_sports_club_manager_include_scripts($scripts_to_include) {
	global $sn_sports_club_manager_directory;
	
	foreach($scripts_to_include as $script_to_include) {
		
		if($script_to_include == "media_upload") {
			wp_enqueue_media();
			wp_enqueue_script( 'custom-header' );
		} else if($script_to_include == "chosen") {
			wp_enqueue_style('chosen_css', $sn_sports_club_manager_directory.'/includes/scripts/chosen/chosen.css');
		    wp_register_script('chosen_js', $sn_sports_club_manager_directory.'/includes/scripts/chosen/chosen.jquery.min.js');
			wp_enqueue_script('chosen_js');
		} else if($script_to_include == "sortable") {
			wp_enqueue_script('jquery-ui-sortable');
		} else if($script_to_include == "datepicker") {
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('datepicker-style', $sn_sports_club_manager_directory.'/includes/scripts/datepicker/jquery-ui-1.8.23.custom.css');
		} else if($script_to_include == "colourpicker") {
			wp_enqueue_style('wp-color-picker');          
		    wp_enqueue_script('wp-color-picker');     
		} else if($script_to_include == "icon_fonts") {
			wp_enqueue_style('admin-fontawesome', $sn_sports_club_manager_directory.'/includes/scripts/fonts/fontAwesome/css/font-awesome.css');
			wp_enqueue_style('admin-fontello', $sn_sports_club_manager_directory.'/includes/scripts/fonts/fontello/css/fontello.css');		
		} else if($script_to_include == "admin") {
			wp_enqueue_style('sn_admin_css', $sn_sports_club_manager_directory.'/includes/scripts/admin.css');
		    wp_register_script('sn_admin_js', $sn_sports_club_manager_directory.'/includes/scripts/admin.js');
			wp_enqueue_script('sn_admin_js');
		}
		
	}

}
include($sn_sports_club_manager_path.'/includes/admin-page.php');
include($sn_sports_club_manager_path.'/includes/post-types/players.php');
include($sn_sports_club_manager_path.'/includes/post-types/fixtures.php');

/* Enqueue Frontend Scripts */
if(!is_admin()) {
	function sn_sports_club_manager_enqueue_scripts() {
		global $sn_sports_club_manager_directory;
		
		wp_enqueue_style('sn-scm-stylesheet', $sn_sports_club_manager_directory.'/style.css');
		wp_register_script('sn-scm-js', $sn_sports_club_manager_directory.'/script.js', 'jquery', '', 1);
		wp_enqueue_script('jquery');
		wp_enqueue_script('sn-scm-js');
		
	}
	add_action('wp_enqueue_scripts', 'sn_sports_club_manager_enqueue_scripts');
}


/* Get date format */
function sn_sports_club_manager_date_format() {
	$date_format = 'jS M Y';
	global $sn_sports_club_manager_options;
	if(isset($sn_sports_club_manager_options['date_format'])) {
		if($sn_sports_club_manager_options['date_format']) { 
			$date_format = $sn_sports_club_manager_options['date_format'];
		}
	}
	if(isset($sn_sports_club_manager_options['specific_date_format'])) {
		if($sn_sports_club_manager_options['specific_date_format']) {
			$date_format = $sn_sports_club_manager_options['specific_date_format'];
		}
	}
	return $date_format;
}


/* Is vs. enabled */
function sn_sports_club_manager_is_vs() {
	global $sn_sports_club_manager_options;
	if(isset($sn_sports_club_manager_options['hide_vs'])) {
		if($sn_sports_club_manager_options['hide_vs']) { 
			return false;
		} else { return true; }
	} else { return true; }
}


/* Player Thumb */
if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
	add_image_size( 'player_thumb', 300, 350, true );
}


/* Fixtures Shortcode */
function sn_fixtures_table($atts, $content = null) {
	extract(shortcode_atts(array(
		'season' => '', 
		'number' => '', 
		'team' => '', 
		'what_to_show' => ''
	), $atts));
	global $sn_sports_club_manager_options;

	if($number) { } else { $number = -1; }
	$odd = 1;
	
	$code = '';
	$code .= '<div class="sn-scm-fixtures-table">';
	
	$terms = get_terms("team");
	$count = count($terms);
	if($count > 1 && !$team) {
	/* Multiple Teams */
	
		$code .= '<div class="sn-scm-fixtures-table-filter"><div class="sn-scm-select"><select>';
		foreach ( $terms as $term ) {
			$code .= '<option value="'.$term->term_id.'">'.$term->name.'</option>';
		}
		$code .= '</select></div></div>';
		
	foreach($terms as $team) {
			
		$code .= '<div class="sn-scm-fixture-table" id="team-'.$team->term_id.'">';
		if($season) {
			$season_term = get_term_by('name', $season, 'season');
			if(isset($season_term->term_id)) {
				$season_id = $season_term->term_id;
				$args = array(
					'post_type' => 'fixture',
					'posts_per_page' => -1,
					'order_by' => 'meta_value',
					'meta_key' => 'fixture_date',
					'order' => 'ASC',
					'tax_query' => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'season',
							'field' => 'id',
							'terms' => $season_id
						), 
						array(
							'taxonomy' => 'team',
							'field' => 'id',
							'terms' => $team->term_id
						)
					)
				);
			} else {
				$code .= 'ERROR: The season specified cannot be found';
			}
		} else {
			$args = array(
				'post_type' => 'fixture',
				'posts_per_page' => -1,
				'order_by' => 'meta_value',
				'meta_key' => 'fixture_date',
				'order' => 'ASC',
				'tax_query' => array(
					array(
						'taxonomy' => 'team',
						'field' => 'id',
						'terms' => $team->term_id
					)
				)
			);
		}
		
		$fixture_posts = array();
		
		$i = 0;
		if(isset($args)) {
		query_posts($args);
		while(have_posts()) : the_post();
		global $post;
			
			$fixture_time = get_post_meta($post->ID, 'fixture_time', true);
			if($fixture_time == "") { $fixture_time = '0:00'; }
			$fixture_seconds = date("U", strtotime(get_post_meta($post->ID, 'fixture_date', true).' '.$fixture_time));
			$fixture_posts[$fixture_seconds] = array($fixture_seconds, $post->ID);
			
		endwhile; 
		wp_reset_query();
		
		ksort($fixture_posts);
		
		if($what_to_show == "results" && $number > 0) {
			$fixture_posts = array_reverse($fixture_posts);
		}
		
		foreach($fixture_posts as $fixture_post) {
		query_posts('post_type=fixture&p='.$fixture_post[1]);
		while(have_posts()) : the_post();
		
			$show_fixture = 1;
			$fixture_time = get_post_meta($post->ID, 'fixture_time', true);
			if($fixture_time == "") { $fixture_time = '0:00'; }
			$fixture_seconds = date("U", strtotime(get_post_meta($post->ID, 'fixture_date', true).' '.$fixture_time));
			
			if($what_to_show == "results") {
				if($fixture_seconds <= date('U')) { } else { $show_fixture = 0; }
			} else if($what_to_show == "fixtures") {
				if($fixture_seconds >= date('U')) { } else { $show_fixture = 0; }
			}
			
			if($number > 0) {
				if($i >= $number) {
					$show_fixture = 0;
				}
			}
			
			if($show_fixture == 1) { $i++;
				
			$fixture_date = get_post_meta($post->ID, 'fixture_date', true);
			if($fixture_date) {
				$fixture_date = str_split($fixture_date);
				$fixture_date = date(sn_sports_club_manager_date_format(), strtotime($fixture_date[0].$fixture_date[1].$fixture_date[2].$fixture_date[3].'-'.$fixture_date[4].$fixture_date[5].'-'.$fixture_date[6].$fixture_date[7]));
			}			
			if(get_post_meta($post->ID, 'fixture_home_score', true)) {
				$home_score = get_post_meta($post->ID, 'fixture_home_score', true);
			} else { $home_score = 0; }
			if(get_post_meta($post->ID, 'fixture_away_score', true)) {
				$away_score = get_post_meta($post->ID, 'fixture_away_score', true);
			} else { $away_score = 0; }
			
			if($odd == 1) {
				$odd = 0;
				$row_class = '';
			} else {
				$odd = 1;
				$row_class = ' sn-scm-fixture-table-row-odd';
			}
			
			if(sn_sports_club_manager_is_vs()) {
				$code .= '<div class="sn-scm-fixture-table-row'.$row_class.'">';
			} else {
				$code .= '<div class="sn-scm-fixture-table-row'.$row_class.' sn-scm-fixture-table-row-no-vs">';
			}
			
			$home_logo = '';
			if(get_post_meta($post->ID, 'fixture_home_team_logo', true)) {
				$home_logo = '<img src="'.get_post_meta($post->ID, 'fixture_home_team_logo', true).'" alt="" />';
			}
			$away_logo = '';
			if(get_post_meta($post->ID, 'fixture_home_away_logo', true)) {
				$away_logo = '<img src="'.get_post_meta($post->ID, 'fixture_home_away_logo', true).'" alt="" />';
			}
			
			$code .= '<div class="cell-date"><span>'.$fixture_date.'</span></div>
				<div class="cell-home">'.$home_logo.'<span>'.get_post_meta($post->ID, 'fixture_home_team_name', true).'</span></div>
				<div class="cell-home-score"><span>'.$home_score.'</span></div>';
				
			if(sn_sports_club_manager_is_vs()) {
				$code .= '<div class="cell-away-score"><span>'.$away_score.'</span></div>
				<div class="cell-away"><span>'.get_post_meta($post->ID, 'fixture_away_team_name', true).'</span>'.$away_logo.'</div>';
			}
				
				$code .= '<div class="cell-link"><a href="'.get_permalink($post->ID).'"></a></div>
			</div>';
			
			}
		
		endwhile; wp_reset_query();
		} }
		$code .= '</div>';
			
	}
	
	} else if($team) {
	/* Specific  Team */
	
		$team_term = get_term_by('name', $team, 'team');
		if(isset($team_term->term_id)) {
			$team_id = $team_term->term_id;
		}

		$code .= '<div class="sn-scm-fixture-table">';
		if($season) {
			$season_term = get_term_by('name', $season, 'season');
			if(isset($season_term->term_id)) {
				$season_id = $season_term->term_id;
				$args = array(
					'post_type' => 'fixture',
					'posts_per_page' => -1,
					'order_by' => 'meta_value',
					'meta_key' => 'fixture_date',
					'order' => 'ASC',
					'tax_query' => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'season',
							'field' => 'id',
							'terms' => $season_id
						),
						array(
							'taxonomy' => 'team',
							'field' => 'id',
							'terms' => $team_id
						)
					)
				);
			} else {
				$code .= 'ERROR: The season specified cannot be found';
			}
		} else {
			$args = array(
				'post_type' => 'fixture',
				'posts_per_page' => -1,
				'order_by' => 'meta_value',
				'meta_key' => 'fixture_date',
				'order' => 'ASC',
				'tax_query' => array(
					array(
						'taxonomy' => 'team',
						'field' => 'id',
						'terms' => $team_id
					)
				)
			);
		}
		
		$fixture_posts = array();
		
		$i = 0;
		if(isset($args)) {
		query_posts($args);
		while(have_posts()) : the_post();
		global $post;
			
			$fixture_time = get_post_meta($post->ID, 'fixture_time', true);
			if($fixture_time == "") { $fixture_time = '0:00'; }
			$fixture_seconds = date("U", strtotime(get_post_meta($post->ID, 'fixture_date', true).' '.$fixture_time));
			$fixture_posts[$fixture_seconds] = array($fixture_seconds, $post->ID);
			
		endwhile; 
		wp_reset_query();
		
		ksort($fixture_posts);
		
		if($what_to_show == "results" && $number > 0) {
			$fixture_posts = array_reverse($fixture_posts);
		}
		
		
		foreach($fixture_posts as $fixture_post) {
		query_posts('post_type=fixture&p='.$fixture_post[1]);
		while(have_posts()) : the_post();
		
			$show_fixture = 1;
			$fixture_time = get_post_meta($post->ID, 'fixture_time', true);
			if($fixture_time == "") { $fixture_time = '0:00'; }
			$fixture_seconds = date("U", strtotime(get_post_meta($post->ID, 'fixture_date', true).' '.$fixture_time));
			
			if($what_to_show == "results") {
				if($fixture_seconds <= date('U')) { } else { $show_fixture = 0; }
			} else if($what_to_show == "fixtures") {
				if($fixture_seconds >= date('U')) { } else { $show_fixture = 0; }
			}
			
			if($number > 0) {
				if($i >= $number) {
					$show_fixture = 0;
				}
			}
			
			if($show_fixture == 1) { $i++;
				
			$fixture_date = get_post_meta($post->ID, 'fixture_date', true);
			if($fixture_date) {
				$fixture_date = str_split($fixture_date);
				$fixture_date = date(sn_sports_club_manager_date_format(), strtotime($fixture_date[0].$fixture_date[1].$fixture_date[2].$fixture_date[3].'-'.$fixture_date[4].$fixture_date[5].'-'.$fixture_date[6].$fixture_date[7]));
			}			
			if(get_post_meta($post->ID, 'fixture_home_score', true)) {
				$home_score = get_post_meta($post->ID, 'fixture_home_score', true);
			} else { $home_score = 0; }
			if(get_post_meta($post->ID, 'fixture_away_score', true)) {
				$away_score = get_post_meta($post->ID, 'fixture_away_score', true);
			} else { $away_score = 0; }
			
			if($odd == 1) {
				$odd = 0;
				$row_class = '';
			} else {
				$odd = 1;
				$row_class = ' sn-scm-fixture-table-row-odd';
			}
			
			if(sn_sports_club_manager_is_vs()) {
				$code .= '<div class="sn-scm-fixture-table-row'.$row_class.'">';
			} else {
				$code .= '<div class="sn-scm-fixture-table-row'.$row_class.' sn-scm-fixture-table-row-no-vs">';
			}
			
			$home_logo = '';
			if(get_post_meta($post->ID, 'fixture_home_team_logo', true)) {
				$home_logo = '<img src="'.get_post_meta($post->ID, 'fixture_home_team_logo', true).'" alt="" />';
			}
			$away_logo = '';
			if(get_post_meta($post->ID, 'fixture_home_away_logo', true)) {
				$away_logo = '<img src="'.get_post_meta($post->ID, 'fixture_home_away_logo', true).'" alt="" />';
			}
			
			$code .= '<div class="cell-date"><span>'.$fixture_date.'</span></div>
				<div class="cell-home">'.$home_logo.'<span>'.get_post_meta($post->ID, 'fixture_home_team_name', true).'</span></div>
				<div class="cell-home-score"><span>'.$home_score.'</span></div>';
				
			if(sn_sports_club_manager_is_vs()) {
				$code .= '<div class="cell-away-score"><span>'.$away_score.'</span></div>
				<div class="cell-away"><span>'.get_post_meta($post->ID, 'fixture_away_team_name', true).'</span>'.$away_logo.'</div>';
			}
				
				$code .= '<div class="cell-link"><a href="'.get_permalink($post->ID).'"></a></div>
			</div>';
			
			}
		
		endwhile; wp_reset_query();
		} }
		$code .= '</div>';
	
	} else {
	/* One Team */
		
		$code .= '<div class="sn-scm-fixture-table">';
		if($season) {
			$season_term = get_term_by('name', $season, 'season');
			if(isset($season_term->term_id)) {
				$season_id = $season_term->term_id;
				$args = array(
					'post_type' => 'fixture',
					'posts_per_page' => -1,
					'order_by' => 'meta_value',
					'meta_key' => 'fixture_date',
					'order' => 'ASC',
					'tax_query' => array(array(
						'taxonomy' => 'season',
						'field' => 'id',
						'terms' => $season_id
					))
				);
			} else {
				$code .= 'ERROR: The season specified cannot be found';
			}
		} else {
			$args = array(
				'post_type' => 'fixture',
				'posts_per_page' => -1,
				'order_by' => 'meta_value',
				'meta_key' => 'fixture_date',
				'order' => 'ASC'
			);
		}
		
		$fixture_posts = array();
		
		$i = 0;
		if(isset($args)) {
		query_posts($args);
		while(have_posts()) : the_post();
		global $post;
			
			$fixture_time = get_post_meta($post->ID, 'fixture_time', true);
			if($fixture_time == "") { $fixture_time = '0:00'; }
			$fixture_seconds = date("U", strtotime(get_post_meta($post->ID, 'fixture_date', true).' '.$fixture_time));
			$fixture_posts[$fixture_seconds] = array($fixture_seconds, $post->ID);
			
		endwhile; 
		wp_reset_query();
		
		ksort($fixture_posts);
		
		if($what_to_show == "results" && $number > 0) {
			$fixture_posts = array_reverse($fixture_posts);
		}
		
		
		foreach($fixture_posts as $fixture_post) {
		query_posts('post_type=fixture&p='.$fixture_post[1]);
		while(have_posts()) : the_post();
		
			$show_fixture = 1;
			$fixture_time = get_post_meta($post->ID, 'fixture_time', true);
			if($fixture_time == "") { $fixture_time = '0:00'; }
			$fixture_seconds = date("U", strtotime(get_post_meta($post->ID, 'fixture_date', true).' '.$fixture_time));
			
			if($what_to_show == "results") {
				if($fixture_seconds <= date('U')) { } else { $show_fixture = 0; }
			} else if($what_to_show == "fixtures") {
				if($fixture_seconds >= date('U')) { } else { $show_fixture = 0; }
			}
			
			if($number > 0) {
				if($i >= $number) {
					$show_fixture = 0;
				}
			}
			
			if($show_fixture == 1) { $i++;
				
			$fixture_date = get_post_meta($post->ID, 'fixture_date', true);
			if($fixture_date) {
				$fixture_date = str_split($fixture_date);
				$fixture_date = date(sn_sports_club_manager_date_format(), strtotime($fixture_date[0].$fixture_date[1].$fixture_date[2].$fixture_date[3].'-'.$fixture_date[4].$fixture_date[5].'-'.$fixture_date[6].$fixture_date[7]));
			}			
			if(get_post_meta($post->ID, 'fixture_home_score', true)) {
				$home_score = get_post_meta($post->ID, 'fixture_home_score', true);
			} else { $home_score = 0; }
			if(get_post_meta($post->ID, 'fixture_away_score', true)) {
				$away_score = get_post_meta($post->ID, 'fixture_away_score', true);
			} else { $away_score = 0; }
			
			if($odd == 1) {
				$odd = 0;
				$row_class = '';
			} else {
				$odd = 1;
				$row_class = ' sn-scm-fixture-table-row-odd';
			}
			
			if(sn_sports_club_manager_is_vs()) {
				$code .= '<div class="sn-scm-fixture-table-row'.$row_class.'">';
			} else {
				$code .= '<div class="sn-scm-fixture-table-row'.$row_class.' sn-scm-fixture-table-row-no-vs">';
			}
			
			$home_logo = '';
			if(get_post_meta($post->ID, 'fixture_home_team_logo', true)) {
				$home_logo = '<img src="'.get_post_meta($post->ID, 'fixture_home_team_logo', true).'" alt="" />';
			}
			$away_logo = '';
			if(get_post_meta($post->ID, 'fixture_home_away_logo', true)) {
				$away_logo = '<img src="'.get_post_meta($post->ID, 'fixture_home_away_logo', true).'" alt="" />';
			}
			
			$code .= '<div class="cell-date"><span>'.$fixture_date.'</span></div>
				<div class="cell-home">'.$home_logo.'<span>'.get_post_meta($post->ID, 'fixture_home_team_name', true).'</span></div>
				<div class="cell-home-score"><span>'.$home_score.'</span></div>';
				
			if(sn_sports_club_manager_is_vs()) {
				$code .= '<div class="cell-away-score"><span>'.$away_score.'</span></div>
				<div class="cell-away"><span>'.get_post_meta($post->ID, 'fixture_away_team_name', true).'</span>'.$away_logo.'</div>';
			}
				
				$code .= '<div class="cell-link"><a href="'.get_permalink($post->ID).'"></a></div>
			</div>';
			
			}
		
		endwhile; wp_reset_query();
		} }
		$code .= '</div>';
		
	}
	
	
	
	$code .= '</div>';
	
	return $code;
}
add_shortcode('fixtures_table', 'sn_fixtures_table');


/* Players Shortcode */
function sn_players($atts, $content = null) {
	extract(shortcode_atts(array(
		'team' => ''
	), $atts));
	global $sn_sports_club_manager_options;
	
	$code = '';
	$code .= '<div class="sn-scm-players-grid">';
	
	$terms = get_terms("team");
	$count = count($terms);
	if($team) {
	/* Specified Team */
		
		$team_term = get_term_by('name', $team, 'team');
		if(isset($team_term->term_id)) {
	
			$code .= '<div class="sn-sports-club-players">';
			$args = array(
				'post_type' => 'player',
				'posts_per_page' => -1,
				'order_by' => 'name',
				'order' => 'ASC',
				'tax_query' => array(
					array(
						'taxonomy' => 'team',
						'field' => 'id',
						'terms' => $team_term->term_id
					)
				)
			);
			if(isset($args)) {
			query_posts($args);
			while(have_posts()) : the_post();
			global $post;
			
				if(has_post_thumbnail()) {
					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'player_thumb' );
					$image = $thumb['0'];
				} else {
					global $sn_sports_club_manager_directory;
					$image = $sn_sports_club_manager_directory.'/images/player-thumb.jpg';
				}
					
				$code .= '<a href="'.get_permalink($post->ID).'" class="sn-scm-players-player">
					<span class="inner">
					<span class="image"><img src="'.$image.'" alt="'.$post->post_title.'" /></span>
					<span class="player-name">'.$post->post_title.'</span>';
					
					if(get_post_meta($post->ID, 'player_position', true)) {
						$code .= '<span class="player-position">'.get_post_meta($post->ID, 'player_position', true).'</span>';
					}
					
					$code .= '<span class="player-view">View Player</span>
				</span></a>';
			
			endwhile; wp_reset_query();
			}
			$code .= '</div>';
		
		} else {
			$code .= 'ERROR: The season specified cannot be found';
		}

	
	} else if($count > 1) {
	/* Multiple Teams */
	
		$code .= '<div class="sn-scm-players-filter"><div class="sn-scm-select"><select>';
		foreach ( $terms as $term ) {
			$code .= '<option value="'.$term->term_id.'">'.$term->name.'</option>';
		}
		$code .= '</select></div></div>';
		
	foreach($terms as $team) {
			
		$code .= '<div class="sn-sports-club-players" id="team-'.$team->term_id.'">';
		$args = array(
			'post_type' => 'player',
			'posts_per_page' => -1,
			'order_by' => 'name',
			'order' => 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => 'team',
					'field' => 'id',
					'terms' => $team->term_id
				)
			)
		);
		if(isset($args)) {
		query_posts($args);
		while(have_posts()) : the_post();
		global $post;
		
			if(has_post_thumbnail()) {
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'player_thumb' );
				$image = $thumb['0'];
			} else {
				global $sn_sports_club_manager_directory;
				$image = $sn_sports_club_manager_directory.'/images/player-thumb.jpg';
			}
				
			$code .= '<a href="'.get_permalink($post->ID).'" class="sn-scm-players-player">
				<span class="inner">
				<img src="'.$image.'" alt="'.$post->post_title.'" />
				<span class="player-name">'.$post->post_title.'</span>';
				
				if(get_post_meta($post->ID, 'player_position', true)) {
					$code .= '<span class="player-position">'.get_post_meta($post->ID, 'player_position', true).'</span>';
				}
				
				$code .= '<span class="player-view">View Player</span>
			</span></a>';
		
		endwhile; wp_reset_query();
		}
		$code .= '</div>';
			
	}
		
	} else {
	/* One Team */
			
		$code .= '<div class="sn-sports-club-players">';
		$args = array(
			'post_type' => 'player',
			'posts_per_page' => -1,
			'order_by' => 'name',
			'order' => 'ASC'
		);
		if(isset($args)) {
		query_posts($args);
		while(have_posts()) : the_post();
		global $post;
		
			if(has_post_thumbnail()) {
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'player_thumb' );
				$image = $thumb['0'];
			} else {
				global $sn_sports_club_manager_directory;
				$image = $sn_sports_club_manager_directory.'/images/player-thumb.jpg';
			}
			
			$code .= '<a href="'.get_permalink($post->ID).'" class="sn-scm-players-player">
				<span class="inner">
				<img src="'.$image.'" alt="'.$post->post_title.'" />
				<span class="player-name">'.$post->post_title.'</span>';
				
				if(get_post_meta($post->ID, 'player_position', true)) {
					$code .= '<span class="player-position">'.get_post_meta($post->ID, 'player_position', true).'</span>';
				}
				
				$code .= '<span class="player-view">View Player</span>
			</span></a>';
		
		endwhile; wp_reset_query();
		}
		$code .= '</div>';
		
	}
	
	
	
	$code .= '</div>';
	
	return $code;
}
add_shortcode('players', 'sn_players');


/* Fixture Single */

function sn_fixture_single($content) {
	if(is_singular() && is_main_query() && get_post_type() == "fixture") {
		$new_content = '';
		global $post;
		
		if(get_post_meta($post->ID, 'fixture_home_score', true)) {
			$home_score = get_post_meta($post->ID, 'fixture_home_score', true);
		} else { $home_score = 0; }
		if(get_post_meta($post->ID, 'fixture_away_score', true)) {
			$away_score = get_post_meta($post->ID, 'fixture_away_score', true);
		} else { $away_score = 0; }
		$fixture_date = get_post_meta($post->ID, 'fixture_date', true);
		if($fixture_date) {
			$fixture_date = str_split($fixture_date);
			$fixture_date = date(sn_sports_club_manager_date_format(), strtotime($fixture_date[0].$fixture_date[1].$fixture_date[2].$fixture_date[3].'-'.$fixture_date[4].$fixture_date[5].'-'.$fixture_date[6].$fixture_date[7]));
		}	
		
		if(sn_sports_club_manager_is_vs()) {
			$new_content .= '<div class="sn-scm-fixture-single">';
		} else {
			$new_content .= '<div class="sn-scm-fixture-single sn-scm-fixture-single-no-vs">';
		}
		$new_content .= '<div class="sn-scm-fixture-single-header">
				<div class="sn-scm-fixture-single-header-teams">
					<span class="team-left">
						<span class="team-name">'.get_post_meta($post->ID, 'fixture_home_team_name', true).'</span>
						<span class="team-score">'.$home_score.'</span>
					</span>';
					
				if(sn_sports_club_manager_is_vs()) {
					$new_content .= '<span class="team-right">
						<span class="team-score">'.$away_score.'</span>
						<span class="team-name">'.get_post_meta($post->ID, 'fixture_away_team_name', true).'</span>
					</span>';
				}
					$new_content .= '</div>';
				
				if(get_post_meta($post->ID, 'fixture_date_nicename', true)) {
					$new_content .= '<div class="sn-scm-fixture-single-header-when">'.get_post_meta($post->ID, 'fixture_date_nicename', true).'</div>';
				} else {
				$new_content .= '<div class="sn-scm-fixture-single-header-when">
					'.$fixture_date.'';
					if(get_post_meta($post->ID, 'fixture_time', true)) {
						$new_content .= ' - '.get_post_meta($post->ID, 'fixture_time', true);
					}
				$new_content .= '</div>';
				}
				
				if(get_post_meta($post->ID, 'fixture_location', true)) {
					$new_content .= '<div class="sn-scm-fixture-single-header-location">'.get_post_meta($post->ID, 'fixture_location', true).'</div>';
				}
				if(get_post_meta($post->ID, 'fixture_game_type', true)) {
					$new_content .= '<div class="sn-scm-fixture-single-header-type">'.get_post_meta($post->ID, 'fixture_game_type', true).'</div>';
				}
			$new_content .= '</div>
		';
		
			
		
		$content = $new_content.$content.'</div><!-- end sn-scm-fixture-single -->';
	}	
	return $content;
}
add_filter('the_content', 'sn_fixture_single');


/* Player Single */

function sn_player_single($content) {
	if(is_singular() && is_main_query() && get_post_type() == "player") {
		$new_content = '';
		global $post;
	
		if(has_post_thumbnail()) {
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'player_thumb' );
			$image = $thumb['0'];
		} else {
			global $sn_sports_club_manager_directory;
			$image = $sn_sports_club_manager_directory.'/images/player-thumb.jpg';
		}
		
		$new_content .= '<div class="sn-scm-player-single">
			<div class="sn-scm-player-single-pic"><img src="'.$image.'" alt="" /></div>
			<div class="sn-scm-player-single-facts">';
			if(get_post_meta($post->ID, 'player_position', true)) {
				$new_content .= '<div class="sn-scm-player-single-fact"><span>'.__('Position', 'snplugin').'</span>'.get_post_meta($post->ID, 'player_position', true).'</div>';
			}
			$i = 0; while($i < 100) { $i++; if(get_post_meta($post->ID, 'player_facts_label__'.$i, true)) {
				$new_content .= '<div class="sn-scm-player-single-fact"><span>'.get_post_meta($post->ID, 'player_facts_label__'.$i, true).'</span>'.get_post_meta($post->ID, 'player_facts_value__'.$i, true).'</div>';
			} }
		$new_content .= '</div></div>';
	
		$content = $new_content.$content;
	}	
	return $content;
}
add_filter('the_content', 'sn_player_single');


/* Fixtures Widget */
class sn_fixtures_widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'sntheme_fixtures_widget',
			__( 'Fixtures and Results', 'snplugin' ), 
			array( 'description' => __( '', 'snplugin' ) )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		if(isset($instance['title'])) {
			$title = apply_filters( 'sntheme_fixtures_widget', $instance['title'] );
		} else { $title = '';}
		if(isset($instance['season'])) {
			$season = apply_filters( 'sntheme_fixtures_widget', $instance['season'] );
		} else { $season = ''; }
		if(isset($instance['number'])) {
			$number = apply_filters( 'sntheme_fixtures_widget', $instance['number'] );
		} else { $number = ''; }
		if(isset($instance['button_link'])) {
			$button_link = apply_filters( 'sntheme_fixtures_widget', $instance['button_link'] );
		} else { $button_link = ''; }
		if(isset($instance['button_text'])) {
			$button_text = apply_filters( 'sntheme_fixtures_widget', $instance['button_text'] );
		} else { $button_text = ''; }
		if(isset($instance['what_to_show'])) {
			$what_to_show = apply_filters( 'sntheme_fixtures_widget', $instance['what_to_show'] );
		} else { $what_to_show = ''; }
		
		echo $before_widget;
		
			if(!empty($title)) { echo $before_title . $title . $after_title; }

			if($season) {
				echo do_shortcode('[fixtures_table season="'.$season.'" number="'.$number.'" what_to_show="'.$what_to_show.'"]');
			} else {
				echo do_shortcode('[fixtures_table number="'.$number.'" what_to_show="'.$what_to_show.'"]');
			}
			
			if($button_link) {
				echo '<a href="'.$button_link.'" class="sn-button">'.$button_text.'</a>';
			}
		
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['season'] = strip_tags( $new_instance['season'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['button_link'] = strip_tags( $new_instance['button_link'] );
		$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		$instance['what_to_show'] = strip_tags( $new_instance['what_to_show'] );
		return $instance;
	}

	public function form( $instance ) {
		// Form Data
		if(isset($instance['title'])) { $title = $instance[ 'title' ]; } else { $title = __( 'Fixtures & Results', 'snplugin' ); }
		if(isset($instance['season'])) { $season = $instance[ 'season' ]; } else { $season = ''; }
		if(isset($instance['number'])) { $number = $instance[ 'number' ]; } else { $number = ''; }
		if(isset($instance['button_link'])) { $button_link = $instance[ 'button_link' ]; } else { $button_link = ''; }
		if(isset($instance['button_text'])) { $button_text = $instance[ 'button_text' ]; } else { $button_text = ''; }
		if(isset($instance['what_to_show'])) { $what_to_show = $instance[ 'what_to_show' ]; } else { $what_to_show = ''; }
		
		// Form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'season' ); ?>"><?php _e( 'Season:' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'season' ); ?>" name="<?php echo $this->get_field_name( 'season' ); ?>">
				<option value="">All Seasons</option>
			<?php
			$terms = get_terms("season");
			$count = count($terms);
			if($count > 0) { foreach ( $terms as $term ) {
				if($season == $term->name) {
					echo '<option selected="selected" value="'.$term->name.'">'.$term->name.'</option>';
				} else {
					echo '<option value="'.$term->name.'">'.$term->name.'</option>';
				}
			} } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number to show:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'what_to_show' ); ?>"><?php _e( 'What to show:' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'what_to_show' ); ?>" name="<?php echo $this->get_field_name( 'what_to_show' ); ?>">
				<option value="">From start of season</option>
				<option value="results"<?php if($what_to_show == "results") { echo ' selected="selected"'; } ?>>Show results (past games)</option>
				<option value="fixtures"<?php if($what_to_show == "fixtures") { echo ' selected="selected"'; } ?>>Show fixtures (future games)</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_link' ); ?>"><?php _e( 'Button Link:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'button_link' ); ?>" name="<?php echo $this->get_field_name( 'button_link' ); ?>" type="text" value="<?php echo esc_attr( $button_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button Text:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" />
		</p>
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "sn_fixtures_widget" );' ) );