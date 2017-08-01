<?php
/**
 * Custom template tags.
 *
 * @package Progeny_MMXV
 * @since 1.0.0
 */

/**
 * Determine if a post has content.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post $post_id Optional. Post ID or WP_Post object. Defaults to the current global post.
 * @return bool
 */
function progeny_has_content( $post_id = null ) {
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;
	$content = get_post_field( 'post_content', $post_id );

	return empty( $content ) ? false : true;
}

/**
 * Return new WP Query object with pages for a specific page type.
 *
 * @since 1.0.0
 *
 * @param int|WP_Post $post Optional. Post ID or object. Defaults to the current post.
 * @return object WP Query
 */
function progeny_page_type_query( $post = 0 ) {
	$post = get_post( $post );

	return new WP_Query( apply_filters( 'progeny_page_type_query_args', array(
		'post_type'      => 'page',
		'post_parent'    => $post->ID,
		'posts_per_page' => 50,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) ) );
}

/**
 * Display a notice when there aren't any page types available.
 *
 * @since 1.0.0
 */
function progeny_page_type_notice() {
	echo esc_html( sprintf(
		_x( 'There are currently not any %s available.', 'archive template label', 'progeny-mmxv' ),
		esc_html( get_the_title() )
	) );

	if ( current_user_can( 'publish_posts' ) ) :
		$notice = sprintf(
			/* translators: there is a space at the begining of this sentence. */
			_x( ' Create a <a href="%1$s">new page</a> with this page as its <a href="%2$s">parent</a>.', 'archive template label; create page link', 'progeny-mmxv' ),
			esc_url( add_query_arg( 'post_type', 'page', admin_url( 'post-new.php' ) ) ),
			esc_url( 'https://en.support.wordpress.com/pages/page-attributes/#parent' )
		);

		echo progeny_allowed_tags( wpautop( $notice ) ); // WPCS: XSS OK.
	endif;
}

/**
 * Display HTML classes for the posts container.
 *
 * @since 1.0.0
 *
 * @param string|array $classes One or more classes to add to the class list.
 */
function progeny_posts_class( $classes = array() ) {
	printf(
		' class="%s"',
		esc_attr( implode( ' ', progeny_get_posts_class( $classes ) ) )
	);
}

/**
 * Retrieve HTML classes for the posts container as an array.
 *
 * @since 1.0.0
 *
 * @param string|array $classes One or more classes to add to the class list.
 * @return array Array of classes.
 */
function progeny_get_posts_class( $classes = array() ) {
	if ( ! empty( $classes ) && ! is_array( $classes ) ) {
		// Split a string.
		$classes = preg_split( '#\s+#', $classes );
	}

	$classes[] = 'posts-container';

	return array_unique( apply_filters( 'progeny_posts_class', $classes ) );
}

/**
 * Allow only the allowedtags array in a string.
 *
 * @since 1.0.0
 *
 * @link https://www.tollmanz.com/wp-kses-performance/
 *
 * @param  string $string The unsanitized string.
 * @return string		 The sanitized string.
 */
function progeny_allowed_tags( $string ) {
	global $allowedtags;

	$theme_tags = array(
		'a'	=> array(
			'class'	=> true,
			'href'  => true,
			'rel'   => true,
			'title' => true,
		),
		'br' => array(),
		'h2' => array(
			'class' => true,
		),
		'p' => array(),
		'span' => array(
			'class' => true,
		),
		'time' => array(
			'class'    => true,
			'datetime' => true,
		),
	);

	return wp_kses( $string, array_merge( $allowedtags, $theme_tags ) );
}
