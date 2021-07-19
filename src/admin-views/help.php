<?php
/**
 * The template that displays the help page.
 */

$main = Tribe__Main::instance();

// Fetch the Help page Instance
$help = tribe(Tribe__Admin__Help_Page::class);

// get the products list
$products = tribe('plugins.api')->get_products();

//echo '<pre>' . print_r( $products, true ) . '</pre>';
?>

<div class="tribe-events-admin-header tribe-events-admin-container">
	<?php do_action('tec-admin-notice-area', 'help'); ?>
	<div class="tribe-events-admin-header__content-wrapper">

		<img
			class="tribe-events-admin-header__logo-word-mark"
			src="<?php echo esc_url(tribe_resource_url('images/logo/tec-brand.svg', false, null, $main)); ?>"
			alt="<?php esc_attr_e('The Events Calendar brand logo', 'tribe-common'); ?>"
		/>

		<h2 class="tribe-events-admin-header__title"><?php esc_html_e('Help', 'tribe-common'); ?></h2>
		<p class="tribe-events-admin-header__description"><?php esc_html_e('We\'re committed to helping make your calendar spectacular and have a wealth of resources available.', 'tribe-common'); ?></p>

		<ul class="tribe-events-admin-tab-nav">
			<li class="selected" data-tab="tribe-calendar"><?php esc_html_e('Calendar', 'tribe-common'); ?></li>
			<li data-tab="tribe-ticketing"><?php esc_html_e('Ticketing & RSVP', 'tribe-common'); ?></li>
			<li data-tab="tribe-community"><?php esc_html_e('Community', 'tribe-common'); ?></li>
		</ul>
	</div>
</div>

<div class="tribe-events-admin__line">
	&nbsp;
</div>

<div class="tribe-events-admin-content-wrapper tribe-events-admin-container">

	<?php
        // Calendar Tab
        include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/help-calendar.php';

        // Ticketing & RSVP Tab
        include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/help-ticketing.php';

        // Community Tab
        include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/help-community.php';
    ?>

	<?php // Shared footer area?>
	<div class="tribe-events-admin-cta">
		<img
			class="tribe-events-admin-cta__image"
			src="<?php echo esc_url(tribe_resource_url('images/help/troubleshooting.png', false, null, $main)); ?>"
			alt="<?php esc_attr_e('Graphic with an electrical plug and gears', 'tribe-common'); ?>"
		/>

		<div class="tribe-events-admin-cta__content">
			<div class="tribe-events-admin-cta__content-title">
				<?php esc_html_e('Need additional support?', 'tribe-common'); ?>
			</div>

			<div class="tribe-events-admin-cta__content-description">
				<a href="/wp-admin/edit.php?post_type=tribe_events&page=tec-troubleshooting">
					<?php esc_html_e('Visit Troubleshooting next', 'tribe-common'); ?>
				</a>
			</div>
		</div>
	</div>

	<img
		class="tribe-events-admin-footer-logo"
		src="<?php echo esc_url(tribe_resource_url('images/logo/tec-brand.svg', false, null, $main)); ?>"
		alt="<?php esc_attr_e('The Events Calendar brand logo', 'tribe-common'); ?>"
	/>
</div>

<?php // this is inline jQuery / javascript for extra simplicity */?>
<script type="text/javascript">
	jQuery( document ).ready( function($) {
		var current_tab = "#tribe-calendar";
		$( 'body' ).on( "click", ".tribe-events-admin-tab-nav li", function() {
			var tab = "#" + $( this ).data( "tab" );
			$( current_tab ).hide();
			$( '.tribe-events-admin-tab-nav li' ).removeClass( "selected" );
			$( this ).addClass( "selected" );

			$( tab ).show();
			current_tab = tab;
		} );
	} );
</script>