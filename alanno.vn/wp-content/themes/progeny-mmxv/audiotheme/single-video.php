<?php
/**
 * The template for displaying a single video.
 *
 * @package Progeny_MMXV
 * @since 1.0.0
 */

get_header();

$video_url = get_audiotheme_video_url();
?>

<div id="primary" class="content-area single-video">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( ! empty( $video_url ) ) : ?>
					<figure class="entry-video">
						<?php the_audiotheme_video(); ?>
					</figure>
				<?php endif; ?>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<div class="entry-content">
					<?php the_content( '' ); ?>
				</div>

			</article>

		<?php endwhile; ?>

	</main>
</div>

<?php
get_footer();
