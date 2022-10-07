<?php
/*
 * Plugin Name: WooCommerce Custom Product 
 * 
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly


// Required minimum version of WordPress.
if(!function_exists('wc_productdata_options_wp_requred')){
  function wc_productdata_options_wp_requred(){
    global $wp_version;
    $plugin = plugin_basename(__FILE__);
    $plugin_data = get_plugin_data(__FILE__, false);

    if(version_compare($wp_version, "3.3", "<")){
      if(is_plugin_active($plugin)){
        deactivate_plugins($plugin);
        wp_die("'".$plugin_data['Name']."' requires WordPress 3.3 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress Admin</a>.");
      }
    }
  }
  add_action('admin_init', 'wc_productdata_options_wp_requred');
}



// Checks if the WooCommerce plugins is installed and active.
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){

  require_once dirname( __FILE__ ) . '/class-wc-product-data-fields.php';

}
