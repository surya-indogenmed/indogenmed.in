<?php
/**
 * The template for displaying the footer
 */

if ( woodmart_get_opt( 'collapse_footer_widgets' ) && ( ! woodmart_get_opt( 'mobile_optimization', 0 ) || ( wp_is_mobile() && woodmart_get_opt( 'mobile_optimization' ) ) ) ) {
	woodmart_enqueue_inline_style( 'widget-collapse' );
	woodmart_enqueue_js_script( 'widget-collapse' );
}

$page_id                 = woodmart_page_ID();
$disable_prefooter       = get_post_meta( $page_id, '_woodmart_prefooter_off', true );
$disable_footer_page     = get_post_meta( $page_id, '_woodmart_footer_off', true );
$disable_copyrights_page = get_post_meta( $page_id, '_woodmart_copyrights_off', true );
?>
<?php if ( woodmart_needs_footer() ) : ?>
	<?php if ( ! woodmart_is_woo_ajax() ) : ?>
		</div><!-- .main-page-wrapper --> 
	<?php endif ?>
		</div> <!-- end row -->
	</div> <!-- end container -->

	<?php if ( ! $disable_prefooter && ( 'text' === woodmart_get_opt( 'prefooter_content_type', 'text' ) && woodmart_get_opt( 'prefooter_area' ) || 'html_block' === woodmart_get_opt( 'prefooter_content_type' ) && woodmart_get_opt( 'prefooter_html_block' ) ) ) : ?>
		<?php woodmart_enqueue_inline_style( 'footer-base' ); ?>
		<div class="wd-prefooter<?php echo woodmart_get_old_classes( ' woodmart-prefooter' ); ?>">
			<div class="container">
				<?php if ( 'text' === woodmart_get_opt( 'prefooter_content_type', 'text' ) ) : ?>
					<?php echo do_shortcode( woodmart_get_opt( 'prefooter_area' ) ); ?>
				<?php else : ?>
					<?php echo woodmart_get_html_block( woodmart_get_opt( 'prefooter_html_block' ) ); ?>
				<?php endif; ?>
			</div>
		</div>
	<?php endif ?>

	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) : ?>
		<footer class="footer-container color-scheme-<?php echo esc_attr( woodmart_get_opt( 'footer-style' ) ); ?>">
			<?php if ( ! $disable_footer_page && woodmart_get_opt( 'disable_footer' ) ) : ?>
				<?php woodmart_enqueue_inline_style( 'footer-base' ); ?>
				<?php if ( 'widgets' === woodmart_get_opt( 'footer_content_type', 'widgets' ) ) : ?>
					<?php get_sidebar( 'footer' ); ?>
				<?php else : ?>
					<div class="container main-footer">
						<?php echo woodmart_get_html_block( woodmart_get_opt( 'footer_html_block' ) ); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( ! $disable_copyrights_page && woodmart_get_opt( 'disable_copyrights' ) ) : ?>
				<?php woodmart_enqueue_inline_style( 'footer-base' ); ?>
				<div class="copyrights-wrapper copyrights-<?php echo esc_attr( woodmart_get_opt( 'copyrights-layout' ) ); ?>">
					<div class="container">
						<div class="min-footer">
							<div class="col-left set-cont-mb-s reset-last-child">
								<?php if ( woodmart_get_opt( 'copyrights' ) != '' ) : ?>
									<?php echo do_shortcode( woodmart_get_opt( 'copyrights' ) ); ?>
								<?php else : ?>
									<p>&copy; <?php echo date( 'Y' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>. <?php esc_html_e( 'All rights reserved', 'woodmart' ); ?></p>
								<?php endif ?>
							</div>
							<?php if ( woodmart_get_opt( 'copyrights2' ) != '' ) : ?>
								<div class="col-right set-cont-mb-s reset-last-child">
									<?php echo do_shortcode( woodmart_get_opt( 'copyrights2' ) ); ?>
								</div>
							<?php endif ?>
						</div>
					</div>
				</div>
			<?php endif ?>
		</footer>
	<?php endif ?>
<?php endif ?>
</div> <!-- end wrapper -->
<div class="wd-close-side wd-fill<?php echo woodmart_get_old_classes( ' woodmart-close-side' ); ?>"></div>
<?php do_action( 'woodmart_before_wp_footer' ); ?>
<?php wp_footer(); ?>


<script>
jQuery( function( $ ) {

	$( '#misha_file' ).change( function() {

		if ( ! this.files.length ) {
			$( '#misha_filelist' ).empty();
		} else {

			// we need only the only one for now, right?
			const file = this.files[0];

			$( '#misha_filelist' ).html( '<img src="' + URL.createObjectURL( file ) + '"><span>' + file.name + '</span>' );

			const formData = new FormData();
			formData.append( 'misha_file', file );

			$.ajax({
				url: wc_checkout_params.ajax_url + '?action=mishaupload',
				type: 'POST',
				data: formData,
				contentType: false,
				enctype: 'multipart/form-data',
				processData: false,
				success: function ( response ) {
					$( 'input[name="misha_file_field"]' ).val( response );
				}
			});

		}

	} );

} );

</script>
<script>
jQuery(document).ready(function() {
	var upload = jQuery('.prescription-upload'); 
    upload.hide();

    jQuery('input[name="prescribe"]').change(function() {
        if (jQuery(this).val() === 'yes') {
            upload.slideDown();
        } else {
            upload.slideUp();
        }
    });
});


</script>
</body>
</html>
