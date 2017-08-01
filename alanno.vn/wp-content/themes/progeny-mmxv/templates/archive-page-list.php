<?php
/**
 * Template Name: List Page
 *
 * @package Progeny_MMXV
 * @since 1.1.0
 */

get_header();
?>

<div id="primary" class="content-area archive-list">
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

			<div id="posts-container" <?php progeny_posts_class( 'block-list' ); ?>>

				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'block-list-item' ); ?>>

						<?php the_title( '<h1 class="block-list-item-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>

						<?php if ( has_excerpt() ) : ?>

							<div class="block-list-item-content">
								<?php the_excerpt(); ?>
							</div>

						<?php endif; ?>

					</article>

				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>

			</div>

		<?php else : ?>

			<?php progeny_page_type_notice(); ?>

		<?php endif; ?>

		<?php do_action( 'progeny_main_bottom' ); ?>

	</main>
</div>

<?php
get_footer();
