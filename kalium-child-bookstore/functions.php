<?php
/**
 * Kalium WordPress Theme
 *
 * @author Laborator
 * @link   https://kaliumtheme.com
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * After theme setup hooks.
 */
function kalium_child_after_setup_theme() {

	// Load translations for child theme
	load_child_theme_textdomain( 'kalium-child', get_stylesheet_directory() . '/languages' );
}

add_action( 'after_setup_theme', 'kalium_child_after_setup_theme' );

/**
 * This will enqueue style.css of child theme.
 */
function kalium_child_wp_enqueue_scripts() {
	wp_enqueue_style( 'kalium-child', get_stylesheet_directory_uri() . '/style.css' );
}

add_action( 'wp_enqueue_scripts', 'kalium_child_wp_enqueue_scripts', 110 );

/**
 * Filter attributes and show author attribute only.
 *
 * @param array $attributes
 *
 * @return array
 * @see _kalium_show_author_attribute()
 */
function _kalium_woocommerce_product_get_attributes_author_only( $attributes ) {
	$author_attr_name = wc_attribute_taxonomy_name( 'book-author' );
	$author_attribute = [];

	if ( isset( $attributes[ $author_attr_name ] ) ) {
		$author_attribute[ $author_attr_name ] = $attributes[ $author_attr_name ];
	}

	return $author_attribute;
}

/**
 * Show book author attribute.
 */
function _kalium_show_author_attribute() {
	global $product;

	// Add filters to return only author from attributes array
	add_filter( 'woocommerce_product_get_attributes', '_kalium_woocommerce_product_get_attributes_author_only' );
	add_filter( 'wc_product_enable_dimensions_display', '__return_false' );

	// Display author attribute
	wc_display_product_attributes( $product );

	// Remove filters to return only author from attributes array
	remove_filter( 'woocommerce_product_get_attributes', '_kalium_woocommerce_product_get_attributes_author_only' );
	remove_filter( 'wc_product_enable_dimensions_display', '__return_false' );
}

add_action( 'woocommerce_product_meta_start', '_kalium_show_author_attribute', 25 );
add_action( 'kalium_woocommerce_product_loop_after_title', '_kalium_show_author_attribute', 10 );

/**
 * Move categories outside of the products wrapper.
 */
function kalium_woocommerce_remove_categories_from_products_container() {
	remove_action( 'woocommerce_before_shop_loop', 'kalium_woocommerce_maybe_show_product_categories' );
}

add_action( 'woocommerce_init', 'kalium_woocommerce_remove_categories_from_products_container' );
add_action( 'woocommerce_before_main_content', 'kalium_woocommerce_maybe_show_product_categories', 16 );

/**
 * Disable cropping of search thumbnails.
 *
 * @param string $size
 *
 * @return string
 */
function kalium_search_thumbnail_size_large_filter( $size ) {
	return 'large';
}

add_filter( 'kalium_search_thumbnail_size', 'kalium_search_thumbnail_size_large_filter' );

/**
 * Disable masonry for Products on Tabs and Accordions in Homepage.
 */
function _kalium_disable_isotope_for_products_on_tta_tabs_action() {
	?>
    <script>
		jQuery( document ).ready( jQuery.debounce( 100, function ( $ ) {
			var $products = $( '.home .vc_general.vc_tta .products' );

			if ( $products.length && $.isFunction( $.fn.isotope ) ) {
				$products.isotope( 'destroy' );
			}
		} ) );
    </script>
	<?php
}

add_action( 'wp_footer', '_kalium_disable_isotope_for_products_on_tta_tabs_action', 20 );
