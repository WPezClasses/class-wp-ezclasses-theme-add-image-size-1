<?php
/** 
 * Automation and simplification of WP's set_post_thumbnail_size() and add_image_size()
 *
 * TODO - Long Desc 
 *
 * PHP version 5.3
 *
 * LICENSE: TODO
 *
 * @package WP ezClasses
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.0
 * @license TODO
 */
 
 /*
 * == Change Log == 
 *
 * --- 
*/

// No WP? Die! Now!!
if (!defined('ABSPATH')) {
	header( 'HTTP/1.0 403 Forbidden' );
    die();
}

if ( ! class_exists('Class_WP_ezClasses_Theme_Add_Image_Size_1') ) {
  class Class_WP_ezClasses_Theme_Add_Image_Size_1 extends Class_WP_ezClasses_Master_Singleton{
  
    protected $_arr_init;
	
		protected $_bool_validate_only_active_true;
			
		public function __construct() {
			parent::__construct();
		}
		
		public function ezc_init($arr_args = ''){
		
		  $arr_init_defaults = $this->init_defaults();
		
		  $this->_arr_init = WP_ezMethods::ez_array_merge(array($arr_init_defaults, $arr_args));
		}
		
	protected function init_defaults(){
	
	  $arr_defaults = array(
	    'active' 		=> true,
		'active_true'	=> true,
		'filters' 		=> false,	// currently NA but let's leave it for now
		'validation' 	=> false,
		'arr_args'		=> array(),
        ); 
	  return $arr_defaults;
	}
	
	
		/**
		* 
		*/ 
		public function ez_ais( $arr_args = '' ){
		
		  if ( ! WP_ezMethods::array_pass($arr_args) ){
		    return array('status' => false, 'msg' => 'ERROR: arr_args is not valid', 'source' => get_class() . ' ' . __METHOD__, 'arr_args' => 'error');
		  }
		  
		  $arr_args = WP_ezMethods::ez_array_merge(array( $this->_arr_init, $arr_args)); 
		
			if ( $arr_args['active'] === true && WP_ezMethods::array_pass($arr_args['arr_args']) ){

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
				
				/*
				 * At this point we should be good to go.
				 */ 			
				
				// do
				return $this->add_image_size_do($arr_add_image_size);
				
			}
		}	
		

		/**
		 *
		 */
		 /*
		protected function ezc_image_array_validate($arr_args = NULL){
			$str_return_source = 'Theme \ Images ::  ezc_image_array_validate()'; 

			// Note: "global" property $_bool_ezc_validate is *not* checked here. 

			if ( !is_array($arr_args) || empty($arr_args)){
				return array('status' => false, 'msg' => 'ERROR: arr_args !is_array() || empty()', 'source' => $str_return_source, 'arr_args' => 'error');
			}
			
			$arr_msg = array();
			foreach ($arr_args as $str_key => $arr_value){
			
				$arr_msg_detail = $this->ezc_image_single_validate($arr_value);
				if (!empty($arr_msg_detail)){
					$arr_msg[$str_key] = $arr_msg_detail;
				}
			}
			
			if ( empty($arr_msg) ){
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source,'arr_args' => $arr_args);
			} else {
				return array('status' => false, 'msg' => $arr_msg, 'source' => $str_return_source, 'arr_args' => 'error');
			}
		}
		*/
		
		
		/**
		 *
		 */
		 /*
		protected function ezc_image_single_validate($arr_value = NULL){
			$str_return_source = 'Theme \ Images ::  ezc_image_single_validate()'; 

			// Note: "global" property $_bool_ezc_validate is *not* checked
			
			$arr_msg_detail = array();
			if ( is_array($arr_value) ){
			
				// what is bool_validate_only_active_true vs the status of the current row?
				if ( $this->_bool_validate_only_active_true !== false && isset($arr_value['active']) && $arr_value['active'] !== true ){
					return;
				}
				
				if ( !isset($arr_value['active']) || !isset($arr_value['width']) || !isset($arr_value['height']) || !isset($arr_value['crop']) ){
					$arr_msg_detail[] = 'ERROR: arr_args[] -> status, width, height and/or crop - !isset()';
				}
				
				if ( isset($arr_value['active']) && !is_bool($arr_value['active'])) {
					$arr_msg_detail[] = 'ERROR: arr_arg[status] !is_bool(). ';
				}
				if ( isset($arr_value['width']) && !filter_var($arr_value['width'], FILTER_VALIDATE_INT) ) {
					$arr_msg_detail[] = 'ERROR: arr_arg[width] !filter_var(FILTER_VALIDATE_INT)';
				}
				if ( isset($arr_value['height']) && !filter_var($arr_value['height'], FILTER_VALIDATE_INT) ) {
					$arr_msg_detail[] = 'ERROR: arr_arg[height] !filter_var(FILTER_VALIDATE_INT)';
				}
				if ( isset($arr_value['crop']) && !is_bool($arr_value['crop']) ) {
					$arr_msg_detail[] = 'ERROR: arr_arg[crop] !is_bool()';
				}
			} else {
				$arr_msg_detail[] = 'ERROR: value !is_array()';
			}

			return $arr_msg_detail;
		}
		
		*/

/*
 * ===============================================================================================
 * ==> set_post_thumbnail_size
 * ===============================================================================================
 */	

		/**
		 *
		 */
		 /*
		public function set_post_thumbnail_size_validate($arr_args = NULL) {
			
			if ($this->_bool_ezc_validate !== false){
				return $this->ezc_image_array_validate($arr_args);
			}
		} 
		*/
		
		/**
		 * 
		 */	
		 /*
		public function set_post_thumbnail_size_active_true($arr_args = NULL) {
			$str_return_source = 'Theme \ Images :: set_post_thumbnail_size_active_true()'; 
		
			if ( is_array($arr_args) ) {
			
				$arr_active_true = array();
				foreach ($arr_args as $str_key => $arr_value){
					if (  isset($arr_value['active']) && $arr_value['active'] === true ) {
						$arr_active_true[$str_key] = $arr_value;	
					}
				}	
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_active_true);
			}
			return array('status' => false, 'msg' => 'ERROR: arr_args !is_array()', 'source' => $str_return_source, 'arr_args' => 'error');
		}
		*/
		

		/**
		 * 
		 */	
/*		 
		public function set_post_thumbnail_size_do() {
			$str_return_source = 'Theme \ Images :: set_post_thumbnail_size_do()'; 
		
			if ( isset($this->_arr_set_post_thumbnail_size) && !empty($this->_arr_set_post_thumbnail_size) ){
			
				// if there are multiple keys then we only want the first one. http://php.net/manual/en/function.reset.php
				$arr_set_post_thumbnail_size = reset($this->_arr_set_post_thumbnail_size);	

				set_post_thumbnail_size( $arr_set_post_thumbnail_size['width'], $arr_set_post_thumbnail_size['height'], $arr_set_post_thumbnail_size['crop'] );

				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $this->_arr_set_post_thumbnail_size);		
			}
			return array('status' => false, 'msg' => 'ERROR: $_arr_set_post_thumbnail_size !isset() || is_empty()', 'source' => $str_return_source, 'arr_args' => 'error');		
		}
		/*


/*
 * ===============================================================================================
 * ==> add_image_size
 * ===============================================================================================
 */	
 
 		/**
		 * 
		 */
		 /* TODO
		public function add_image_size_validate($arr_args = NULL) {

			if ($this->_bool_ezc_validate !== false){
				return $this->ezc_image_array_validate($arr_args);
			}
		}
		*/
		 
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
		public function add_image_size_do( $arr_args = '') {
			$str_return_source = get_class() . ' > ' . __METHOD__; 

			if ( WP_ezMethods::array_pass($arr_args) ){
			
				foreach ($arr_args as $key_name => $arr_value){
				
				  if ( isset($arr_value['width']) && is_int($arr_value['width']) && isset($arr_value['height']) && is_int($arr_value['height']) && isset($arr_value['crop']) && is_bool($arr_value['crop'])){
				
				    if ( isset($arr_value['post_thumbnail']) && $arr_value['post_thumbnail'] === true ){
				  
				      set_post_thumbnail_size($arr_value['width'],$arr_value['height'], $arr_value['crop']);
					  
				    } else {
					
					  add_image_size( $key_name, $arr_value['width'], $arr_value['height'], $arr_value['crop'] );
				    }
				  }
				}
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
			} 
			return array('status' => false, 'msg' => 'ERROR: add_image_size_do() > $arr_args is not valid', 'source' => $str_return_source, 'arr_args' => 'error');
		}		

	} // END: class
} // END: if class exists