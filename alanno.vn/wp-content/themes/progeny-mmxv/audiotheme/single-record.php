<?php
/**
 * The template for displaying a single record.
 *
 * @package Progeny_MMXV
 * @since 1.0.0
 */

get_header();

$artist = get_audiotheme_record_artist();
$genre  = get_audiotheme_record_genre();
$links  = get_audiotheme_record_links();
$tracks = get_audiotheme_record_tracks();
$year   = get_audiotheme_record_release_year();
$download_url = is_audiotheme_track_downloadable( $track->ID );
?>

<div id="primary" class="content-area single-record">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="record-artwork">
						<a class="post-thumbnail" href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>">
							<?php the_post_thumbnail( 'progeny-record-thumbnail' ); ?>
						</a>
					</figure>
				<?php endif; ?>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					<?php if ( ! empty( $artist ) ) : ?>
						<h2 class="entry-subtitle record-artist"><?php echo esc_html( $artist ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $year ) || ! empty( $genre ) ) : ?>
						<ul class="entry-meta">
							<?php if ( ! empty( $year ) ) : ?>
								<li class="record-release">
									<span class="screen-reader-text"><?php esc_html_e( 'Released', 'progeny-mmxv' ); ?></span>
									<?php echo esc_html( $year ); ?>
								</li>
							<?php endif; ?>

							<?php if ( ! empty( $genre ) ) : ?>
								<li class="record-genre">
									<span class="screen-reader-text"><?php esc_html_e( 'Genre', 'progeny-mmxv' ); ?></span>
									<?php echo esc_html( $genre ); ?>
								</li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
				</header>

				<?php if ( ! empty( $links ) ) : ?>
					<div class="record-links">
						<h2><?php esc_html_e( 'Purchase', 'progeny-mmxv' ); ?></h2>
						<ul>
							<?php
							foreach( $links as $link ) {
								printf( '<li><a class="button" href="%1$s"%2$s>%3$s</a></li>',
									esc_url( $link['url'] ),
									( false === strpos( $link['url'], home_url() ) ) ? ' target="_blank"' : '',
									esc_html( $link['name'] )
								);
							}
							?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $tracks ) ) : ?>
					<div class="tracklist-section">
						<h2><?php esc_html_e( 'Track List', 'progeny-mmxv' ); ?></h2>
						<ol class="tracklist">
							<?php foreach ( $tracks as $track ) : ?>
								<li id="track-<?php echo absint( $track->ID ); ?>" class="track">
									<a class="track-title" href="<?php echo esc_url( get_permalink( $track->ID ) ); ?>"><?php echo get_the_title( $track->ID ); ?></a>

									<?php if ( ! empty( $download_url ) ) : ?>
										(<a class="track-download-link" href="<?php echo esc_url( $download_url ); ?>"><?php esc_html_e( 'Download', 'progeny-mmxv' ); ?></a>)
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ol>
					</div>
				<?php endif; ?>

				<div class="entry-content">
					<?php the_content( '' ); ?>
				</div>
			</article>

		<?php endwhile; ?>

	</main>
</div>

<?php
get_footer();
