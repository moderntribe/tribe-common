<?php 
/**
 * View: Troubleshooting - Introduction
 * 
 * @since TBD
 * 
 */
?>
<div class="tribe-events-admin-header tribe-events-admin-container">
	<?php do_action('tec-admin-notice-area', 'help'); ?>
	<div class="tribe-events-admin-header__content-wrapper">

		<img
			class="tribe-events-admin-header__logo-word-mark"
			src="<?php echo esc_url(tribe_resource_url('images/logo/tec-brand.svg', false, null, $main)); ?>"
			alt="<?php esc_attr_e('The Events Calendar brand logo', 'tribe-common'); ?>"
		/>

		<h2 class="tribe-events-admin-header__title"><?php esc_html_e('Troubleshooting', 'tribe-common'); ?></h2>
		<p class="tribe-events-admin-header__description"><?php esc_html_e('Sometimes things just don’t work as expected. We’ve created a wealth of resources to get you back on track.', 'tribe-common'); ?></p>
	</div>
</div>

<div class="tribe-events-admin-content-wrapper tribe-events-admin-container">
    <img
        class="tribe-events-admin-header__right-image"
        src="<?php echo esc_url(tribe_resource_url('images/help/troubleshooting-hero.png', false, null, $main)); ?>"
    />