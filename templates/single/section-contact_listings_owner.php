<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.0.5
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if( $listing->hide_contact_owner_form() ){
	return;
}
?>

<div class="directorist-card directorist-card-contact-owner <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="directorist-card__header">

		<h4 class="directorist-card__header--title"><?php directorist_icon( $icon );?><?php echo esc_html( $label );?></h4>
		
	</div>

	<div class="directorist-card__body">

		<form action="atbdp_public_send_contact_email" class="directorist-contact-owner-form" data-form-id="atbdp_stcode_contact_email">

			<div class="directorist-contact-owner-form-inner">

				<div class="directorist-form-group">
					<input type="text" class="directorist-form-element" name="atbdp-contact-name" placeholder="<?php esc_attr_e( 'Name', 'directorist' ); ?>" required />
				</div>

				<div class="directorist-form-group">
					<input type="email" class="directorist-form-element" name="atbdp-contact-email" placeholder="<?php esc_attr_e( 'Email', 'directorist' ); ?>" required />
				</div>

				<div class="directorist-form-group">
					<textarea class="directorist-form-element" name="atbdp-contact-message" rows="3" placeholder="<?php esc_attr_e( 'Message...', 'directorist' ); ?>" required></textarea>
				</div>

				<input type="hidden" name="atbdp-post-id" value="<?php echo esc_attr( $listing->id ); ?>" />
				<input type="hidden" name="atbdp-listing-email" value="<?php echo esc_attr( $listing->contact_owner_email() ); ?>" />

				<p class="directorist-contact-message-display"></p> 

				<button type="submit" class="directorist-btn directorist-btn-primary directorist-btn-sm directorist-btn-submit"><?php esc_html_e( 'Submit', 'directorist' ); ?></button>

			</div>

		</form>

	</div>

</div>