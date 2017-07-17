<?php

/* Select and Radios Arrays */ 
$sn_sports_club_manager_date_formats = array(
	'' => array(
		'value' => '',
		'label' => __( 'Default', 'snplugin' )
	),
	'jS M Y' => array(
		'value' => 'jS M Y',
		'label' => date('jS M Y')
	),
	'd/m/Y' => array(
		'value' => 'd/m/Y',
		'label' => date('d/m/Y')
	),
	'm/d/Y' => array(
		'value' => 'm/d/Y',
		'label' => date('m/d/Y')
	),
	'Y-m-d' => array(
		'value' => 'Y-m-d',
		'label' => date('Y-m-d')
	)
); 

/* Options Fields Array */
$sports_club_manager_options_fields = array();

$sports_club_manager_options_fields[] = array(	"label" => 				__( "Date Format", 'snplugin' ), 
												"name" =>					"date_format", 
												"choices" =>			$sn_sports_club_manager_date_formats,
												"description" => 		"",
												"field" => 				"select",
												"validate_as" => 		"" );

$sports_club_manager_options_fields[] = array(	"label" => 				__( "Specific Date Format", 'snplugin' ), 
												"name" =>					"specific_date_format", 
												"description" => 		"For advanced users only, use the PHP date formats: http://uk1.php.net/manual/en/function.date.php",
												"field" => 				"text",
												"validate_as" => 		"" );

$sports_club_manager_options_fields[] = array(	"label" => 				__( "", 'snplugin' ), 
												"name" =>					"hide_vs", 
												"description" => 		"Hide vs. teams",
												"field" => 				"checkbox",
												"validate_as" => 		"" );