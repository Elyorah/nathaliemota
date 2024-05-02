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

          <h1 class="nmota-photo-post__content-description--title"> <?php the_title(); ?> </h1>

          <ul class="nmota-photo-post__content-description--list">

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
        
        <p class="nmota-photo-post__cta--intro">Cette photo vous intéresse ?</p>
        <button id="nmota-photo-post__cta--button" class="spacemono-regular default-button">Contact</button>

        <div class="nmota-photo-post__cta--nav">
          <img src="" alt="" class="">

          <div class="nmota-photo-post__cta--nav-arrows">
            <img src="" alt="" class="">
            <img src="" alt="" class="">
          </div>

        </div>

      </div>

      <div class="nmota-photo-post__suggestions">

        <h2 class="nmota-photo-post__suggestions--title spacemono-regular">Vous aimerez aussi</h2>

        <div class="nmota-photo-post__suggestions--photos">
          <img src="" alt="" class="">
          <img src="" alt="" class="">
        </div>

      </div>

    </article>

  <?php endwhile; endif; ?>
  
  <!-- Fin du Body -->  

<!-- Footer -->  
<?php get_footer(); ?>