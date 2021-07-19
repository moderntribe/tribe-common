<?php 
/**
 * View: Troubleshooting - EA Status
 * 
 * @since TBD
 * 
 */
?>
<h3 class="tribe-events-admin__troubleshooting-title tribe-events-admin__ea-status">
    <?php esc_html_e('Event Aggregator system status ', 'tribe-common'); ?>
</h3>

<table class="tribe-events-admin__ea-status-table">
    <?php //License & Usage?>
    <tr>
        <th>
            <?php esc_html_e('License & Usage', 'tribe-common'); ?>
        </th>
    </tr>

    <tr class="tribe-events-admin__ea-status-table-dark">
        <td>
            <?php esc_html_e('License Key', 'tribe-common'); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url('images/help/success-icon.svg', false, null, $main)); ?>"
                alt="<?php esc_attr_e('success-icon', 'tribe-common'); ?>"
            />
            <?php esc_html_e('Your license is valid', 'tribe-common'); ?>
        </td>
    </tr>

    <tr>
        <td>
            <?php esc_html_e('Event Block Patterns', 'tribe-common'); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url('images/help/success-icon.svg', false, null, $main)); ?>"
                alt="<?php esc_attr_e('success-icon', 'tribe-common'); ?>"
            />
            <?php esc_html_e('100 imports used ouf of 1000 available today', 'tribe-common'); ?>
        </td>
    </tr>

    <?php //Import Services?>
    <tr>
        <th>
            <?php esc_html_e('Import Services', 'tribe-common'); ?>
        </th>
    </tr>

    <tr class="tribe-events-admin__ea-status-table-dark">
        <td>
            <?php esc_html_e('Server Connection', 'tribe-common'); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url('images/help/error-icon.svg', false, null, $main)); ?>"
                alt="<?php esc_attr_e('error-icon', 'tribe-common'); ?>"
            />
            <?php esc_html_e('Connected to https://ea.theeventscalendar.com', 'tribe-common'); ?></td>
        </tr>

    <tr>
        <td>
            <?php esc_html_e( 'Scheduler Status', 'tribe-common' ); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url( tribe_resource_url( 'images/help/success-icon.svg', false, null, $main ) ); ?>"
                alt="<?php esc_attr_e( 'success-icon', 'tribe-common' ); ?>"
            />
            <?php esc_html_e( 'WP Cron enabled', 'tribe-common' ); ?>
        </td>
    </tr>

    <?php //Third Party Accounts?>
    <tr>
        <th>
            <?php esc_html_e( 'Third Party Accounts', 'tribe-common' ); ?>
        </th>
    </tr>

    <tr class="tribe-events-admin__ea-status-table-dark">
        <td class="tribe-events-admin__ea-status-table-dark">
            <?php esc_html_e( 'Eventbrite', 'tribe-common' ); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url('images/help/warning-icon.svg', false, null, $main)); ?>"
                alt="<?php esc_attr_e('warning-icon', 'tribe-common'); ?>"
            />
            <?php esc_html_e('Event Aggregator is not connected to Eventbrite', 'tribe-common'); ?>
        </td>
        <td>
            <a href="" target="_blank" rel="noreferrer">
                <?php esc_html_e('Connect to Eventbrite', 'tribe-common'); ?>
            </a>
        </td>
    </tr>

    <tr>
        <td>
            <?php esc_html_e('Meetup', 'tribe-common'); ?>
        </td>
        <td>
            <img
                src="<?php echo esc_url(tribe_resource_url('images/help/warning-icon.svg', false, null, $main)); ?>"
                alt="<?php esc_attr_e('warning-icon', 'tribe-common'); ?>"
            />
            <?php esc_html_e('Event Aggregator is not connected to Meetup', 'tribe-common'); ?>
        </td>
        <td>
            <a href="" target="_blank" rel="noreferrer">
                <?php esc_html_e('Connect to Meetup', 'tribe-common'); ?>
            </a>
        </td>
    </tr>
</table>
