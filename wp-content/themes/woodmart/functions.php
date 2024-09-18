<?php
/**
 *
 * The framework's functions and definitions
 */

define( 'WOODMART_THEME_DIR', get_template_directory_uri() );
define( 'WOODMART_THEMEROOT', get_template_directory() );
define( 'WOODMART_IMAGES', WOODMART_THEME_DIR . '/images' );
define( 'WOODMART_SCRIPTS', WOODMART_THEME_DIR . '/js' );
define( 'WOODMART_STYLES', WOODMART_THEME_DIR . '/css' );
define( 'WOODMART_FRAMEWORK', '/inc' );
define( 'WOODMART_DUMMY', WOODMART_THEME_DIR . '/inc/dummy-content' );
define( 'WOODMART_CLASSES', WOODMART_THEMEROOT . '/inc/classes' );
define( 'WOODMART_CONFIGS', WOODMART_THEMEROOT . '/inc/configs' );
define( 'WOODMART_HEADER_BUILDER', WOODMART_THEME_DIR . '/inc/header-builder' );
define( 'WOODMART_ASSETS', WOODMART_THEME_DIR . '/inc/admin/assets' );
define( 'WOODMART_ASSETS_IMAGES', WOODMART_ASSETS . '/images' );
define( 'WOODMART_API_URL', 'https://xtemos.com/wp-json/xts/v1/' );
define( 'WOODMART_DEMO_URL', 'https://woodmart.xtemos.com/' );
define( 'WOODMART_PLUGINS_URL', WOODMART_DEMO_URL . 'plugins/' );
define( 'WOODMART_DUMMY_URL', WOODMART_DEMO_URL . 'dummy-content-new/' );
define( 'WOODMART_TOOLTIP_URL', WOODMART_DEMO_URL . 'theme-settings-tooltips/' );
define( 'WOODMART_SLUG', 'woodmart' );
define( 'WOODMART_CORE_VERSION', '1.0.43' );
define( 'WOODMART_WPB_CSS_VERSION', '1.0.2' );

if ( ! function_exists( 'woodmart_load_classes' ) ) {
	function woodmart_load_classes() {
		$classes = array(
			'class-singleton.php',
			'class-api.php',
			'class-config.php',
			'class-layout.php',
			'class-autoupdates.php',
			'class-activation.php',
			'class-notices.php',
			'class-theme.php',
			'class-registry.php',
		);

		foreach ( $classes as $class ) {
			require WOODMART_CLASSES . DIRECTORY_SEPARATOR . $class;
		}
	}
}

woodmart_load_classes();

new XTS\Theme();

define( 'WOODMART_VERSION', woodmart_get_theme_info( 'Version' ) );





// Add file upload field conditionally during WooCommerce checkout
add_action('woocommerce_after_checkout_billing_form', 'misha_file_upload_field');
function misha_file_upload_field() {
    $show_field = false;
    // Loop through the cart and check if any product has the 'rx' tag
    foreach (WC()->cart->get_cart() as $cart_item) {
        $product_id = $cart_item['product_id'];
        if (has_term('rx', 'product_tag', $product_id)) {
            $show_field = true;
            break;
        }
    }
    // Show the file upload field only if there's an 'rx' product
    if ($show_field) {
        ?>
        <div class="prescribe-group">
            <div class="d-flex">
                <input type="radio" id="contactChoice1" name="prescribe" value="yes" />
                <label for="contactChoice1">Prescription</label>
            </div>
            <div class="d-flex">
                <input type="radio" id="contactChoice2" name="prescribe" value="no" />
                <label for="contactChoice2">Consult</label>
            </div>
            <div class="form-row form-row-wide prescription-upload" style="display: none;">
                <label for="misha_file"><a>Uploaded Prescription</a></label>
                <input type="file" id="misha_file" name="misha_file" />
                <input type="hidden" name="misha_file_field" />
                <div id="misha_filelist"></div>
            </div>
        </div>
        <?php
    }
}
// AJAX for file upload handling
add_action('wp_ajax_mishaupload', 'misha_file_upload');
add_action('wp_ajax_nopriv_mishaupload', 'misha_file_upload');
function misha_file_upload() {
    $upload_dir = wp_upload_dir();
    if (isset($_FILES['misha_file'])) {
        $path = $upload_dir['path'] . '/' . basename($_FILES['misha_file']['name']);
        if (move_uploaded_file($_FILES['misha_file']['tmp_name'], $path)) {
            echo $upload_dir['url'] . '/' . basename($_FILES['misha_file']['name']);
        }
    }
    die;
}
// Validate the file upload field during checkout process
add_action('woocommerce_checkout_process', 'custom_checkout_field_process');
function custom_checkout_field_process() {
    // Check if 'yes' is selected for prescription
    if ($_POST['prescribe'] === 'yes') {
        if (empty($_POST['misha_file_field'])) {
            wc_add_notice(__('Please upload a prescription file.'), 'error');
        }
        // Validate uploaded file for errors
        if (!empty($_FILES['misha_file']['name'])) {
            if ($_FILES['misha_file']['error'] !== UPLOAD_ERR_OK) {
                wc_add_notice(__('File upload error. Please try again.'), 'error');
            }
        }
    }
    // Ensure that the user selects either "Prescription" or "Consult"
    if (empty($_POST['prescribe'])) {
        wc_add_notice(__('Please select either "Prescription" or "Consult" option.'), 'error');
    }
}
// Save the uploaded file URL or the 'Consult' value in the order meta data
add_action('woocommerce_checkout_update_order_meta', 'misha_save_what_we_added');
function misha_save_what_we_added($order_id) {
    if (!empty($_POST['prescribe']) && ($order = wc_get_order($order_id))) {
        // Save the 'prescribe' field value (either 'yes' or 'no')
        $order->update_meta_data('prescribe_option', sanitize_text_field($_POST['prescribe']));
        // If 'Prescription' is selected, save the file URL
        if ($_POST['prescribe'] === 'yes' && !empty($_POST['misha_file_field'])) {
            $order->update_meta_data('misha_file_field', sanitize_text_field($_POST['misha_file_field']));
        }
        $order->save();
    }
}
// Display prescription or consult value in WooCommerce admin order details
add_action('woocommerce_admin_order_data_after_order_details', 'misha_order_meta_general');
function misha_order_meta_general($order) {
    // Get the 'prescribe' option value (either 'yes' for Prescription or 'no' for Consult)
    $prescribe_option = $order->get_meta('prescribe_option');
    if ($prescribe_option === 'yes') {
        // Display the uploaded prescription file
        $file = $order->get_meta('misha_file_field');
        if ($file) {
            echo '<div style="margin-top:30px !important;"><p ><strong>Prescription:</strong></p>';
            echo '<img src="' . esc_url($file) . '" /> </div>';
        }
    } elseif ($prescribe_option === 'no') {
        // Display that the customer chose 'Consult'
        echo '<p><strong>Consultation:</strong> The customer opted for a consultation instead of uploading a prescription.</p>';
    }
}
// Enqueue jQuery and WC Checkout script
add_action('wp_enqueue_scripts', 'enqueue_my_custom_script');
function enqueue_my_custom_script() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('wc-checkout');
}
// JavaScript to toggle the prescription file upload field based on radio selection
add_action('wp_footer', 'prescription_field_toggle');
function prescription_field_toggle() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($){
        // Toggle the file upload field based on the 'prescribe' radio selection
        $('input[name="prescribe"]').change(function(){
            if ($('#contactChoice1').is(':checked')) { // If 'Prescription' is selected
                $('.prescription-upload').show();
                $('#misha_file').attr('required', true); // Make the file field required
            } else { // If 'Consult' is selected
                $('.prescription-upload').hide();
                $('#misha_file').removeAttr('required'); // Remove required attribute
            }
        });
        // Trigger change event on page load in case 'Consult' is selected by default
        $('input[name="prescribe"]:checked').trigger('change');
        // Validate form before submission
        $('form.checkout').on('submit', function(e){
            if (!$('input[name="prescribe"]:checked').val()) {
                e.preventDefault(); // Prevent form submission
//                 alert('Please select either "Prescription" or "Consult" option.'); // Show an error message
            }
        });
    });
    </script>
    <?php
}