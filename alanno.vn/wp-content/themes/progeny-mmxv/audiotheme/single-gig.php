<?php
/**
 * The template for displaying a single gig.
 *
 * @package Progeny_MMXV
 * @since 1.0.0
 */

get_header();

$gig   = get_audiotheme_gig();
$venue = get_audiotheme_venue( $gig->venue->ID );
?>

<div id="primary" class="content-area single-gig">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php twentyfifteen_post_thumbnail(); ?>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					<h2 class="entry-subtitle gig-location">
						<?php echo get_audiotheme_venue_location( $gig->venue->ID ); ?>
					</h2>

					<h3 class="entry-subtitle gig-date-time">
						<span class="gig-date date">
							<time datetime="<?php echo get_audiotheme_gig_time( 'c' ); ?>"><?php echo get_audiotheme_gig_time( 'F d, Y' ); ?></time>
						</span>

						<span class="gig-time">
							<?php echo get_audiotheme_gig_time( '', 'g:i A', false, array( 'empty_time' => esc_html__( 'TBD', 'progeny-mmxv' ) ) ); ?>
						</span>
					</h3>

					<?php the_audiotheme_gig_description( '<div class="gig-description">', '</div>' ); ?>

					<?php
					progeny_the_audiotheme_tickets_html( '<div class="gig-tickets"><h4 class="screen-reader-text">' . esc_html__( 'Tickets', 'progeny-mmxv' ) . '</h4>',
						'</div>' );
					?>
				</header>

				<div class="entry-content">
					<?php if ( audiotheme_gig_has_venue() ) : ?>
						<dl class="venue-meta">
							<dt class="venue-address"><?php esc_html_e( 'Address', 'progeny-mmxv' ); ?></dt>
							<dd class="venue-address">
								<?php
								the_audiotheme_venue_vcard( array(
									'container'      => '',
									'show_name_link' => false,
									'show_phone'     => false,
								) );
								?>
							</dd>

							<?php if ( ! empty( $venue->phone ) ) : ?>
								<dt class="venue-phone"><?php esc_html_e( 'Phone', 'progeny-mmxv' ); ?></dt>
								<dd class="venue-phone"><?php echo esc_html( $venue->phone ); ?></dd>
							<?php endif; ?>

							<?php if ( ! empty( $venue->website ) ) : ?>
								<dt class="venue-website"><?php esc_html_e( 'Website', 'progeny-mmxv' ); ?></dt>
								<dd class="venue-website"><a href="<?php echo esc_url( $venue->website ); ?>"><?php echo esc_html( audiotheme_simplify_url( $venue->website ) ); ?></a></dd>
							<?php endif; ?>
						</dl>
					<?php endif; ?>

					<?php the_content( '' ); ?>
				</div>

				<?php if ( audiotheme_gig_has_venue() ) : ?>
					<figure class="venue-map">
						<?php
						echo get_audiotheme_google_map_embed( array(
							'width'     => '100%',
							'height'    => 510,
							'link_text' => false,
						), $venue->ID );
						?>
					</figure>
				<?php endif; ?>

			</article>

		<?php endwhile; ?>

	</main>
</div>

<?php
get_footer();
