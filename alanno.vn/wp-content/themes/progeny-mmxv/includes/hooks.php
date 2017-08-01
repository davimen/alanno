<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package Progeny_MMXV
 * @since 1.0.0
 */

/**
 * Theme credits text.
 *
 * @link https://wordpress.org/plugins/footer-credits/
 *
 * @since 1.0.0
 *
 * @param string $text Text to display.
 * @return string
 */
function progeny_credits() {
	$credits = apply_filters( 'progeny_credits', '' );
	$credits = apply_filters( 'footer_credits', $credits );
	echo progeny_allowed_tags( $credits );
}
add_action( 'twentyfifteen_credits', 'progeny_credits' );
