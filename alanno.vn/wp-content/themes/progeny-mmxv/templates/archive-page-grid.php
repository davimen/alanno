<?php
/**
 * Template Name: Grid Page
 *
 * @package Progeny_MMXV
 * @since 1.1.0
 */

get_header();
?>

<div id="primary" class="content-area archive-grid">
	<main id="main" class="site-main" role="main">

		<?php do_action( 'progeny_main_top' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<header class="page-header">
				<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

				<?php if ( progeny_has_content() ) : ?>
					<div class="page-content"><?php the_content(); ?></div>
				<?php endif; ?>
			</header>

		<?php endwhile; ?>

		<?php
		$loop = progeny_page_type_query();
		if ( $loop->have_posts() ) :
		?>

			<div id="posts-container" <?php progeny_posts_class( array( 'block-grid', 'block-grid--16x9' ) ); ?>>

				<div class="block-grid-inside">

					<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'block-grid-item' ); ?>>

							<a class="block-grid-item-thumbnail" href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'progeny-block-grid-16x9' ); ?>
							</a>

							<div class="block-grid-item-header entry-header">
								<?php the_title( '<h1 class="block-grid-item-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>
							</div>

						</article>

					<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>
				</div>

			</div>

		<?php else : ?>

			<?php progeny_page_type_notice(); ?>

		<?php endif; ?>

		<?php do_action( 'progeny_main_bottom' ); ?>

	</main>
</div>

<?php
get_footer();
