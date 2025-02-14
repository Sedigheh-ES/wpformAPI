<?php

if( !defined('ABSPATH') )
{
      die('You cannot be here');
}


use Carbon_Fields\Container;
use Carbon_Fields\Field;

//  Boot Carbon fields into our actula plugin
add_action('after_setup_theme', 'load_carbon_fields');
add_action('carbon_fields_register_fields', 'create_options_page');

function load_carbon_fields(){
    \Carbon_Fields\Carbon_Fields::boot();
}

function create_options_page()
{
         

        Container::make( 'theme_options', __( 'Theme Options' ) )
         ->set_icon( 'dashicons-pets' )
         ->add_fields( array(
         Field::make( 'checkbox', 'contact_plugin_active', 'Active' )
            ->set_option_value( 'yes' ),
       
        Field::make( 'text', 'contact_plugin_recipients', __( 'Recipient Email' ) )
        ->set_attribute( 'placeholder', 'email@gmail.com' )
        ->set_help_text('please enter your email'),

        Field::make( 'textarea', 'contact_plugin_message', __( 'Confirmation  message' ) )
        ->set_attribute( 'placeholder', 'Description' )
        ->set_help_text('please enter your confirmation message')
       
      
    ) );
    
}