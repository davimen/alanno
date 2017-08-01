<?php
/**
 * AudioTheme Compatibility File
 *
 * @package Progeny_MMXV
 * @since 1.0.0
 * @link https://audiotheme.com/
 */

/**
 * Set up theme defaults and register support for various AudioTheme features.
 *
 * @since 1.0.0
 */
function progeny_audiotheme_setup() {
	add_image_size( 'progeny-record-thumbnail', 748, 748, true );
	add_image_size( 'progeny-video-thumbnail', 748, 420, true );
}
add_action( 'after_setup_theme', 'progeny_audiotheme_setup', 11 );

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since 1.0.0
 */
function progeny_audiotheme_enqueue_assets() {
	// Enqueue AudioTheme's Fitvids script
	if ( is_singular( 'audiotheme_video' ) ) {
		wp_enqueue_script( 'jquery-fitvids' );
	}
}
add_action( 'wp_enqueue_scripts', 'progeny_audiotheme_enqueue_assets', 20 );

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since 1.0.0
 */
function progeny_audiotheme_document_head() {
	// Call AudioTheme's Fitvids script.
	if ( is_singular( 'audiotheme_video' ) && wp_script_is( 'jquery-fitvids' ) ) {
		echo '<script>jQuery(function($){ $(".hentry").fitVids(); });</script>' . "\n";
	}
}
add_action( 'wp_head', 'progeny_audiotheme_document_head', 20 );

/**
 * Add additional HTML classes to posts.
 *
 * @since 1.0.0
 *
 * @param array $classes List of HTML classes.
 * @return array
 */
function progeny_audiotheme_post_class( $classes ) {
	if ( is_singular( 'audiotheme_gig' ) && audiotheme_gig_has_venue() ) {
		$classes[] = 'has-venue';
	}

	if ( is_singular( 'audiotheme_track' ) && get_audiotheme_track_thumbnail_id() ) {
		$classes[] = 'has-post-thumbnail';
	}

	if ( is_singular( 'audiotheme_video' ) && get_audiotheme_video_url() ) {
		$classes[] = 'has-post-video';
	}

	return $classes;
}
add_filter( 'post_class', 'progeny_audiotheme_post_class', 10 );


/*
 * AudioTheme hooks.
 * -----------------------------------------------------------------------------
 */

/**
 * Activate default archive setting fields.
 *
 * @since 1.0.0
 *
 * @param array $fields List of default fields to activate.
 * @param string $post_type Post type archive.
 * @return array
 */
function progeny_audiotheme_archive_settings_fields( $fields, $post_type ) {
	if ( ! in_array( $post_type, array( 'audiotheme_record', 'audiotheme_video' ) ) ) {
		return $fields;
	}

	$fields['posts_per_archive_page'] = true;

	return $fields;
}
add_filter( 'audiotheme_archive_settings_fields', 'progeny_audiotheme_archive_settings_fields', 10, 2 );


/**
 * Add classes to gig archive block grids.
 *
 * @since 1.0.0
 *
 * @param array $classes Array of HTML classes for the gig archive posts wrapper.
 * @return array
 */
function progeny_audiotheme_gig_posts_class( $classes ) {
	if ( is_post_type_archive( 'audiotheme_gig' ) ) {
		$classes[] = 'block-grid';
		$classes[] = 'gig-list';
		$classes[] = 'vcalendar';
	}

	return $classes;
}
add_filter( 'progeny_posts_class', 'progeny_audiotheme_gig_posts_class' );

/**
 * Add classes to record archive block grids.
 *
 * @since 1.0.0
 *
 * @param array $classes Array of HTML classes for the record archive posts wrapper.
 * @return array
 */
function progeny_audiotheme_record_posts_class( $classes ) {
	if ( is_post_type_archive( 'audiotheme_record' ) ||	is_tax( 'audiotheme_record_type' ) ) {
		$classes[] = 'block-grid';
	}

	return $classes;
}
add_filter( 'progeny_posts_class', 'progeny_audiotheme_record_posts_class' );

/**
 * Add classes to video archive block grids.
 *
 * @since 1.0.0
 *
 * @param array $classes Array of HTML classes for the video archive posts wrapper.
 * @return array
 */
function progeny_audiotheme_video_posts_class( $classes ) {
	if ( is_post_type_archive( 'audiotheme_video' ) || is_tax( 'audiotheme_video_category' ) ) {
		$classes[] = 'block-grid';
		$classes[] = 'block-grid--16x9';
	}

	return $classes;
}
add_filter( 'progeny_posts_class', 'progeny_audiotheme_video_posts_class' );



/*
 * Template tags.
 * -----------------------------------------------------------------------------
 */

function progeny_the_audiotheme_tickets_html( $before = '', $after = '' ) {
	$gig_tickets_price = get_audiotheme_gig_tickets_price();
	$gig_tickets_url   = get_audiotheme_gig_tickets_url();

	if ( empty( $gig_tickets_price ) || empty( $gig_tickets_url ) ) {
		return;
	}

	$html = esc_html__( 'Tickets', 'progeny-mmxv' );

	if ( ! empty( $gig_tickets_price ) ) {
		$html .= sprintf(
			' <span class="gig-ticket-price">%s</span>',
			esc_html( $gig_tickets_price )
		);
	}

	if ( ! empty( $gig_tickets_url ) ) {
		$html = sprintf(
			'<a class="gig-tickets-link button js-maybe-external" href="%1$s">%2$s</a>',
			esc_url( $gig_tickets_url ),
			$html
		);
	}

	echo $before . $html . $after;
}
