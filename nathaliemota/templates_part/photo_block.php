<?php

// Template qui gère l'affichage d'un block photo

$post_permalink = get_permalink();

?>

  <div class="photo-block">
      <a href="<?php echo esc_url($post_permalink); ?>">
          <?php the_post_thumbnail(); ?>
      </a>
  </div>

<?php