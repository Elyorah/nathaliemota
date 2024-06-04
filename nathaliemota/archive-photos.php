<?php 
/*
	Template Name: archive-photos
	Template Post Type: page
*/

get_header();

/*'.'.'.'.'.*/
/*   HERO   */
/*'.'.'.'.'.*/

?>

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
		wp_reset_postdata();
	?>
				
	<h1 class="nmota-hero__title spacemono-italic-gras"><?php bloginfo('description'); ?></h1>

</div>

<?php
/*'.'.'.'.'.'.'*/
/*   FILTRES   */
/*'.'.'.'.'.'.'*/
?>

<div class="nmota-filters-container poppins-medium">

	<!-- Filtre 1 CATEGORIES -->

	<?php
		$categories = get_terms(array(
			'taxonomy' => 'categorie', // Nom de votre taxonomie
			'hide_empty' => false, // Afficher les termes même s'ils sont vides
	));
		if ($categories && !is_wp_error($categories)) : ?>
			<div class="filter-wrapper">
				<span class="filter-chevron"></span>
				<select id="filter-category">
					<option value="" class="">Catégories</option>
					<?php foreach($categories as $categorie) : ?>
						<option value="<?php echo esc_attr($categorie->slug); ?>"><?php echo esc_html($categorie->name); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>

	<!-- Filtre 2 FORMATS -->

	<?php
		$formats = get_terms(array(
			'taxonomy' => 'format', // Nom de votre taxonomie
			'hide_empty' => false, // Afficher les termes même s'ils sont vides
		));
		if ($formats && !is_wp_error($formats)) : ?>
			<div class="filter-wrapper">
				<span class="filter-chevron"></span>
				<select id="filter-format">
					<option value="">Formats</option>
					<?php foreach($formats as $format) : ?>
						<option value="<?php echo esc_attr($format->slug); ?>"><?php echo esc_html($format->name); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>
	
	<!-- Filtre 3 TRI PAR DATE -->
	<div class="filter-wrapper">
		<span class="filter-chevron"></span>
		<select id="filter-sort">
			<option value="">Trier par</option>
			<option value="DESC">Du plus récent au plus ancien</option>
			<option value="ASC">Du plus ancien au plus récent</option>
		</select>
	</div>

</div>

<?php
/*'.'.'.'.'.'.'.'.'.'.'*/
/*   LISTE DE PHOTOS   */
/*'.'.'.'.'.'.'.'.'.'.'*/
?>


<div class="nmota-home-photo-list">

	<?php
	// Arguments pour la requête WP_Query
	$args = array(
		'post_type' => 'photos',
		'posts_per_page' => 8,
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => 1,
	);

	// Affichage de la liste de photos
	$photo_list_query = new WP_Query ($args);

	if ($photo_list_query->have_posts()) : while ($photo_list_query->have_posts()) : $photo_list_query->the_post();
		get_template_part( 'templates_part/photo_block' );
	endwhile; endif;

	?>

</div>

<div class="button-container"><button id="load-more" class="default-button spacemono-regular">Charger plus</button></div>

<?php get_footer(); ?>