<?php
/**
 * Order review map.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Layouts;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;
use XTS\Modules\Checkout_Order_Table;
use Automattic\WooCommerce\Internal\Orders\OrderAttributionController;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 */
class Order_Review extends Widget_Base {
	/**
	 * Classes added to this element's wrapper.
	 *
	 * @var string
	 */
	private $wd_css_classes = 'wd-order-table';

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_checkout_order_review';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Order review', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wd-icon-ch-order-review';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'wd-checkout-elements' );
	}

	/**
	 * Show in panel.
	 *
	 * @return bool Whether to show the widget in the panel or not.
	 */
	public function show_in_panel() {
		return Main::is_layout_type( 'checkout_form' );
	}

	/**
	 * Retrieve the list of scripts the counter widget depended on.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array( 'wc-checkout' );
	}

	/**
	 * Register the widget controls.
	 */
	protected function register_controls() {

		/**
		 * Style tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section(
			'general_style_section',
			array(
				'label' => esc_html__( 'General', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'css_classes',
			array(
				'type'         => 'wd_css_class',
				'default'      => $this->get_element_wrapper_classes(),
				'prefix_class' => '',
			)
		);

		$this->add_control(
			'reviews_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Note: This element have not options', 'woodmart' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
		if ( ! is_object( WC()->cart ) || 0 === WC()->cart->get_cart_contents_count() ) {
			return;
		}

		woocommerce_order_review();

		// Render order attribution inputs if feature is enabled.
		if ( FeaturesUtil::feature_is_enabled( 'order_attribution' ) ) {
			$order_attribution_controller = new OrderAttributionController();

			$order_attribution_controller->stamp_html_element();
		}
	}

	/**
	 * Add the required classes to this element's wrapper.
	 *
	 * @return string
	 */
	private function get_element_wrapper_classes() {
		if ( Checkout_Order_Table::get_instance()->is_enable_woodmart_product_table_template() ) {
			$this->wd_css_classes .= ' wd-manage-on';
		}

		return $this->wd_css_classes;
	}
}

Plugin::instance()->widgets_manager->register( new Order_Review() );
