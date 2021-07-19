<?php 
/**
 * View: Troubleshooting - Event Logs
 * 
 * @since TBD
 * 
 */
    
 $error_log = tribe( 'logger' )->admin()->get_log_entries();
?>
<div class="tribe-events-admin__troubleshooting-event-log-wrapper">
    <h3 class="tribe-events-admin__troubleshooting-title tribe-events-admin__recent-log">
        <?php esc_html_e('Event log', 'tribe-common'); ?>
    </h3>
    <?php 
        // event log
        include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/event-log.php';
    ?>
</div>

<div class="tribe-events-admin__recent-log-filters">
    <div class="tribe-events-admin__recent-log-filters-field">
        <label>
            <?php esc_html_e('Logging level', 'tribe-common'); ?>
        </label>
        <div class="tribe-events-admin__recent-log-filters-select-wrapper">
            <select name="" id="">
                <option value="errors">
                    <?php esc_html_e('Only errors', 'tribe-common'); ?>
                </option>
            </select>
        </div>
    </div>

    <div class="tribe-events-admin__recent-log-filters-field">
        <label>
            <?php esc_html_e('Method', 'tribe-common'); ?>
        </label>
        <div class="tribe-events-admin__recent-log-filters-select-wrapper">
            <select name="" id="">
                <option value="default">
                    <?php esc_html_e('Default (uses temporary file)', 'tribe-common'); ?>
                </option>
            </select>
        </div>
    </div>

    <div class="tribe-events-admin__recent-log-filters-field">
        <label>
            <?php esc_html_e('View', 'tribe-common'); ?>
        </label>
        <div class="tribe-events-admin__recent-log-filters-select-wrapper">
            <select name="" id="">
                <option value="none">
                    <?php esc_html_e('None currently available', 'tribe-common'); ?>
                </option>
            </select>
        </div>
    </div>
</div>

<div class="tribe-events-admin__system-information-widget">
    <?php if ( empty( $error_log ) ) : ?>
        <?php esc_html_e('The selected log file is empty or has not been generated yet.', 'tribe-common'); ?>
    <?php else: ?>
        <?php var_dump( $error_log ); die; ?>
    <?php endif; ?>
</div>