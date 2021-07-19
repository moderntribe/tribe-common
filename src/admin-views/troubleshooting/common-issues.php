<?php 
/**
 * View: Troubleshooting - Common Issues
 * 
 * @since TBD
 * 
 */

use \Tribe\Admin\Troubleshooting;
$common_issues    = tribe( Troubleshooting::class )->get_common_issues();
?>
<div class="tribe-events-admin-section-header">
	<h3>
		<?php esc_html_e('Common Problems', 'tribe-common'); ?>
	</h3>
</div>

<div class="tribe-events-admin-faq tribe-events-admin-4col-grid">
	<?php foreach ($common_issues as $commonIssue) : ?>
		<div class="tribe-events-admin-faq-card">
			<div class="tribe-events-admin-faq-card__icon">
				<img
					src="<?php echo esc_url(tribe_resource_url('images/icons/faq.png', false, null, $main)); ?>"
					alt="<?php esc_attr_e('lightbulb icon', 'tribe-common'); ?>"
				/>
			</div>
			<div class="tribe-events-admin-faq-card__content">
				<div class="tribe-events-admin-faq__question">
					<?php echo esc_html($commonIssue['issue']); ?>
				</div>
				<div class="tribe-events-admin-faq__answer">
					<?php
						$label = '<a href=" ' . $commonIssue['link'] . ' " target="_blank" rel="noreferrer">' . esc_html__( $commonIssue['link_label'], 'tribe-common' ) . '</a>';
						echo sprintf( __( $commonIssue['solution'], 'tribe-common' ), $label );
					?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>