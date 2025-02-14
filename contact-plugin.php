<?php 
/**
 * 
 * Plugin name: Contact Plugin
 * Description: This is my test contact API plugin
 * Version: 1.0.0
 * Text Domain: contact-plugin
 * 
 */

 if(!defined('ABSPATH')){
    die('plugin access denied');
 }


//  If it actually exist already 
if (!class_exists('ContactPlugin')) {
    class ContactPlugin
    {
        public function __construct()
        {
            define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
            require_once(MY_PLUGIN_PATH.'/vendor/autoload.php');
        }
        public function initialize()
         {
            include_once(MY_PLUGIN_PATH .'includes/untilities.php');
            include_once(MY_PLUGIN_PATH .'includes/options-page.php');
            include_once(MY_PLUGIN_PATH .'includes/contact-form.php');
         }

    }
   $ContactPlugin = new ContactPlugin;
    $ContactPlugin->initialize();
}