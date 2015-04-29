<?php
/**
 * Once you have your ajax-setup in place, this is how you can add on the various ajax-requests to that foundation.
 *
 * PHP version 5.3
 *
 * LICENSE: TODO
 *
 * @package WPezClasses
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.0
 */

/**
 * CHANGE LOG
 *
 */

/**
 * -- TODO --
 *
 * - https://gist.github.com/jtsternberg/2445968b4f50b654c923
 * - https://thomasgriffin.io/a-creative-approach-to-efficient-and-scalable-wordpress-api-endpoints/
 * - http://torquemag.io/improved-wordpress-front-end-ajax-processing/
 */

if ( ! class_exists('Class_WP_ezClasses_Ajax_Request_1') ) {

    class Class_WP_ezClasses_Ajax_Request_1 extends Class_WP_ezClasses_Master_Singleton
    {

        protected $_version;
        protected $_url;
        protected $_path;
        protected $_path_parent;
        protected $_basename;
        protected $_file;

        protected $_arr_init;

        protected function __construct()
        {
        }

        public function ez__construct()
        {

            $this->setup();

            $this->_arr_init = WPezHelpers::ez_array_merge(array($this->init_defaults(), $this->ajax_todo()));

            $this->_arr_init['nonce'] = $this->ajax_nonce_todo();

            // NOTE: We're not actually enqueue'ing any scripts, just using the action to do wp_localize_script() for this request
            if ($this->_arr_init['wp_enqueue_scripts_active']) {
                add_action('wp_enqueue_scripts', array($this, 'ajax_wp_localize_script'), $this->_arr_init['wp_enqueue_scripts_priority']);
            }
            if ($this->_arr_init['admin_enqueue_scripts_active']) {
                add_action('admin_enqueue_scripts', array($this, 'ajax_wp_localize_script'), $this->_arr_init['admin_enqueue_scripts_priority']);
            }

            add_action('init', array($this, 'ajax_init'));

        }




        /**
         * Setting up the add_action()s for processing the request.
         */
        public function ajax_init()
        {

            $arr_init = $this->_arr_init;

            if ($arr_init['ajax_active'] === true) {

                if ( $arr_init['wp_ajax_active'] === true && !empty($arr_init['ajax_action']) && !empty($arr_init['wp_ajax_method']) ) {

                    $str_ajax_action = $arr_init['ajax_action'];
                    $str_ajax_method = $arr_init['wp_ajax_method'];

                    if ( method_exists($this, $str_ajax_method ) ) {
                        add_action('wp_ajax_' . $str_ajax_action, array($this, $str_ajax_method));
                    }

                }

                if ( $arr_init['wp_ajax_nopriv_active'] === true && !empty($arr_init['ajax_action']) && !empty($arr_init['wp_ajax_nopriv_method']) ) {

                    $str_ajax_action = $arr_init['ajax_action'];
                    $str_ajax_method = $arr_init['wp_ajax_nopriv_method'];

                    if ( method_exists($this, $str_ajax_method ) ) {
                        add_action('wp_ajax_nopriv_' . $str_ajax_action, array($this, $str_ajax_method));
                    }

                }
            }

        }


        /**
         * Note: Any of the defaults (below) can be TODOs. The TODOs here are the most typical / minimum.
         */
        protected function ajax_todo()
        {

            $arr_todo = array(

                'ajax_js_handle'            => 'ez-ajax-setup-1',           //  * IMPORTANT * this should match your ajax-setup-1 - need to know because of additional script localizing here
                'my_js_handle'              => 'my-ez-ajax',                //  * IMPORTANT * this should match your ajax-setup-1 - need to know because of additional script localizing here

                'wp_localize_name'          => 'ajaxTest',                  // in your markup data-{ ajax-setup-1 > data_wp_localize_name} = this value. the js looks at this data attribute and then uses that wp_localize name

                'nonce_slug'                => 'ajax-request-1-nonce',      //

                'ajax_action'               => 'wp_ezclasses_ajax_action',

                'wp_ajax_active'            => true,
                'wp_ajax_method'            => 'ajax_demo_method',      // obviously you'll have to code this method. this is where your server side magic happens

                'wp_ajax_nopriv_active'     => true,
                'wp_ajax_nopriv_method'     => 'ajax_demo_method',      // obviously you'll have to code this method. this is where your server side magic happens

                'ajax_before'               => 'beforeAjax',            // name of the js function to call prior to making a request. returns a bool for whether the request should continue or not
                'ajax_always'               => 'alwaysAjax',
                'ajax_done_true'            => 'doneAjaxTrue',
                'ajax_done_false'           => 'doneAjaxFalse',
                'ajax_fail'                 => 'failAjax'

            );
            return $arr_todo;
        }


        /**
         * in the event you want something more elaborate for the nonce action/name, this is where you
         * can make that magic happen
         */
        protected function ajax_nonce_todo()
        {
            return $this->_arr_init['nonce_slug'];

        }


        public function init_defaults()
        {

            $str_protocol = isset( $_SERVER["HTTPS"] ) ? 'https://' : 'http://';

            $arr_defaults = array(

                'wp_enqueue_scripts_active'         => true,
                'wp_enqueue_scripts_priority'       => 20,              // make sure this is after the priority in ajax-setup
                'admin_enqueue_scripts_active'      => true,
                'admin_enqueue_scripts_priority'    => 20,              // make sure this is after the priority in ajax-setup

                'ajax_js_handle'            => 'ez-ajax-setup-1',       // this should match your ajax-setup-1
                'my_js_handle'              => 'TODO-MY-JS-HANDLE',     // this should match your ajax-setup-1

                'wp_localize_name'          => 'ajaxTest',              // in your markup data-{ ajax-setup-1 > data_wp_localize_name} = this value. the js looks at this data attribute and then uses that wp_localize name

                'nonce_slug'                => 'ajax-request-1-nonce',  //

                // ------
                'ajax_active'               => true,                    // the master switch
                'ajax_action'               => 'wp_ezclasses_ajax_action',

                'wp_ajax_active'            => true,
                'wp_ajax_method'            => 'ajax_demo_method',      // obviously you'll have to code this method. this is where your server side magic happens

                'wp_ajax_nopriv_active'     => true,
                'wp_ajax_nopriv_method'     => 'ajax_demo_method',      // obviously you'll have to code this method. this is where your server side magic happens
                // -------

                'ajax_before'                       => 'beforeAjax',            // name of the function to call prior to making a request. returns a bool for whether the request should continue or not
                'ajax_before_bool_default'          => true,                    // if the ajax_before() function is undefined, what is the default for continuing to do the request?
                'ajax_always'                       => 'alwaysAjax',
                'ajax_done_bool_default'            => false,
                'ajax_done_true'                    => 'doneAjaxTrue',
                'ajax_done_false'                   => 'doneAjaxFalse',
                'ajax_fail'                         => 'failAjax',

                'ajax_search_empty'                 => 'searchEmptyAjax',
                'search_keyup_timeout_duration'     => 1500,                    // used only for search

                // ------
                'ajax_url'                  => admin_url("admin-ajax.php", $str_protocol),
                'ajax_type'                 => 'POST',
                'ajax_data_type'            => 'json',
                'response_format'           => 'json',

            );

            return $arr_defaults;
        }


        /**
         *
         */
        protected function ajax_request_localize()
        {

            $arr_init = $this->_arr_init;

            $arr_localize = array(

                'ajax_before'                       => $arr_init['ajax_before'],
                'ajax_before_bool_default'          => $arr_init['ajax_before_bool_default'],
                'ajax_always'                       => $arr_init['ajax_always'],
                'ajax_done_bool_default'            => $arr_init['ajax_done_bool_default'],
                'ajax_done_true'                    => $arr_init['ajax_done_true'],
                'ajax_done_false'                   => $arr_init['ajax_done_false'],
                'ajax_fail'                         => $arr_init['ajax_fail'],
                // search specific
                'ajax_search_empty'                 => $arr_init['ajax_search_empty'],
                'search_keyup_timeout_duration'     => $arr_init['search_keyup_timeout_duration'],

                'ajax_url'                  => $arr_init['ajax_url'],
                'ajax_type'                 => $arr_init['ajax_type'],
                'ajax_data_type'            => $arr_init['ajax_data_type'],
                'action'                    => $arr_init['ajax_action'],
                'nonce'                     => wp_create_nonce($arr_init['nonce']),


            );

            return $arr_localize;
        }


        /**
         * Copy this into your class (that inherits this class), and remove the comments wrapper. Run / test and then refactor as you see fit.
         */
/*
        public function ajax_demo_method($bool_return_str = false)
        {

            $arr_init = $this->_arr_init;

            if (!empty($_POST) && isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], $arr_init['nonce'])) {

                $post_data = $_POST['data'];

                echo json_encode(array('status' => true, 'cb' => 'demo_callback', 'cb_data' => 'callback data', 'post_data' => $post_data));
                if (defined('DOING_AJAX') && DOING_AJAX) {
                    die();
                }
            } else {

                echo json_encode(array('status' => false, 'post_data' => $post_data ));
                if (defined('DOING_AJAX') && DOING_AJAX) {
                    die();
                }
            }
        }
*/


        /**
         *
         */
         public function ajax_wp_localize_script(){

             $arr_init = $this->_arr_init;

             $str_handle = $arr_init['ajax_js_handle'];
             $str_script_is = 'enqueued';
             if ( wp_script_is( $arr_init['my_js_handle'], $str_script_is ) ) {
                 $str_handle = $arr_init['my_js_handle'];
             }

             wp_localize_script(
                 $str_handle,
                 $arr_init['wp_localize_name'],                       // DO NOT CHANGE. the .js expects this name.
                 $this->ajax_request_localize()
                );
         }


        /**
         * The usual suspects
         */
        protected function setup()
        {
            $this->_version = '0.5.0';
            $this->_url = plugin_dir_url(__FILE__);
            $this->_path = plugin_dir_path(__FILE__);
            $this->_path_parent = dirname($this->_path);
            $this->_basename = plugin_basename(__FILE__);
            $this->_file = __FILE__;
        }

    }
}