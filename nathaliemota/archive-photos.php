<?php 
/*
	Template Name: archive-photos
	Template Post Type: page
*/

get_header(); ?>

<div class="nmota-hero">

	<!-- On récupère aléatoirement 1 post du CPT "Photos" -->
	<?php
		$args = array(
			'post_type' => 'photos',
			'orderby'   => 'rand',
			'posts_per_page' => 1,
		);

		// On exécute la requête WP_Query avec les critères placés dans la variables $args
		$hero_query = new WP_Query( $args );

		// Boucle 
		if( $hero_query->have_posts() ) : while( $hero_query->have_posts() ) : $hero_query->the_post();

			if(has_post_thumbnail()) : ?>

				<div class="nmota-hero__thumbnail">
					<a href="<?php the_permalink(); ?>" alt="<?php the_title(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
				</div>
				
			<?php endif;
                    
		endwhile; endif ;   
						
	// On réinitialise à la requête principale
	wp_reset_postdata(); ?>
				
	<h1 class="nmota-hero__title spacemono-italic-gras"><?php bloginfo('description'); ?></h1>

</div>

		
<?php get_footer(); ?>