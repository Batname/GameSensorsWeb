<?php

if( !class_exists('blackfyre_acf') ):

class blackfyre_acf
{
	// vars
	var $settings;


	/*
	*  Constructor
	*
	*  This function will construct all the neccessary actions, filters and functions for the ACF plugin to work
	*
	*  @type	function
	*  @date	23/06/12
	*  @since	1.0.0
	*
	*  @param	N/A
	*  @return	N/A
	*/

	function __construct()
	{
		// helpers
		add_filter('acf/helpers/get_path', array($this, 'helpers_get_path'), 1, 1);
		add_filter('acf/helpers/get_dir', array($this, 'helpers_get_dir'), 1, 1);


		// vars
		$this->settings = array(
			'path'				=> apply_filters('acf/helpers/get_path', __FILE__),
			'dir'				=> apply_filters('acf/helpers/get_dir', __FILE__),
			'hook'				=> basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ),
			'version'			=> '4.3.8',
			'upgrade_version'	=> '3.4.1',
			'include_3rd_party'	=> false
		);


			// actions
		add_action('init', array($this, 'init'), 1);
		add_action('acf/pre_save_post', array($this, 'save_post_lock'), 0);
		add_action('acf/pre_save_post', array($this, 'save_post_unlock'), 999);
		add_action('acf/save_post', array($this, 'save_post_lock'), 0);
		add_action('acf/save_post', array($this, 'save_post'), 10);
		add_action('acf/save_post', array($this, 'save_post_unlock'), 999);
		add_action('acf/create_fields', array($this, 'create_fields'), 1, 2);


		// filters
		add_filter('acf/get_info', array($this, 'get_info'), 1, 1);
		add_filter('acf/parse_types', array($this, 'parse_types'), 1, 1);
		add_filter('acf/get_post_types', array($this, 'get_post_types'), 1, 3);
		add_filter('acf/get_taxonomies_for_select', array($this, 'get_taxonomies_for_select'), 1, 2);
		add_filter('acf/get_image_sizes', array($this, 'get_image_sizes'), 1, 1);
		add_filter('acf/get_post_id', array($this, 'get_post_id'), 1, 1);


		// includes
		$this->include_before_theme();
		add_action('after_setup_theme', array($this, 'include_after_theme'), 1);
		add_action('after_setup_theme', array($this, 'include_3rd_party'), 1);

	}


	/*
	*  helpers_get_path
	*
	*  This function will calculate the path to a file
	*
	*  @type	function
	*  @date	30/01/13
	*  @since	3.6.0
	*
	*  @param	$file (file) a reference to the file
	*  @return	(string)
	*/

    function helpers_get_path( $file )
    {
        return trailingslashit(dirname($file));
    }


    /*
	*  helpers_get_dir
	*
	*  This function will calculate the directory (URL) to a file
	*
	*  @type	function
	*  @date	30/01/13
	*  @since	3.6.0
	*
	*  @param	$file (file) a reference to the file
	*  @return	(string)
	*/

    function helpers_get_dir( $file )
    {
        $dir = trailingslashit(dirname($file));
        $count = 0;


        // sanitize for Win32 installs
        $dir = str_replace('\\' ,'/', $dir);


        // if file is in plugins folder
        $wp_plugin_dir = str_replace('\\' ,'/', WP_PLUGIN_DIR);
        $dir = str_replace($wp_plugin_dir, plugins_url(), $dir, $count);


        if( $count < 1 )
        {
	        // if file is in wp-content folder
	        $wp_content_dir = str_replace('\\' ,'/', WP_CONTENT_DIR);
	        $dir = str_replace($wp_content_dir, content_url(), $dir, $count);
        }


        if( $count < 1 )
        {
	        // if file is in ??? folder
	        $wp_dir = str_replace('\\' ,'/', ABSPATH);
	        $dir = str_replace($wp_dir, site_url('/'), $dir);
        }


        return $dir;
    }


	/*
	*  acf/get_post_id
	*
	*  A helper function to filter the post_id variable.
	*
	*  @type	filter
	*  @date	27/05/13
	*
	*  @param	{mixed}	$post_id
	*  @return	{mixed}	$post_id
	*/

	function get_post_id( $post_id )
	{
		// set post_id to global
		if( !$post_id )
		{
			global $post;

			if( $post )
			{
				$post_id = intval( $post->ID );
			}
		}


		// allow for option == options
		if( $post_id == "option" )
		{
			$post_id = "options";
		}


		// object
		if( is_object($post_id) )
		{
			if( isset($post_id->roles, $post_id->ID) )
			{
				$post_id = 'user_' . $post_id->ID;
			}
			elseif( isset($post_id->taxonomy, $post_id->term_id) )
			{
				$post_id = $post_id->taxonomy . '_' . $post_id->term_id;
			}
			elseif( isset($post_id->ID) )
			{
				$post_id = $post_id->ID;
			}
		}


		/*
		*  Override for preview
		*
		*  If the $_GET['preview_id'] is set, then the user wants to see the preview data.
		*  There is also the case of previewing a page with post_id = 1, but using get_field
		*  to load data from another post_id.
		*  In this case, we need to make sure that the autosave revision is actually related
		*  to the $post_id variable. If they match, then the autosave data will be used, otherwise,
		*  the user wants to load data from a completely different post_id
		*/

		if( isset($_GET['preview_id']) )
		{
			$autosave = wp_get_post_autosave( $_GET['preview_id'] );
			if( $autosave->post_parent == $post_id )
			{
				$post_id = intval( $autosave->ID );
			}
		}


		// return
		return $post_id;
	}


	/*
	*  get_info
	*
	*  This function will return a setting from the settings array
	*
	*  @type	function
	*  @date	24/01/13
	*  @since	3.6.0
	*
	*  @param	$i (string) the setting to get
	*  @return	(mixed)
	*/

	function get_info( $i )
	{
		// vars
		$return = false;


		// specific
		if( isset($this->settings[ $i ]) )
		{
			$return = $this->settings[ $i ];
		}


		// all
		if( $i == 'all' )
		{
			$return = $this->settings;
		}


		// return
		return $return;
	}


	/*
	*  parse_types
	*
	*  @description: helper function to set the 'types' of variables
	*  @since: 2.0.4
	*  @created: 9/12/12
	*/

	function parse_types( $value )
	{
		// vars
		$restricted = array(
			'label',
			'name',
			'_name',
			'value',
			'instructions'
		);


		// is value another array?
		if( is_array($value) )
		{
			foreach( $value as $k => $v )
			{
				// bail early for restricted pieces
				if( in_array($k, $restricted, true) )
				{
					continue;
				}


				// filter piece
				$value[ $k ] = apply_filters( 'acf/parse_types', $v );
			}
		}
		else
		{
			// string
			if( is_string($value) )
			{
				$value = trim( $value );
			}


			// numbers
			if( is_numeric($value) )
			{
				// check for non numeric characters
				if( preg_match('/[^0-9]/', $value) )
				{
					// leave value if it contains such characters: . + - e
					//$value = floatval( $value );
				}
				else
				{
					$value = intval( $value );
				}
			}
		}


		// return
		return $value;
	}


	/*
	*  include_before_theme
	*
	*  This function will include core files before the theme's functions.php file has been excecuted.
	*
	*  @type	action (plugins_loaded)
	*  @date	3/09/13
	*  @since	4.3.0
	*
	*  @param	N/A
	*  @return	N/A
	*/

	function include_before_theme()
	{
		// incudes
		include_once('core/api.php');

		include_once('core/controllers/input.php');
		include_once('core/controllers/location.php');
		include_once('core/controllers/field_group.php');


		// admin only includes
		if( is_admin() )
		{
			include_once('core/controllers/post.php');
			include_once('core/controllers/revisions.php');
			include_once('core/controllers/everything_fields.php');
			include_once('core/controllers/field_groups.php');
		}


		// register fields
		include_once('core/fields/_functions.php');
		include_once('core/fields/_base.php');


		include_once('core/fields/textarea.php');
		include_once('core/fields/image.php');


	}


	/*
	*  include_3rd_party
	*
	*  This function will include 3rd party add-ons
	*
	*  @type	function
	*  @date	29/01/2014
	*  @since	5.0.0
	*
	*  @param	N/A
	*  @return	N/A
	*/

	function include_3rd_party() {

		// run only once
		if( $this->settings['include_3rd_party'] )
		{
			return false;
		}


		// update setting
		$this->settings['include_3rd_party'] = true;


		// include 3rd party fields
		do_action('acf/register_fields');

	}


	/*
	*  include_after_theme
	*
	*  This function will include core files after the theme's functions.php file has been excecuted.
	*
	*  @type	action (after_setup_theme)
	*  @date	3/09/13
	*  @since	4.3.0
	*
	*  @param	N/A
	*  @return	N/A
	*/

	function include_after_theme() {

		// bail early if user has defined LITE_MODE as true
		if( defined('ACF_LITE') && ACF_LITE )
		{
			return;
		}

	}


	/*
	*  init
	*
	*  This function is called during the 'init' action and will do things such as:
	*  create post_type, register scripts, add actions / filters
	*
	*  @type	action (init)
	*  @date	23/06/12
	*  @since	1.0.0
	*
	*  @param	N/A
	*  @return	N/A
	*/

	function init()
	{

		


		// min
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';


		// register acf scripts
		$scripts = array();

		$scripts[] = array(
			'handle'	=> 'acf-input',
			'src'		=> $this->settings['dir'] . "js/input{$min}.js",

		);

		foreach( $scripts as $script )
		{

            if(!isset($script['deps'])){
            wp_register_script( $script['handle'], $script['src'], '', $this->settings['version'] );
            }else{
			wp_register_script( $script['handle'], $script['src'], $script['deps'], $this->settings['version'] );
			}
		}


		// register acf styles
		$styles = array(
			'acf-input'			=> $this->settings['dir'] . 'css/input.css',
		);

		foreach( $styles as $k => $v )
		{
			wp_register_style( $k, $v, false, $this->settings['version'] );
		}


		// bail early if user has defined LITE_MODE as true
		if( defined('ACF_LITE') && ACF_LITE )
		{
			return;
		}


	}


	/*
	*  admin_menu
	*
	*  @description:
	*  @since 1.0.0
	*  @created: 23/06/12
	*/


	/*
	*  post_updated_messages
	*
	*  @description: messages for saving a field group
	*  @since 1.0.0
	*  @created: 23/06/12
	*/

	function post_updated_messages( $messages )
	{
		global $post, $post_ID;

		$messages['blackfyre'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => esc_html__('Field group updated.', 'blackfyre'),
			2 => esc_html__('Custom field updated.', 'blackfyre'),
			3 => esc_html__('Custom field deleted.', 'blackfyre'),
			4 => esc_html__('Field group updated.', 'blackfyre'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( esc_html__('Field group restored to revision from %s', 'blackfyre'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => esc_html__('Field group published.', 'blackfyre'),
			7 => esc_html__('Field group saved.', 'blackfyre'),
			8 => esc_html__('Field group submitted.', 'blackfyre'),
			9 => esc_html__('Field group scheduled for.', 'blackfyre'),
			10 => esc_html__('Field group draft updated.', 'blackfyre'),
		);

		return $messages;
	}


	/*
	*  get_taxonomies_for_select
	*
	*  @description:
	*  @since: 3.6
	*  @created: 27/01/13
	*/

	function get_taxonomies_for_select( $choices, $simple_value = false )
	{
		// vars
		$post_types = get_post_types();


		if($post_types)
		{
			foreach($post_types as $post_type)
			{
				$post_type_object = get_post_type_object($post_type);
				$taxonomies = get_object_taxonomies($post_type);
				if($taxonomies)
				{
					foreach($taxonomies as $taxonomy)
					{
						if(!is_taxonomy_hierarchical($taxonomy)) continue;
						$terms = get_terms($taxonomy, array('hide_empty' => false));
						if($terms)
						{
							foreach($terms as $term)
							{
								$value = $taxonomy . ':' . $term->term_id;

								if( $simple_value )
								{
									$value = $term->term_id;
								}

								$choices[$post_type_object->label . ': ' . $taxonomy][$value] = $term->name;
							}
						}
					}
				}
			}
		}

		return $choices;
	}


	/*
	*  get_post_types
	*
	*  @description:
	*  @since: 3.5.5
	*  @created: 16/12/12
	*/

	function get_post_types( $post_types, $exclude = array(), $include = array() )
	{
		// get all custom post types
		$post_types = array_merge($post_types, get_post_types());


		// core include / exclude
		$acf_includes = array_merge( array(), $include );
		$acf_excludes = array_merge( array( 'blackfyre', 'revision', 'nav_menu_item' ), $exclude );


		// include
		foreach( $acf_includes as $p )
		{
			if( post_type_exists($p) )
			{
				$post_types[ $p ] = $p;
			}
		}


		// exclude
		foreach( $acf_excludes as $p )
		{
			unset( $post_types[ $p ] );
		}


		return $post_types;

	}


	/*
	*  get_image_sizes
	*
	*  @description: returns an array holding all the image sizes
	*  @since 3.2.8
	*  @created: 6/07/12
	*/

	function get_image_sizes( $sizes )
	{
		// find all sizes
		$all_sizes = get_intermediate_image_sizes();


		// define default sizes
		$sizes = array_merge($sizes, array(
			'thumbnail'	=>	esc_html__("Thumbnail",'blackfyre'),
			'medium'	=>	esc_html__("Medium",'blackfyre'),
			'large'		=>	esc_html__("Large",'blackfyre'),
			'full'		=>	esc_html__("Full",'blackfyre')
		));


		// add extra registered sizes
		foreach( $all_sizes as $size )
		{
			if( !isset($sizes[ $size ]) )
			{
				$sizes[ $size ] = ucwords( str_replace('-', ' ', $size) );
			}
		}


		// return array
		return $sizes;
	}


	/*
	*  render_fields_for_input
	*
	*  @description:
	*  @since 3.1.6
	*  @created: 23/06/12
	*/

	function create_fields( $fields, $post_id )
	{
		if( is_array($fields) ){ foreach( $fields as $field ){

			// if they didn't select a type, skip this field
			if( !$field || !$field['type'] || $field['type'] == 'null' )
			{
				continue;
			}


			// set value
			if( !isset($field['value']) )
			{
				$field['value'] = apply_filters('acf/load_value', false, $post_id, $field);
				$field['value'] = apply_filters('acf/format_value', $field['value'], $post_id, $field);
			}


			// required
			$required_class = "";
			$required_label = "";

			if( $field['required'] )
			{
				$required_class = ' required';
				$required_label = ' <span class="required">*</span>';
			}
            $blackfyre_allowed = wp_kses_allowed_html( 'post' );

			echo '<div id="acf-' . $field['name'] . '" class="field field_type-' . $field['type'] . ' field_key-' . $field['key'] . $required_class . '" data-field_name="' . $field['name'] . '" data-field_key="' . $field['key'] . '" data-field_type="' . $field['type'] . '">';

				echo '<p class="label">';
					echo '<label for="' . $field['id'] . '">' . $field['label'] . $required_label . '</label>';
					echo wp_kses($field['instructions'],$blackfyre_allowed);
				echo '</p>';

				$field['name'] = 'fields[' . $field['key'] . ']';
				do_action('acf/create_field', $field, $post_id);

			echo '</div>';

		}}

	}


	/*
	*  save_post_lock
	*
	*  This action sets a global variable which locks the ACF save functions to this ID.
	*  This prevents an inifinite loop if a user was to hook into the save and create a new post
	*
	*  @type	function
	*  @date	16/07/13
	*
	*  @param	{int}	$post_id
	*  @return	{int}	$post_id
	*/

	function save_post_lock( $post_id )
	{
		$GLOBALS['acf_save_lock'] = $post_id;

		return $post_id;
	}


	/*
	*  save_post_unlock
	*
	*  This action sets a global variable which unlocks the ACF save functions to this ID.
	*  This prevents an inifinite loop if a user was to hook into the save and create a new post
	*
	*  @type	function
	*  @date	16/07/13
	*
	*  @param	{int}	$post_id
	*  @return	{int}	$post_id
	*/

	function save_post_unlock( $post_id )
	{
		$GLOBALS['acf_save_lock'] = false;

		return $post_id;
	}


	/*
	*  save_post
	*
	*  @description:
	*  @since: 3.6
	*  @created: 28/01/13
	*/

	function save_post( $post_id )
	{

		// load from post
		if( !isset($_POST['fields']) )
		{
			return $post_id;
		}


		// loop through and save
		if( !empty($_POST['fields']) )
		{
			// loop through and save $_POST data
			foreach( $_POST['fields'] as $k => $v )
			{
				// get field
				$f = apply_filters('acf/load_field', false, $k );

				// update field
				do_action('acf/update_value', $v, $post_id, $f );

			}
			// foreach($fields as $key => $value)
		}
		// if($fields)


		return $post_id;
	}


}


/*
*  acf
*
*  The main function responsible for returning the one true acf Instance to functions everywhere.
*  Use this function like you would a global variable, except without needing to declare the global.
*
*  Example: <?php $acf = acf(); ?>
*
*  @type	function
*  @date	4/09/13
*  @since	4.3.0
*
*  @param	N/A
*  @return	(object)
*/

function bf_acf()
{
	global $acf;

	if( !isset($acf) )
	{
		$acf = new blackfyre_acf();
	}

	return $acf;
}


// initialize
bf_acf();


endif; // class_exists check

?>
