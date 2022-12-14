<?php

// Do not allow direct access to this file.
if( ! function_exists('add_action') )
	die();

/**
 * Plugin version
 *
 * Get the current plugin version.
 *
 * @return string
 */
function smk_sidebar_version(){
	return '3.4.1';
}

/**
 * All conditions
 *
 * All condtions will be accessible from this function
 *
 * @return array All conditions type => class_name
 */
function smk_sidebar_conditions_filter(){
	return apply_filters( 'smk_sidebar_conditions_filter', array() );
}

/**
 * Register a condition
 *
 * Register a condition and inject it in the main array
 *
 * @param string $name Condition class name
 * @return void
 */
class Smk_Sidebar_Generator_Register_Condition{
	public $name;
	public $allCond;

	public function __construct( $name ){
		$this->name = $name;
		$this->allCond = smk_sidebar_conditions_filter();
		add_filter( 'smk_sidebar_conditions_filter', array( $this, 'add') );
	}
	public function add(){
		if( class_exists( $this->name ) ){
			$class = new $this->name;
			if( ! array_key_exists($class->type, $this->allCond) ){
				return array( $class->type => $this->name );
			}
		}
	}
}

/**
 * Register condition helper
 *
 * @param string $name Condition class name
 * @use Smk_Sidebar_Generator_Register_Condition
 * @return void
 */
function smk_register_condition( $name ){
	new Smk_Sidebar_Generator_Register_Condition( $name );
}


//------------------------------------//--------------------------------------//

/*
-------------------------------------------------------------------------------
Smk Sidebar function
-------------------------------------------------------------------------------
*/
function smk_sidebar($id){
	if(function_exists('dynamic_sidebar') && dynamic_sidebar($id)) :
	endif;
	return true;
}

/*
-------------------------------------------------------------------------------
Smk All Sidebars
-------------------------------------------------------------------------------
*/
if(! function_exists('smk_get_all_sidebars') ) {
	function smk_get_all_sidebars(){
		global $wp_registered_sidebars;
		$all_sidebars = array();
		if ( $wp_registered_sidebars && ! is_wp_error( $wp_registered_sidebars ) ) {

			foreach ( $wp_registered_sidebars as $sidebar ) {
				$all_sidebars[ $sidebar['id'] ] = $sidebar['name'];
			}

		}
		return $all_sidebars;
	}
}

/*
----------------------------------------------------------------------
Shortcode
----------------------------------------------------------------------
*/
// [smk_sidebar id="X"] //X is the sidebar ID
function smk_sidebar_shortcode( $atts ) {

	extract( shortcode_atts( array(
		'id' => null,
	), $atts ) );
	smk_sidebar($id);
}
add_shortcode( 'smk_sidebar', 'smk_sidebar_shortcode' );

/* Plugin path
------------------------------------------------*/
$path = SMK_SIDEBAR_GENERATOR_DIR;

/* HTML helper
------------------------------------------------*/
require_once $path . 'html.php';

/* Conditions
------------------------------------------------*/
require_once $path . 'condition.php';
require_once $path . 'condition-cpt.php';

/* Init conditions
------------------------------------------------*/
smk_register_condition( 'Smk_Sidebar_Generator_Condition_Cpt' );

/* Plugin work
------------------------------------------------*/
require_once $path . 'abstract.php';
require_once $path . 'render.php';
require_once $path . 'apply.php';

/* Init plugin
------------------------------------------------*/
$smk_sidebar_generator = new Smk_Sidebar_Generator;
$smk_sidebar_generator->init();

/* Apply conditions
------------------------------------------------*/
$applySidebars = new Smk_Sidebar_Generator_Apply;
