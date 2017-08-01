<?php
/**
 * The template for displaying a single track.
 *
 * @package Progeny_MMXV
 * @since 1.0.0
 */

get_header();

$artist       = get_audiotheme_record_artist();
$download_url = is_audiotheme_track_downloadable();
$purchase_url = get_audiotheme_track_purchase_url();
$thumbnail_id = get_audiotheme_track_thumbnail_id();
?>

<div id="primary" class="content-area single-record single-track">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

				<?php if ( ! empty( $thumbnail_id ) ) : ?>

					<figure class="record-artwork">
						<a class="post-thumbnail" href="<?php echo esc_url( get_permalink( $post->post_parent ) ); ?>">
							<?php echo wp_get_attachment_image( $thumbnail_id, 'progeny-record-thumbnail' ); ?>
						</a>
					</figure>

				<?php endif; ?>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					<?php if ( ! empty( $artist ) ) : ?>
						<h2 class="record-artist entry-subtitle"><?php echo esc_html( $artist ); ?></h2>
					<?php endif; ?>

					<h3 class="record-title entry-subtitle"><a href="<?php echo esc_url( get_permalink( $post->post_parent ) ); ?>"><?php echo get_the_title( $post->post_parent ); ?></a></h3>
				</header>

				<?php if ( ! empty( $download_url ) || ! empty( $purchase_url ) ) : ?>
					<div class="record-links track-links">
						<h2><?php esc_html_e( 'Track Links', 'progeny-mmxv' ); ?></h2>
						<ul>
							<?php if ( ! empty( $download_url ) ) : ?>
								<li>
									<a class="button" href="<?php echo esc_url( $download_url ); ?>" target="_blank"><?php esc_html_e( 'Download', 'progeny-mmxv' ); ?></a>
								</li>
							<?php endif; ?>

							<?php if ( ! empty( $purchase_url ) ) : ?>
								<li>
									<a class="button" href="<?php echo esc_url( $purchase_url ); ?>" target="_blank"><?php esc_html_e( 'Purchase', 'progeny-mmxv' ); ?></a>
								</li>
							<?php endif; ?>
						</ul>
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
