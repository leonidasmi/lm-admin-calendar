<?php
/**
 * Provide the view for a metabox
 *
 * @since      1.0.0
 *
 * @package    LM_Admin_Calendar
 * @subpackage LM_Admin_Calendar/admin/partials
 */

?>
<input type="hidden" id="lmac_event_user" name="lmac_event_user" value="<?php echo esc_attr( get_current_user_id() ); ?>">
<div class="form-group form-text-line" id="event-datetime" >
	<label><?php esc_html_e( 'Event Date', 'lm-admin-calendar' ); ?></label>
	<input type="text" class="form-control MyDate" name="lmac_event_date" id="event_date" value="<?php echo esc_attr( $event_date ); ?>">
</div>
