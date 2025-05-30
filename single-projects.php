<?php
/*
	Template Name: Single Project
	*/
get_header();

?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<!-- Display Project Title -->
		<h1>
			<?php the_title(); ?>
			<span class="post-date">(<?php the_time('F j, Y \a\t g:i a'); ?>)</span>
			<span class="post-status <?php echo get_post_status(); ?>">
				<?php echo ucfirst(get_post_status()); ?>
			</span>
		</h1>

		<!-- Display Project Excerpt -->
		<h2><?php echo get_the_excerpt(); ?></h2>

	</main>
</div>
<style>

</style>
<?php get_footer(); ?>
