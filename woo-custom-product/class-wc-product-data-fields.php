<?php

if (!defined('ABSPATH')) exit;

/**
 * @class 		WC_Product_Data_Fields
 * @version		1
 * @category	Class
 * @author 		Et
 */

if (!class_exists('WC_Product_Data_Fields')) {

  class WC_Product_Data_Fields
  {
    public static $plugin_prefix;
    public static $plugin_url;
    public static $plugin_path;
    public static $plugin_basefile;
    /**
     * Constructor
     */
    public function __construct()
    {

      WC_Product_Data_Fields::$plugin_prefix = 'wc_productdata_options_';
      WC_Product_Data_Fields::$plugin_basefile = plugin_basename(__FILE__);
      WC_Product_Data_Fields::$plugin_url = plugin_dir_url(WC_Product_Data_Fields::$plugin_basefile);
      WC_Product_Data_Fields::$plugin_path = trailingslashit(dirname(__FILE__));
      add_action('woocommerce_init', array(&$this, 'init'));
    }
    /**
     * Gets saved data
     * It is used for displaying the data value in template file
     * @return array
     */
    public function get_value($post_id, $field_id)
    {
      $meta_value = get_post_meta($post_id, 'wc_productdata_options', true);
      $meta_value = $meta_value[0];
      return (isset($meta_value[$field_id])) ? $meta_value[$field_id] : '';
    }

    /**
     * Init WooCommerce Custom Product Data Fields extension once we know WooCommerce is active
     * @return void
     */
    public function init()
    {
      add_action('woocommerce_product_write_panel_tabs', array($this, 'product_write_panel_tab'));
      add_action('woocommerce_product_write_panels', array($this, 'product_write_panel'));
      add_action('woocommerce_process_product_meta', array($this, 'product_save_data'), 10, 2);
    }

    /**
     * Create fields via hook
     * @return null if no hook applied
     */
    public function wc_cpdf_fields()
    {
      return apply_filters('wc_cpdf_init', null);
    }
    /**
     * Adds a new tab to the Product Data postbox in the admin product interface
     * @return string
     */
    public function product_write_panel_tab()
    {
      $fields = $this->wc_cpdf_fields();
      if ($fields == null) {
        return;
      }
      foreach ($fields as $key => $fields_array) {
        foreach ($fields_array as $field) {
          if (isset($field['tab_name']) && $field['tab_name'] != '') {
            $href = "#" . $key;
            echo "<li class=" . $key . "><a href=" . $href . ">" . $field['tab_name'] . "</a></li>";
          }
        }
      }
    }
    /**
     * Adds the panel to the Product Data postbox in the product interface
     *
     * @return string
     */
    public function product_write_panel()
    {
      global $post;
      // Pull the field data out of the database
      $available_fields = array();
      $available_fields[] = maybe_unserialize(get_post_meta($post->ID, 'wc_productdata_options', true));
      if ($available_fields) {
        foreach ($available_fields as $available_field) {
          $fields = $this->wc_cpdf_fields();
          if ($fields == null) {
            return;
          }
          foreach ($fields as $key => $fields_array) {
            echo '<div id="' . $key . '" class="panel woocommerce_options_panel wc_cpdf_tab">';
            foreach ($fields_array as $field) {
              if (!isset($field['tab_name'])) {
                WC_Product_Data_Fields::wc_product_data_options_fields($field);
              }
            }
            echo '</div>';
          }
        }
      }
    }
    /**
     * Create Fields
     * @param $field array
     * @return string
     */
    public function wc_product_data_options_fields($field)
    {
      global $thepostid, $post, $woocommerce;
      $fieldtype = isset($field['type']) ? $field['type'] : '';
      $field_id = isset($field['id']) ? $field['id'] : '';
      $thepostid = empty($thepostid) ? $post->ID : $thepostid;
      $options_data = maybe_unserialize(get_post_meta($thepostid, 'wc_productdata_options', true));
      switch ($fieldtype) {
        case 'text':
          $thepostid              = empty($thepostid) ? $post->ID : $thepostid;
          $field['placeholder']   = isset($field['placeholder']) ? $field['placeholder'] : '';
          $field['class']         = isset($field['class']) ? $field['class'] : 'short';
          $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
          $field['value']         = isset($field['value']) ? $field['value'] : get_post_meta($thepostid, $field['id'], true);
          $field['name']          = isset($field['name']) ? $field['name'] : $field['id'];
          $field['type']          = isset($field['type']) ? $field['type'] : 'text';
          $inputval = isset($options_data[0][$field_id]) ? $options_data[0][$field_id] : '';
          echo '<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '"><label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label><input type="' . esc_attr($field['type']) . '" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($inputval) . '" placeholder="' . esc_attr($field['placeholder']) . '"' . (isset($field['style']) ? ' style="' . $field['style'] . '"' : '') . ' /> ';
          if (!empty($field['description'])) {
            if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
              echo '<img class="help_tip" data-tip="' . esc_attr($field['description']) . '" src="' . esc_url(WC()->plugin_url()) . '/assets/images/help.png" height="16" width="16" />';
            } else {
              echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
            }
          }
          echo '</p>';
          break;

        case 'select':
          $thepostid           = empty($thepostid) ? $post->ID : $thepostid;
          $field['class']     = isset($field['class']) ? $field['class'] : 'select short';
          $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
          $field['value']     = isset($options_data[0][$field_id]) ? $options_data[0][$field_id] : '';
          echo '<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '"><label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label><select id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" class="' . esc_attr($field['class']) . '">';
          foreach ($field['options'] as $key => $value) {
            echo '<option value="' . esc_attr($key) . '" ' . selected(esc_attr($field['value']), esc_attr($key), false) . '>' . esc_html($value) . '</option>';
          }
          echo '</select> ';
          if (!empty($field['description'])) {
            if (isset($field['desc_tip']) && false !== $field['desc_tip']) {
              echo '<img class="help_tip" data-tip="' . esc_attr($field['description']) . '" src="' . esc_url(WC()->plugin_url()) . '/assets/images/help.png" height="16" width="16" />';
            } else {
              echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
            }
          }
          echo '</p>';
          break;
        case 'hidden':
          $thepostid = empty($thepostid) ? $post->ID : $thepostid;
          $field['value'] = isset($field['value']) ? $field['value'] : $options_data[0][$field_id];
          $field['class'] = isset($field['class']) ? $field['class'] : '';
          echo '<input type="hidden" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['id']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) .  '" /> ';
          break;
        case 'divider':
          echo '<hr class="divider" />';
          break;
      }
    }
    /**
     * @param int $post_id the post (product) identifier
     * @param stdClass $post the post (product)
     * @return void
     */
    public function product_save_data($post_id, $post)
    {
      $options_value = array();
      $data_args = array();
      $fields = $this->wc_cpdf_fields();
      if ($fields == null) {
        return;
      }
      foreach ($fields as $key => $fields_array) {
        foreach ($fields_array as $data) {
          $data_args[$data['id']] = maybe_unserialize($_POST[$data['id']]);
        }
      }
      $options_value[] = $data_args;
      // save the data to the database
      update_post_meta($post_id, 'wc_productdata_options', $options_value);
    }
  }
}
$wc_cpdf = new WC_Product_Data_Fields();