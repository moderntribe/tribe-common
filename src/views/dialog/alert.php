<?php
/**
 * Dialog Alert View Template
 * The alert template for tribe-dialogs.
 *
 * Includes a "OK" button. All event handling is in `alert-script.php`
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/dialogs/alert.php
 *
 * @package Tribe
 * @version TBD
 */

?>
<?php tribe( 'dialog.view' )->template( 'script', get_defined_vars(), true ); ?>
<?php tribe( 'dialog.view' )->template( 'button', get_defined_vars(), true ); ?>
<script data-js="<?php echo esc_attr( 'dialog-content-' . $id ); ?>" type="text/template" >
	<div class="<?php echo esc_attr( $content_classes ); ?>">
		<?php if ( ! empty( $title ) ) : ?>
			<h2><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php echo $content; ?>
		<div class="tribe-dialog__button_wrap">
			<button class="tribe-button tribe-alert__continue"><?php echo esc_html( $alert_button_text ); ?></button>
		</div>
	</div>
</script>
