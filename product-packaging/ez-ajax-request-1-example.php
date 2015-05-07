<?php
/**
 * Once again, this is it. This is The ezWay.
 *
 * Class_WP_ezClasses_Ajax_Request_1 handles all the tedious stuff. You just fill in the (_todo) blanks,
 * and then use your time and energy to focus on the custom js and custom method to process the particular request.
 *
 * This is the example form that could be used to make this request.
 *

<div class="col-sm-5">
    <div class="email">
        <div class="email-msg-wrap" style="display:none">
            <span class="email-msg">

            </span>

            <span class="email-msg-close">
                X
            </span>

        </div>

        <form class="ez-ajax-form-submit" data-ezwplocalizename="mcExample">
            <input type="text" name="email" placeholder="Sign up">
            <input type="submit">
            <input type="hidden" name="mtf1" value="website">
        </form>
    </div>
</div>

 *
 *
 * Created by PhpStorm.
 * User: wpezclasses
 * Date: 5/6/2015
 * Time: 9:39 PM
 */



if ( ! class_exists('Class_WP_ezClasses_Ajax_Request_1_Example') ) {

    class Class_WP_ezClasses_Ajax_Request_1_Example extends Class_WP_ezClasses_Ajax_Request_1
    {


        /**
         * Note: Any of the defaults (below) can be TODOs. The TODOs here are the most typical / minimum.
         */
        protected function ajax_todo()
        {

            $arr_todo = array(

                'ajax_js_handle'            => 'ez-ajax-setup-1',           // this should match your ajax-setup-1
                'my_js_handle'              => 'my-ez-ajax-example',       // this should match your ajax-setup-1

                'wp_localize_name'          => 'mcExample',    // notice this value matches data-ezwplocalizename= in the form above
                'args'                      => array(
                    'invalidEmail'         => 'Error: Invalid email',
                    'doingRequest'          => 'Doing Request',
                    'addSuccess'            => 'Success - You\'ve been added!',
                ),

                'nonce_slug'                => 'mailchimp-example-nonce-1',      //

                'ajax_action'               => 'ajax_action_mailchimp_example',

                'wp_ajax_active'            => true,
                'wp_ajax_method'            => 'ajax_method_mailchimp_signup_example',          // obviously you'll have to code this method. this is where your server side magic happens

                'wp_ajax_nopriv_active'     => true,
                'wp_ajax_nopriv_method'     => 'ajax_method_mailchimp_signup_example',

                // IMPORTANT!
                // These are the js functions unique to this request (as found in my-ez-ajax-example.js).
                // ez-ajax-setup-1.js uses these to complete this request. A different request, still
                // using ez-ajax-setup-1.js, would (probably) have different js needs.
                'ajax_before'               => 'ezEmailSubmit',
                'ajax_always'               => false,
                'ajax_done_true'            => 'ezDoneTrueMcSignup',
                'ajax_done_false'           => 'ezDoneFalseMcSignup',
                'ajax_fail'                 => 'ezAjaxFail'
            );
            return $arr_todo;
        }

        /**
         * Once the request is made, this is the method (as defined above) that does the request-centric magic.
         */
        public function ajax_method_mailchimp_signup_example(){

            $arr_init = $this->_arr_init;

            if (!empty($_POST) && isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], $arr_init['nonce'])) {

                parse_str($_POST['data'], $arr_data);

                // mtf1 stands for My Test Field 1. it's a custom field in MC.
                if ( isset($arr_data['email']) && isset($arr_data['mtf1']) ) {

                    if ( filter_var($arr_data['email'], FILTER_VALIDATE_EMAIL) ){

                        /**
                         * IMPORTANT - This class (Class_WP_ezClasses_API_Mailchimp_1_Example) is not included.
                         *
                         * It's not really important in the sense you can do whatever you want with one of these
                         * Ajax Request 1 classes. The purpose of this example is simply to show you the structure
                         * and process, as well as how "automated" The ezWay makes it.
                         */
                 //       $obj_mc = Class_WP_ezClasses_API_Mailchimp_1_Example::ez_new();

                        $arr_parms =  array(
                            "email"         => array("email"=>$arr_data['email']),
                            "merge_vars"    => array('source_1' => $arr_data['mtf1'], 'source_2' => $arr_data['mtf1'])
                        );

                        // fyi - This is the MC API call. Again. This is just an example
                   //     $json_data_mc = $obj_mc->call("lists/subscribe", $arr_parms);

                        $json_data_mc = 'TODO - jam your sample MC json reply here (for now).'

                        // TODO - check for errors here?

                        $bool_status = true;
                        $str_cb = 'ezCbMcSignup';

                    } else {

                        // email address is not valid
                        $bool_status = false;
                        $str_cb = 'n/a';
                        $arr_data_mc = array(
                            'error' => true,
                            'msg'   => 'Email address not valid'
                        );
                        $json_data_mc = json_encode($arr_data_mc);
                    }

                } else {

                    // TODO - error: something is missing.

                }

                $ser_mc_data = serialize($json_data_mc);

                echo json_encode(array('status' => $bool_status, 'cb' => $str_cb, 'data_post' =>  $_POST['data'], 'data_mc' => $ser_mc_data ));
                if (defined('DOING_AJAX') && DOING_AJAX) {
                    die();
                }
            } else {

                echo json_encode(array('status' => false, 'post_data' => $arr_data ));
                if (defined('DOING_AJAX') && DOING_AJAX) {
                    die();
                }
            }
        }

    }
}

Class_WP_ezClasses_Ajax_Request_1_Example::ez_new();