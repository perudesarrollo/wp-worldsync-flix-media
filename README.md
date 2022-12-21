# Configuración

## _ instalar plugins o subir por ftp
```sh
woo-custom-product
```

## _ agrega código de configuración en functions.php

```sh
/wp-content/themes/woodmart/functions.php
```

```php
# prefix_custom_product_data_tab_init
add_action('wc_cpdf_init', 'prefix_custom_product_data_tab_init', 10, 0);
if(!function_exists('prefix_custom_product_data_tab_init')) :
   function prefix_custom_product_data_tab_init(){
     $custom_product_data_fields = array();
     /** First product data tab starts **/
     /** ===================================== */
     $custom_product_data_fields['custom_data_1'] = array(
       array(
             'tab_name'    => __('Codigo Marca', 'wc_cpdf'),
       ),
       // Select
       array(
             'id'          => '_myselect',
             'type'        => 'select',
             'label'       => __('Seleccionar proveedor', 'wc_cpdf'),
             'options'     => array(
                 'option_0'  => '---',
                 'option_1'  => 'Flixmedia',
                 'option_2'  => 'World Sync'
             ),
             'description' => __('Seleccionar proveedor', 'wc_cpdf'),
             'desc_tip'    => true,
       ),
	          // Text
	array(
             'id'          => '_distributor',
             'type'        => 'text',
             'label'       => __('Agregar el nombre distribuidor', 'wc_cpdf'),
             'placeholder' => __('Agregar el nombre distribuidor.', 'wc_cpdf'),
             'class'       => 'large',
             'description' => __('Agregar el nombre distribuidor.', 'wc_cpdf'),
             'desc_tip'    => true,
       ),
       array(
             'id'          => '_codimarca1',
             'type'        => 'text',
             'label'       => __('Agregar el nombre del producto', 'wc_cpdf'),
             'placeholder' => __('Agregar el nombre del producto.', 'wc_cpdf'),
             'class'       => 'large',
             'description' => __('Agregar el nombre del producto.', 'wc_cpdf'),
             'desc_tip'    => true,
       ),
       array(
             'id'          => '_codimarca2',
             'type'        => 'text',
             'label'       => __('Agregar el codigo del producto', 'wc_cpdf'),
             'placeholder' => __('Agregar el codigo del producto.', 'wc_cpdf'),
             'class'       => 'large',
             'description' => __('Agregar el codigo del producto.', 'wc_cpdf'),
             'desc_tip'    => true,
       ),
       array(
            'id'          => '_upcean',
            'type'        => 'text',
            'label'       => __('Agregar el codigo de upcean', 'wc_cpdf'),
            'placeholder' => __('Agregar el codigo de UPC_EAN_CODE.', 'wc_cpdf'),
            'class'       => 'large',
            'description' => __('Agregar el codigo de upcean.', 'wc_cpdf'),
            'desc_tip'    => true,
      ),
      array(
            'id'          => '_ccid',
            'type'        => 'text',
            'label'       => __('Codigo de ccid', 'wc_cpdf'),
            'placeholder' => __('Codigo de CATALOG_CODE.', 'wc_cpdf'),
            'class'       => 'large',
            'description' => __('Codigo de ccid.', 'wc_cpdf'),
            'desc_tip'    => true,
      ),
       // Hidden
       array(
             'id'         => '_myhidden',
             'type'       => 'hidden',
             'value'      => 'Hidden Value',
       ),
     );
     return $custom_product_data_fields;
   }

endif;
```

## _ agrega código de js y elementos en producto
```sh
/wp-content/themes/woodmart/woocommerce/single-product/tabs/tabs.php
```

```php
<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

$tabs_layout = woodmart_get_opt( 'product_tabs_layout' ); // accordion tabs

$scroll        = ( $tabs_layout == 'accordion' );
$tab_count     = 0;
$content_count = 0;

woodmart_enqueue_js_script( 'single-product-tabs-accordion' );
woodmart_enqueue_js_script( 'product-accordion' );

if ( comments_open() ) {
	woodmart_enqueue_js_script( 'single-product-tabs-comments-fix' );
	woodmart_enqueue_js_script( 'woocommerce-comments' );
}
global $wc_cpdf;
if ( ! empty( $product_tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper tabs-layout-<?php echo esc_attr( $tabs_layout ); ?>">
		<ul class="tabs wc-tabs">
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<li class="<?php echo esc_attr( $key ); ?>_tab <?php echo $tab_count === 0 ? 'active' : ''; ?>">
					<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $product_tab['title'] ), $key ); ?></a>
				</li>
				<?php $tab_count++; ?>
			<?php endforeach; ?>
		</ul>
		<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
			<div class="wd-tab-wrapper<?php echo woodmart_get_old_classes( ' woodmart-tab-wrapper' ); ?>">
				<a href="#tab-<?php echo esc_attr( $key ); ?>" class="wd-accordion-title<?php echo woodmart_get_old_classes( ' woodmart-accordion-title' ); ?> tab-title-<?php echo esc_attr( $key ); ?> <?php echo $content_count === 0 ? 'active' : ''; ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $product_tab['title'] ), $key ); ?></a>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>">
					<div class="wc-tab-inner 
					<?php
					if ( $scroll ) {
						echo 'wd-scroll';}
					?>
					">
						<div class="<?php echo true == $scroll ? 'wd-scroll-content' : ''; ?>">
							<?php call_user_func( $product_tab['callback'], $key, $product_tab ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php $content_count++; ?>
		<?php endforeach; ?>

		<?php 
        /******************************** */
		// worldsync && flixfacts
        /******************************** */
		if ($wc_cpdf->get_value(get_the_ID(), '_myselect')=='option_1'){
			?>
			<div id="flix-minisite"></div>
			<div id="flix-inpage"></div>
			<script type="text/javascript" src="//media.flixfacts.com/js/loader.js" 
				data-flix-distributor=""
				data-flix-language="pe"
				data-flix-brand="<?= $wc_cpdf->get_value(get_the_ID(), '_codimarca1')?>"
				data-flix-mpn="<?= $wc_cpdf->get_value(get_the_ID(), '_codimarca2')?>"
				data-flix-ean="<?= $wc_cpdf->get_value(get_the_ID(), '_codimarca2')?>"
				data-flix-button="flix-minisite"
				data-flix-inpage="flix-inpage" 
				data-flix-button-image=""
				data-flix-fallback-language="t2"
				data-flix-price="" async ></script>
		<?php }elseif($wc_cpdf->get_value(get_the_ID(), '_myselect')=='option_2'){?>
				<div id="ccs-feature-icons"></div>
				<div id="ccs-inline-content"></div>
				<script type='text/javascript'>
				var ccs_cc_args = ccs_cc_args || [];
				// Mesajil
				ccs_cc_args.push(['cpn', 'CPN']);
				ccs_cc_args.push(['mf', '<?= $wc_cpdf->get_value(get_the_ID(), '_codimarca1')?>']);
				ccs_cc_args.push(['pn', '<?= $wc_cpdf->get_value(get_the_ID(), '_codimarca2')?>']);
				ccs_cc_args.push(['upcean', '<?= $wc_cpdf->get_value(get_the_ID(), '_upcean')?>']);
				ccs_cc_args.push(['ccid', '<?= $wc_cpdf->get_value(get_the_ID(), '_ccid')?>']);
				ccs_cc_args.push(['lang', 'es']);
				ccs_cc_args.push(['market', 'PE']);
				
				(function () {
					var o = ccs_cc_args; o.push(['_SKey', '2f0f25bc']); o.push(['_ZoneId', 'e3c494e31c']);
					var sc = document.createElement('script'); sc.type = 'text/javascript'; sc.async = true;
					sc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.cs.1worldsync.com/jsc/h1ws.js';
					var n = document.getElementsByTagName('script')[0]; n.parentNode.insertBefore(sc, n);
				})();
				</script>
		<?php } ?>
        /******************************** */
		// end worldsync && flixfacts
        /******************************** */
		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>

<?php endif; ?>
```
