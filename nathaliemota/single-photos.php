<!-- Header -->  
<?php get_header(); ?>

  <!-- Body -->  

  <?php if( have_posts() ) : while( have_posts() ) : the_post();

    ///////////////
    // VARIABLES //
    ///////////////

    // Récupération des taxonomies de CPT UI

    $categories = get_the_terms(get_the_ID(), 'categorie');
    $formats = get_the_terms(get_the_ID(), 'format');

    // Récupération des champs ACF nécessaires

    $reference = get_field('reference');
    $type = get_field('type');

    /////////////
    // CONTENU //
    /////////////
?>

    <article class="nmota-photo-post">

      <div class="nmota-photo-post__content">

        <div class="nmota-photo-post__content--main-picture">
          <?php the_content(); ?>
        </div>

        <div class="nmota-photo-post__content-description">

          <h1 class="nmota-photo-post__content-description--title spacemono-italic"> <?php the_title(); ?> </h1>

          <ul class="nmota-photo-post__content-description--list spacemono-regular">

            <li class="">Référence:
              <?php 
                if ($reference) {
                  echo $reference ;
                } else {
                  echo 'Aucune référence trouvée pour cette photo.';
                }
              ?>
            </li>

            <li class="">Catégorie:
              <?php
                if ($categories && !is_wp_error($categories)) {
                  foreach ($categories as $categorie) {
                    echo $categorie->name ;
                  }
                } else {
                  echo 'Aucune catégorie trouvée pour cette photo.';
                }
              ?>
            </li>

            <li class="">Format:
              <?php
                if ($formats && !is_wp_error($formats)) {
                  foreach ($formats as $format) {
                    echo $format->name ;
                  }
                } else {
                  echo 'Aucun format trouvé pour cette photo.';
                }
              ?>
            </li>

            <li class="">Type:
              <?php 
                if ($type) {
                  echo $type ;
                } else {
                  echo 'Aucun type trouvé pour cette photo.';
                }
              ?>
            </li>

            <li class="">Année:
              <?php the_time('Y'); ?>
            </li>

          </ul>

        </div>

      </div>

      <div class="nmota-photo-post__cta">
        
        <div class="nmota-photo-post__cta__main-container">
          <p class="nmota-photo-post__cta__main-container--intro poppins-light">Cette photo vous intéresse ?</p>
          <button id="nmota-photo-post__cta__main-container--button" class="spacemono-regular default-button nmota-photo-post__cta__main-container--button">Contact</button>
        </div>
        <div class="nmota-photo-post__cta__nav">

          <div class="nmota-photo-post__cta__nav__thumbnails">
            <!-- Navigation entre posts -->
            <?php
              $next_post = get_next_post(); // On récupère le post suivant.
              $prev_post = get_previous_post(); // On récupère le post précédent.
              // On vérifie si la publication suivante existe et on affiche sa vignette avec un lien vers le post.
              if ($next_post) {
                $next_post_thumbnail = get_the_post_thumbnail($next_post->ID, array(81, 71));
                $next_post_link = get_permalink($next_post->ID);
                echo '<a href="' . $next_post_link . '"><div id="next-thumbnail">' . $next_post_thumbnail . '</div></a>';
              }
              // Idem pour le post précédent, en vérifiant qu'il soit différent du post suivant
              if ($prev_post && $prev_post !== $next_post) {
                $prev_post_thumbnail = get_the_post_thumbnail($prev_post->ID, array(81, 71));
                $prev_post_link = get_permalink($prev_post->ID);
                echo '<a href="' . $prev_post_link . '"><div id="prev-thumbnail">' . $prev_post_thumbnail . '</div></a>';
              }
            ?>
          </div>
          
          <!-- On ajoute les flèches de navigation -->
          <div class="nmota-photo-post__cta__nav__arrows">
            <?php if ($prev_post) : ?>
              <a href="<?= get_permalink($prev_post->ID) ?>" class="nmota-photo-post__cta__nav__arrows--prev"></a>
            <?php endif; ?>
            <?php if ($next_post) : ?>
              <a href="<?= get_permalink($next_post->ID) ?>" class="nmota-photo-post__cta__nav__arrows--next"></a>
            <?php endif; ?>
          </div>
        </div>

      </div>

      <div class="nmota-photo-post__suggestions">

        <h2 class="nmota-photo-post__suggestions--title spacemono-regular">Vous aimerez aussi</h2>

        <div class="nmota-photo-post__suggestions--photos">

          <?php
          
          // On récupère l'ID du post actuel
          $current_post_id = get_the_ID();

          // On vérifie si des catégories ont été trouvées
          if ( $categories && ! is_wp_error( $categories ) ) {
            // On stocke les IDs des catégories dans un tableau
            $category_ids = array();

            // On boucle à travers les catégories pour récupérer les IDs
            foreach ( $categories as $category ) {
              $category_ids[] = $category->term_id;
            }
          }

          // Arguments pour la requête WP_Query
          $args = array(
            'post_type' => 'photos',
            'posts_per_page' => 2,
            'orderby' => 'rand',
            'tax_query' => array(
              array(
                'taxonomy' => 'categorie',
                'terms' => $category_ids,
              ),
            ),
          );
          // Exclure le post actuel de la requête
          $args['post__not_in'] = array( $current_post_id );

          // Affichage des photos apparentées
          $related_query = new WP_Query($args);

          if ($related_query->have_posts()) : while ($related_query->have_posts()) : $related_query->the_post();
            get_template_part( 'templates_part/photo_block' );
          endwhile; endif;

          wp_reset_postdata(); ?>

        </div>

      </div>

    </article>

  <?php endwhile; endif; ?>
  
  <!-- Fin du Body -->  

<!-- Footer -->  
<?php get_footer(); ?>