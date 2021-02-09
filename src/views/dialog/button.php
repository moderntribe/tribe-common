<?php
/**
 * Dialog Button Template
 * The button template for Tribe Dialog trigger.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/dialogs/buton.php
 *
 * @since 4.10.0
 *
 * @package Tribe
 * @version 4.10.0
 */
$classes = $button_classes ?: 'tribe-button';
$classes = implode( ' ' , (array) $classes );
?>
<button
	class="<?php echo esc_attr( $classes ); ?>"
	data-content="<?php echo esc_attr( 'dialog-content-' . $id ); ?>"
	data-js="<?php echo esc_attr( 'trigger-dialog-' . $id ); ?>"
	<?php if ( ! empty( $button_id ) ) : ?>
		id="<?php echo esc_attr( $button_id ); ?>"
	<?php endif; ?>
	<?php if ( ! empty( $button_name ) ) : ?>
		name="<?php echo esc_attr( $button_name ); ?>"
	<?php endif; ?>
	<?php if ( ! empty( $button_type ) ) : ?>
		type="<?php echo esc_attr( $button_type ); ?>"
	<?php endif; ?>
	<?php if ( ! empty( $button_value ) && 0 !== absint( $button_value ) ) : ?>
		value="<?php echo esc_attr( $button_value ); ?>"
	<?php endif; ?>
	<?php if ( ! empty( $button_disabled ) && tribe_is_truthy( $button_disabled ) ) : ?>
		<?php tribe_disabled( true ); ?>
	<?php endif; ?>
><?php echo esc_html( $button_text ); ?></button>
