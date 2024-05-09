<?php

// On récupère l'ID du post actuel
$current_post_id = get_the_ID();

// On récupère la taxonomie "Catégories" du CPT "Photos"
$categories = get_the_terms( get_the_ID(), 'categorie' );

// On vérifie si des catégories ont été trouvées
if ( $categories && ! is_wp_error( $categories ) ) {
  // On stocke les IDs des catégories dans un tableau
  $category_ids = array();

  // On boucle à travers les catégories pour récupérer les IDs
  foreach ( $categories as $category ) {
    $category_ids[] = $category->term_id;
  }
}

// On utilise les IDs des catégories dans une WP_Query pour filtrer par catégorie
$args = array(
    'post_type'      => 'photos',
    'posts_per_page' => 2,
    'orderby'       => 'rand',
    'tax_query'      => array (
        array (
            'taxonomy' => 'categorie',
            'terms'    => $category_ids,
        ),
    ),
);
// Exclure le post actuel de la requête
$args['post__not_in'] = array( $current_post_id );


// On exécute la WP Query
$related_query = new WP_Query( $args );

// Boucle 
if( $related_query->have_posts() ) : while( $related_query->have_posts() ) : $related_query->the_post();
  
  

  $post_permalink = get_permalink(); ?>

  <div class="related-photo">
    <a href="<?php echo esc_url( $post_permalink ); ?>">
      <?php the_post_thumbnail(); ?>
    </a>
  </div>
    
  

<?php
endwhile; endif;

// On réinitialise la requête principale
wp_reset_postdata();