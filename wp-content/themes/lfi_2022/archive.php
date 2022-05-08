<?php get_header(); ?>

<?php get_template_part( './template-parts/header-blog' ); ?>

	<main>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<article class="post">
				<h2><?php the_title(); ?></h2>

				<?php the_post_thumbnail(); ?>

				<p class="post__meta">
					Publié le <?php the_time( get_option( 'date_format' ) ); ?>
					par <?php the_author(); ?> • <?php comments_number(); ?>
				</p>

				<?php the_excerpt(); ?>

				<p>
					<a href="<?php the_permalink(); ?>" class="post__link">Lire la suite</a>
				</p>
			</article>

		<?php endwhile; endif; ?>
	</main>
	<div class="site__navigation">
		<div class="site__navigation__prev">
			<?php previous_posts_link(); ?>
		</div>
		<div class="site__navigation__next">
			<?php next_posts_link(); ?>
		</div>
	</div>

<?php get_footer(); ?>
