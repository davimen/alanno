<?php
/**
 * The template to display list of videos.
 *
 * @package Progeny_MMXV
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" <?php audiotheme_archive_class( array( 'content-area', 'archive-video' ) ); ?>>
	<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php the_audiotheme_archive_title( '<h1 class="page-title">', '</h1>' ); ?></h1>
				<?php the_audiotheme_archive_description( '<div class="page-content">', '</div>' ); ?>
			</header>

			<div id="posts-container" <?php progeny_posts_class(); ?>>
				<div class="block-grid-inside">

					<?php while ( have_posts() ) : the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'block-grid-item' ); ?>>

							<?php if ( has_post_thumbnail() ) : ?>
								<a class="block-grid-item-thumbnail" href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'progeny-video-thumbnail' ); ?>
								</a>
							<?php endif; ?>

							<div class="block-grid-item-header entry-header">
								<?php the_title( '<h2 class="block-grid-item-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
							</div>

						</article>

					<?php endwhile; ?>

				</div>
			</div>

			<?php
			the_posts_pagination( array(
				'prev_text'          => esc_html__( 'Previous page', 'progeny-mmxv' ),
				'next_text'          => esc_html__( 'Next page', 'progeny-mmxv' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'progeny-mmxv' ) . ' </span>',
			) );
			?>

		<?php else : ?>

			<?php get_template_part( 'audiotheme/parts/content-none', 'video' ); ?>

		<?php endif; ?>

	</main>
</div>

<?php
get_footer();
