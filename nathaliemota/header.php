<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    
  <?php wp_head(); ?>
</head>

<body class="nmota__body <?php body_class(); ?>" >
    
  <?php wp_body_open(); ?>

  <header class="nmota__header spacemono-regular">

    <nav role="navigation" aria-label="<?php _e('principal', 'text-domain'); ?>" id="nmota__header__navigation" class="nmota__header__navigation">
      <a href="<?php echo home_url( '/' ); ?>">
        <img class="nmota__header__navigation--logo" src="<?php echo get_template_directory_uri(); ?> /assets/images/logo.svg" alt="logo du site"/>
      </a>
      <button type="button" aria-expanded="false" aria-controls="nmota__header__menu" class="nmota__header__navigation--button">
      </button>
      <?php wp_nav_menu( array(
        'theme_location' => 'main-menu',
        'container' => 'ul',
        'menu_class' => 'nmota__header__navigation--menu',
        'menu_id' => 'nmota__header__navigation--menu'
      ) ); ?>
    </nav>

  </header>