<?php
/**
 * The template for displaying products on the Recommendation page.
 *
 * @version 1.0.3
 */

defined( 'ABSPATH' ) || exit;

$free_version_installed_class = $free_version_installed || $pro_version_installed ? 'disabled' : '';
$pro_version_installed_class  = $pro_version_installed ? 'disabled' : '';
$product_class                = $pro_version_installed ? 'readonly' : '';
?>

<div class="wpfcs-product <?php echo esc_attr( $product_class ) ?>">
	<?php if ( isset( $product_data['icon_url'] ) ): ?>
		<div class="wpfcs-product-img-wrapper">
			<a href="<?php echo esc_url( $pro_plugin_url ) ?>" target="_blank"><img src="<?php echo esc_attr( $product_data['icon_url'] ) ?>"/></a>
		</div>
	<?php endif; ?>
	<div class="wpfcs-product-middle">
		<h3 class="wpfcs-product-title">
			<a href="<?php echo esc_url( $pro_plugin_url ) ?>" target="_blank"><?php echo esc_html( $product_data['name'] ) ?></a>
		</h3>
		<p class="wpfcs-product-desc"><?php echo esc_html( $product_data['desc'] ) ?></p>
	</div>
	<div class="wpfcs-product-actions">
		<a href="<?php echo esc_url( $pro_plugin_url ) ?>" target="_blank" class="wpfcs-button wpfcs-button-2 <?php echo esc_attr( $pro_version_installed_class ) ?>"><i class="dashicons-before dashicons dashicons-external"></i></a>
	</div>
</div>