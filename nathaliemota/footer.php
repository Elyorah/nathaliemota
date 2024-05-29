  <footer class="nmota__footer spacemono-regular">

    <?php get_template_part( 'templates_part/lightbox' ); ?>

    <nav role="navigation" aria-label="<?php _e('footer', 'text-domain'); ?>" id="nmota__footer__navigation" class="nmota__footer__navigation">
      <?php wp_nav_menu( array(
        'theme_location' => 'footer-menu',
        'container' => 'ul',
        'menu_class' => 'nmota__footer__menu',
        'menu_id' => 'nmota__footer__menu'
      ) ); ?>
    </nav>
  </footer>


<?php wp_footer(); ?>
  
</body>
</html>