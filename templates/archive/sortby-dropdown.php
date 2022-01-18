<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-dropdown directorist-dropdown-js directorist-sortby-dropdown">

	<a class="directorist-dropdown__toggle directorist-dropdown__toggle-js directorist-btn directorist-btn-sm directorist-btn-px-15 directorist-btn-outline-primary directorist-toggle-has-icon" href="#"><?php echo esc_html( $listings->sort_by_text() ); ?><span class="directorist-icon-caret"></span></a>

	<div class="directorist-dropdown__links directorist-dropdown__links-js directorist-dropdown__links--right">

		<form id="directorsit-listing-sort" method="post" action="#">

			<?php
			$current = !empty( $_GET['sort'] ) ? $_GET['sort'] : '';

			foreach ( $listings->sort_by_dropdown_data() as $key => $item ) {
				$active_class = ( $key == $current ) ? 'active' : '';
				?>

				<a class="directorist-dropdown__links--single directorist-dropdown__links--single-js <?php echo esc_attr( $active_class );?>" data-link="<?php echo esc_attr( $item['link'] ); ?>"><?php echo esc_html( $item['label'] );?></a>

				<?php
			}
			?>

		</form>

	</div>

</div>