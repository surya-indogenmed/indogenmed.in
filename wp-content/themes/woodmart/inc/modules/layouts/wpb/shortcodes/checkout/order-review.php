<?php
/**
 * Order review shortcode.
 *
 * @package Woodmart
 */

use XTS\Modules\Checkout_Order_Table;
use XTS\Modules\Layouts\Main;
use Automattic\WooCommerce\Internal\Orders\OrderAttributionController;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_shortcode_checkout_order_review' ) ) {
	/**
	 * Order review shortcode.
	 *
	 * @param array $settings Shortcode attributes.
	 */
	function woodmart_shortcode_checkout_order_review( $settings ) {
		if ( ! is_object( WC()->cart ) || 0 === WC()->cart->get_cart_contents_count() ) {
			return '';
		}

		$default_settings = array(
			'css' => '',
		);

		$settings = wp_parse_args( $settings, $default_settings );

		$wrapper_classes = apply_filters( 'vc_shortcodes_css_class', '', '', $settings );

		if ( $settings['css'] ) {
			$wrapper_classes .= ' ' . vc_shortcode_custom_css_class( $settings['css'] );
		}

		if ( Checkout_Order_Table::get_instance()->is_enable_woodmart_product_table_template() ) {
			$wrapper_classes .= ' wd-manage-on';
		}

		ob_start();

		Main::setup_preview();

		?>
		<div class="wd-order-table wd-wpb<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php woocommerce_order_review(); ?>
			
			<?php
			// Render order attribution inputs if feature is enabled.
			if ( FeaturesUtil::feature_is_enabled( 'order_attribution' ) ) {
				$order_attribution_controller = new OrderAttributionController();

				$order_attribution_controller->stamp_html_element();
			}
			?>
		</div>
		<?php

		Main::restore_preview();

		return ob_get_clean();
	}
}
