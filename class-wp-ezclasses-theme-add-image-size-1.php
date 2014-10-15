<?php
/** 
 * WP's add_image_size(), set_post_thumbnail_size() and a couple other image related bits get ezTized. 
 *
 * TODO - Long Desc 
 *
 * PHP version 5.3
 *
 * LICENSE: TODO
 *
 * @package WP ezClasses
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.1
 * @license TODO
 */
 
/**
 * == Change Log == 
 *
 * --- 0.5.5 - Tue 14 Oct 2014 - Added: add_filter( 'jpeg_quality',...)
 *
 * --- 0.5.4 - Sun 12 Oct 2014 - Added: 'names_choose' setting to make it ez to add the new add_image_size()s to the Media selector size select.
 *
 * --- 0.5.3 - Sat 11 Oct 2014 - Added: 'offset' - Once the width is defined you can adjust it (mathematically) by some offset. 
 *
 * --- 0.5.2 - Sat 11 Oct 2014 - Added: 'orientation' - ratio defines the 'w' and 'h' and orientation ('land' or 'port') defines how they'll be applied. 
 *
 * --- 0.5.1 - Fri 10 Oct 2014 - Added: 'ratio' setting to arr_args. Allows you to set wide (or height) and then calculate height (or width) by specifying a ratio name. 
 */
 
/**
 * == TODO == 
 *
 * Clean up the legacy code junk
 */

// No WP? Die! Now!!
if (!defined('ABSPATH')) {
	header( 'HTTP/1.0 403 Forbidden' );
    die();
}

if ( ! class_exists('Class_WP_ezClasses_Theme_Add_Image_Size_1') ) {
  class Class_WP_ezClasses_Theme_Add_Image_Size_1 extends Class_WP_ezClasses_Master_Singleton{
  
    protected $_arr_init;
	
	protected $_bool_remove_width_height;
	protected $_bool_isnc_defaults;
	protected $_arr_isnc;
	protected $_int_jpeg_quality;
	
	// protected $_bool_validate_only_active_true;
	
	public function __construct() {
	  parent::__construct();
	}
	
	public function ezc_init($arr_args = ''){
	
	  $arr_init_defaults = $this->init_defaults();
	  
	  $this->_arr_init = WP_ezMethods::ez_array_merge(array($arr_init_defaults, $arr_args));
	  
	  $this->_bool_remove_width_height = (bool)$this->_arr_init['remove_width_height_filter'];
	  $this->_bool_isnc_defaults = (bool)$this->_arr_init['isnc_defaults'];


	  add_filter( 'post_thumbnail_html', array($this, 'filter_remove_width_height_attributes'), 10 );
	  add_filter( 'image_send_to_editor', array($this, 'filter_remove_width_height_attributes'), 10 );

	  
	  $this->_arr_isnc = false;
	  add_filter('image_size_names_choose', array($this, 'filter_image_size_names_choose') );
	  
	  $this->_int_jpeg_quality = absint($this->_arr_init['jpeg_quality']);
	  add_filter( 'jpeg_quality', array($this, 'filter_jpeg_quality')  );
	}
		
	protected function init_defaults(){
	
	  $arr_defaults = array(
	    'active' 						=> true,		// the master switch. false
		'active_true'					=> true,		// use the avtive_true "filtering"
		'filters' 						=> false,		// currently NA but let's leave it for now
		'validation' 					=> false,		// currently NA but let's leave it for now
		'remove_width_height_filter'	=> false,		// when inserting an image into the_content() remove the width and height?
		'isnc_defaults'					=> true,		// for the mage_size_names_choose select. true = keep the default sizes. false = remove (i.e., unset) them.
		'jpeg_quality'					=> 90, 			// WP's default is 90.
		'arr_args'						=> array(),
        ); 
	  return $arr_defaults;
	}
	
	/**
	 * ez add_image_size
	 */ 
	public function ez_ais( $arr_args = '' ){
	
	  if ( ! WP_ezMethods::array_pass($arr_args) ){
	    return array('status' => false, 'msg' => 'ERROR: arr_args is not valid', 'source' => get_class() . ' ' . __METHOD__, 'arr_args' => 'error');
	  }
	  
	  $arr_args = WP_ezMethods::ez_array_merge(array( $this->_arr_init, $arr_args)); 
	  
	  if ( $arr_args['active'] === true && WP_ezMethods::array_pass($arr_args['arr_args']) ){
	    $arr_add_image_size = $this->pre_process($arr_args);
		
		// do (it)
		return $this->add_image_size_do($arr_add_image_size);
	  }
	}
	
	
	/**
	 * ez image_size_names_choose
	 */ 
	public function ez_isnc( $arr_args = '' ){
	
	  if ( ! WP_ezMethods::array_pass($arr_args) ){
	    return array('status' => false, 'msg' => 'ERROR: arr_args is not valid', 'source' => get_class() . ' ' . __METHOD__, 'arr_args' => 'error');
	  }
	  
	  $arr_args = WP_ezMethods::ez_array_merge(array( $this->_arr_init, $arr_args)); 
	  
	  if ( $arr_args['active'] === true && WP_ezMethods::array_pass($arr_args['arr_args']) ){
	  
	    $arr_add_image_size = $this->pre_process($arr_args);
		
		// set the property to "pass" the array to the filter
		$this->_arr_isnc = $arr_add_image_size;
	  }
	}

	/**
	 *
	 */
	protected function pre_process($arr_args = array()){
	
	  $arr_add_image_size = $arr_args['arr_args'];

				// validate - optional
				/* TODO
				if ( isset($arr_args['validate']) && $arr_args['validate'] === true ){

					$arr_ret = $this->_obj_ezc_theme_images->add_image_size_validate($arr_add_image_size);
					if ( $arr_ret['status'] !== true){
						return false;
					}
					$arr_add_image_size = $arr_ret['arr_args'];
				}
				*/

      // active_true
	  if ( WP_ezMethods::ez_true($arr_args['active_true']) ){
	    
		$arr_active_true_response = $this->add_image_size_active_true($arr_add_image_size);
		  
		if ( $arr_active_true_response['status'] === false ){
		
		  return $arr_active_true_response;
		} 
		$arr_add_image_size = $arr_active_true_response['arr_args'];
	  }
	  
	  return $arr_add_image_size;
	}
	
	/**
	 * 
	 */		
	public function add_image_size_active_true($arr_args = ''){
	
	  $str_return_source = get_class() . ' > ' . __METHOD__; 
	  
	  if ( WP_ezMethods::array_pass($arr_args) ) {
	    $arr_active_true = array();			
		  
		foreach ($arr_args as $str_key => $arr_value){
		  if ( WP_ezMethods::ez_true($arr_value['active']) ){
		    $arr_active_true[$str_key] = $arr_value;
		  }			
		}
		return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_active_true);
	  } 
	  // TODO what if the result is empty. there are no active === true
	  return array('status' => false, 'msg' => 'ERROR: arr_args == NULL || arr_args == empty()', 'source' => $str_return_source, 'arr_args' => 'error');
	}
	
	/**
	 * 
	 */			
	public function add_image_size_defaults(){
	
	  $arr_ais_defaults = array(
	    'width'			=> false,
		'height'		=> false,			
		'crop'			=> false,
		'ratio'			=> 'custom',
		'orientation'	=> 'land',
		'offset'		=> 0
	  );
	  
	  return $arr_ais_defaults;
	}
	
	
	
	/**
	 * Note: All ratios are defined as landscape by default. The exception, obviously, is for square / sqr / 1x1.
	 *
	 * http://en.wikipedia.org/wiki/Aspect_ratio_%28image%29
	 * 
	 * http://www.papersizes.org/a-paper-sizes-tsta.htm
	 */			
	public function resize_ratios(){
	 
	   $arr_ratios = array(
	     
		 // traditional tv
	     'tv' => array(
		   'w' => 4,
		   'h' => 3
		   ),
		   
		 '4x3' => array(
		   'w' => 4,
		   'h' => 3
		   ),
		 
		 // academy standard film aspect ratio
		 'academy' => array(
		   'w' => 1.375,
		   'h' => 1
		   ),
		 
		 '1.375x1' => array(
		   'w' => 1.375,
		   'h' => 1
		   ),
		  
		  // IMAX motion picture film format
		 'imax' => array(
		    'w' => 1.43,
			'h' => 1
			),
		  
		 '1.43x1' => array(
		   'w' => 1.43,
		   'h' => 1
		   ),			  
		
		 // traditional photo
		 'photo' => array(
		   'w' => 3,
		   'h' => 2
		   ),
		 
		 '3x2' => array(
		   'w' => 3,
		   'h' => 2
		   ),
		 
		 // paper
		 'paper' => array(
		   'w' => 11,
		   'h' => 8.5
		   ),
		 
		 '11x8.5' => array(
		   'w' => 11,
		   'h' => 8.5
		   ),
		   
		 // legal paper
		 'legal' => array(
		   'w' => 14,
		   'h' => 8.5
		   ),
		 
		 '14x8.5' => array(
		   'w' => 14,
		   'h' => 8.5
		   ),
		   
		 // a4 paper  8.27 in × 11.7 in
		 'a4' => array(
		   'w' => 297,
		   'h' => 210
		   ),
		   
		 '297x210' => array(
		   'w' => 11.7,
		   'h' => 8.27
		   ),
		   
		 // golden ratio
		 'golden' => array(
		   'w' => 16.18,
		   'h' => 10
		 ),
		 
		 '16.18x10' => array(
		   'w' => 16.18,
		   'h' => 10
		 ),
		 
		 // hd video 
		 'video' => array(
		   'w' => 16,
		   'h' => 9
		   ),
		   
		 '16x9' => array(
		   'w' => 16,
		   'h' => 9
		   ),
		
		 // widescreen_cinema
		 'widescreen' => array(
		   'w' => 24,
		   'h' => 10
		   ),
			
		 '24x10' => array(
		   'w' => 24,
		   'h' => 10
		   ),
		 
		 // square
		 'square' => array(
		   'w' => 1,
		   'h' => 1
		   ),
		   
		 'sqr' => array(
		   'w' => 1,
		   'h' => 1
		   ),
		   
		 '1x1' => array(
		   'w' => 1,
		   'h' => 1
		   ),

		 // 1.5 x 1
		 '1.5x1' => array(
		   'w' => 1.5,
		   'h' => 1
		   ),		   
		   
		 // 2 x 1
		 '2x1' => array(
		   'w' => 2,
		   'h' => 1
		   ),
		   
		 // 2.5 x 1
		 '2.5x1' => array(
		   'w' => 2.5,
		   'h' => 1
		   ),
		   
		 // 3 x 1
		 '3x1' => array(
		   'w' => 3,
		   'h' => 1
		   ),
		   
		 // 4 x 1
		 '4x1' => array(
		   'w' => 4,
		   'h' => 1
		   ),
		   
		 // 5 x 1 
		 '5x1' => array(
		   'w' => 5,
		   'h' => 1
		   ),
		   
		 // 6 x 1 
		 '6x1' => array(
		   'w' => 6,
		   'h' => 1
		   ),		   
			   		   
	  );
	  return $arr_ratios;
    }
	
	
	/**
	 * 
	 */
	public function add_image_size_do( $arr_args = '') {
	
	  $str_return_source = get_class() . ' > ' . __METHOD__; 
	  
	  // do we have an array with some "stuff" in it
	  if ( WP_ezMethods::array_pass($arr_args) ){
	  
	    $arr_ais_defaults = $this->add_image_size_defaults();
		$arr_resize_ratios = $this->resize_ratios();
		
		foreach ($arr_args as $key_name => $arr_value){
		
		  // lite "validation"
		  if ( ! isset($arr_value['name']) ){
		    continue;
		  }
		  
		  // no ratio? ratio blank? custom? just use the dimensions 'as is'
		  if ( ! isset($arr_value['ratio']) || $arr_value['ratio'] == '' || $arr_value['ratio'] == 'custom' ) {
		  
		    $arr_value = array_merge($arr_ais_defaults, $arr_value );				  
			$width = $arr_value['width'];
			$height = $arr_value['height'];
				  
		  // is the requested ratio legit?
		  } elseif ( isset($arr_resize_ratios[$arr_value['ratio']]) ) {
		    
			// we know the ratio key is good, now get its 'w' and 'h'
			$arr_ratio = $arr_resize_ratios[$arr_value['ratio']];
			
			$arr_value = array_merge($arr_ais_defaults, $arr_value );				  
			
			// default 'land' else 
			$str_orientation = 'land';
			if ( $arr_value['orientation'] == 'land' || $arr_value['orientation'] == 'port' ) {
			  $str_orientation = $arr_value['orientation'];
			}
			
			// default is 0 else
			$num_offset = 0;
			if ( isset($arr_value['offset']) ) {
			  $num_offset = (float) $arr_value['offset'];
			}
			
			// width is our default / GOTO / baseline dimension. we always start with width.
			if ( isset($arr_value['width']) && $arr_value['width'] !== false ){
			
			  $width = (float) $arr_value['width'];
			  // apply the offset
			  $width =  $width + ( $width * $num_offset );
			  $height = $width * ( $arr_ratio['h'] / $arr_ratio['w']);
				
			  if ( $str_orientation == 'port' ){
				$height = $width * ( $arr_ratio['w'] / $arr_ratio['h'] );
		      }
				
			  // if no width then we'll use the height. yeah, weird use case but at least it's hese if you want it
			} elseif ( isset($arr_value['height']) && $arr_value['height'] !== false ) {
			  
			  $height = (float) $arr_value['height'];	  
		      $width = $height * ( $arr_ratio['w'] / $arr_ratio['h']);
			  
			  if ( $str_orientation == 'port' ){
				$width = $height * ( $arr_ratio['h'] / $arr_ratio['w']);
			  }
			} else {
			  // something ain't right...next!
			  continue;
			}
		  } else {
		    // something ain't right...next!
		    continue;
		  }
		  
		  $crop = (bool)$arr_value['crop'];
		  
		  // now is the moment of truth.. thumbnail or add_image_size()?
		  if ( isset($arr_value['post_thumbnail']) && $arr_value['post_thumbnail'] === true ){
		  
		    // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
			set_post_thumbnail_size( absint($width) , absint($height) , $crop);
		  } else {
		  
		    add_image_size( $arr_value['name'], absint($width), absint($height), $crop );
		  }
		}
		return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
	  }
	  return array('status' => false, 'msg' => 'ERROR: add_image_size_do() > $arr_args is not valid', 'source' => $str_return_source, 'arr_args' => 'error');
	}
	
	
	/**
	 * If true then zap the current image sizes and just use these new ones. Else (false) append the new ones
	 */
    public function set_image_size_names_choose_defaults( $bool_flag = true ){
	
	  $this->_bool_isnc_defaults = true;
	  if ( $bool_flag === false ){
		$this->_bool_isnc_defaults = false;
	  }
	}
		
	/**
	 * filter the select list for image_size_names_choose
	 */
	public function filter_image_size_names_choose($arr_sizes){
	
	  $arr_add_sizes = array();
	  foreach ( $this->_arr_isnc as $key_name => $arr_value ){
	    if ( isset($arr_value['names_choose']) && isset($arr_value['names_choose']['active']) && $arr_value['names_choose']['active'] === true && isset($arr_value['names_choose']['select']) ){
		  $arr_add_sizes[$arr_value['name']] = $arr_value['names_choose']['select'];  
	    }
	  }
	  $arr_newsizes = array_merge($arr_sizes, $arr_add_sizes);
	  
	  // true = yes, include. false = no, remove
	  if ( $this->_bool_isnc_defaults === false) {
	  
	    foreach ( $this->image_size_names_choose_defaults() as $str_key => $bool_value ){
		  if ( $bool_value === true ){
		    unset( $arr_newsizes[$str_key] );
		  }
		}
	  }

	  return $arr_newsizes;
	}
	
	/**
	 * current standard default image sizes
	 */
	public function image_size_names_choose_defaults(){
	
		return array(
		  'full'		=> true,
		  'thumbnail' 	=> true,
		  'medium'		=> true,
		  'large'		=> true
		);
	}
	
	
	/**
	 * 
	 */
    public function set_jpeg_quality( $int_jq = 90 ){
	
	  $int_jq = absint($int_jq);
	  if ($int_jq >= 0 && $int_jq <=100){
		$this->_int_jpeg_quality = $int_jq;
	  }
	}
	
	/*
	 * callback for the filter: jpeg_quality
	 */
	public function filter_jpeg_quality($int_jq = ''){
	
	  return absint($this->_int_jpeg_quality);
	}
	
	/**
	 * If you want to remove the width and height then you need to set _remove_width_height to true
	 */
    public function set_remove_width_height( $bool_flag = false ){
	  
	  $this->_bool_remove_width_height = false;
	  if ( $bool_flag === true ){
		$this->_bool_remove_width_height = true;
	  }	  
	}
	
	/**
	 *
	 */
	public function filter_remove_width_height_attributes( $str_html = '' ) {
	
	  if ( $this->_bool_remove_width_height === true ){
	    $str_html = preg_replace( '/(width|height)="\d*"\s/', "", $str_html );
	  }
	  return $str_html;
	}
	

	} // END: class
} // END: if class exists