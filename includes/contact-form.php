<?php

add_shortcode('contact', 'Show_Contact_Form');

add_action('rest_api_init', 'create_rest_endpoint');
add_action('init', 'create_submissions_page');
add_action('add_meta_boxes', 'create_meta_box');
add_action('manage_submission_posts_custom_column','fill_submission_columns',10,2);

add_filter('manage_submission_posts_columns','custom_submission_columns');
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

function enqueue_custom_scripts()
{
      // Enqueue custom css for plugin
      wp_enqueue_style('contact-form-plugin'.' MY_PLUGIN_URL' . 'assets/css/contact-plugin.css');
}




function fill_submission_columns($column,$post_id)
{
    switch ($column)
    {

        case 'name':
            echo get_post_meta($post_id, 'name', true);
        break;
         case 'email':
            echo get_post_meta($post_id, 'email', true);
        break;
         case 'phone':
            echo get_post_meta($post_id, 'phone', true);
        break;
         case 'message':
            echo get_post_meta($post_id, 'message', true);
        break;

     }    
    
}


function custom_submission_columns($columns)
{
    $columns = array(
        'cb' =>$columns['cb'],
        'name' =>__('Name','contact-plugin'),
        'email' =>__('Email','contact-plugin'),
        'phone' =>__('Phone','contact-plugin'),
        'message'=>__('Message','contact-plugin')

    );
    return $columns;
}

function create_meta_box(){
    add_meta_box('custom_contact_form', 'submission', 'display_submission', 'submission');
}


function display_submission()
{
    $postmetas = get_post_meta(get_the_ID());
    unset($postmetas['_edit_lock']);

    echo '<ul>';
    foreach ($postmetas as $key => $value){
    
        echo '<li><strong>' . ucfirst($key) . '</strong>:<br/>' . $value[0] . '</li>';
        
    }
    echo '</ul>';
    
    // echo 'Name:' . get_post_meta(get_the_ID(), 'name', true);

}

function create_submissions_page()
{
    $args = [
        'public' => true,
        'has_atchive' => true,
        'labels' => [
            'name' => 'Submission',
            'singular_name' => 'Submission'
        ],
        'supports'=>false,
        'capability_type'=>'post',
        'capabilities' => array(
            'create_posts' => true,
        ),
        'map_meta_cap'=>true,
       

    ];
    register_post_type('Submission',$args);
}


function Show_Contact_Form(){
    include MY_PLUGIN_PATH .'includes/templates/contact-form.php';
}

function create_rest_endpoint()
{

      // Create endpoint for front end to connect to WordPress securely to post form data
      register_rest_route('v1/contact-form', 'submit', array(

            'methods' => 'POST',
            'callback' => 'handle_enquiry'

      ));
}



function handle_enquiry($data)
 {

  $params = $data->get_params();
     
    // Set fields from the form
      $field_name = sanitize_text_field($params['name']);
      $field_email = sanitize_email($params['email']);
      $field_phone = sanitize_text_field($params['phone']);
      $field_message = sanitize_textarea_field($params['message']);

    if(!wp_verify_nonce($params['_wpnonce'],'wp_rest')){
        return new WP_Rest_Response('Message not send', 422);
    } 

    unset($params['_wpnonce']);
    unset($params['_wp_http_referer']);

    // send data by email address
    $admin_email = get_bloginfo('admin_email');
    $admin_name = get_bloginfo('name');

    $headers = [];

    $headers[] = "Form: {$admin_email} < { $admin_name }> ";
    $headers[] = "Reply to: {$field_name} < { $field_email }>";

    $headers[] = 'Content-Type:text/html';

    $subject = "New enquiary form {$field_name}";

    $message = '';
    $message .='<h3>'. "message has been sent from by ".'</h3>'.'<br />'." {$field_name}";



    $postarr = [
        'post_title' => $params ['name'],
        'post_type' => 'submission'
    ];
    
   $post_id = wp_insert_post($postarr);


    foreach($params as $label => $value)
    {
       $message .= ucfirst($label) . ':' . $value .'<br />';
       add_post_meta($post_id, $label, $value);
    }

    wp_mail($admin_email, $subject, $message, $headers);
    return new WP_Rest_Response('the message send successfully!!', 200);
}