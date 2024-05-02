<?php get_header() ?>

<!-- TEST -->
<h1 class="spacemono-italic-gras archive-photo__title">PHOTOGRAPHE EVENT</h1>
<!-- FIN TEST -->

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
  
    <?php the_content(); ?>

<?php endwhile; endif; ?>

<?php get_footer() ?>