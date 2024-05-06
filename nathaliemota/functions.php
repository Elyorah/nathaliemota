<?php

// Chargement du CSS personnalisé

function theme_enqueue_styles() {
  wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . './style.css' );

  // Chargement des scripts JS

  wp_enqueue_script('jquery'); //Charger jQuery en premier

  wp_enqueue_script('burger-menu-script', get_stylesheet_directory_uri() . '/assets/js/burger-menu-script.js', array(), '1.0', true);
  wp_enqueue_script('scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array('jquery'), '1.0', true);
  wp_enqueue_script('display-thumbnails', get_stylesheet_directory_uri() . '/assets/js/display-thumbnails.js', array('jquery'), '1.0', true);

  
  $reference = get_field('reference'); // Récupération du champ "Référence" depuis ACF

  // Données à transmettre
  $reference_value = array(
    'referenceValue' => $reference
  );

  // Transmettre les données à votre script JavaScript
  wp_localize_script('scripts', 'referenceJS', $reference_value);

}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

// Ajouter automatiquement le titre du site dans l'en-tête du site
add_theme_support( 'title-tag' );

// Ajouter la prise en charge des images mises en avant
add_theme_support( 'post-thumbnails' );

// Enregistrement des Menus de Navigation

function register_nmota_menus() {
  register_nav_menu( 'main-menu', __( 'principal', 'text-domain' ) );
  register_nav_menu( 'footer-menu', __( 'footer', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_nmota_menus' );

// Ajout de la mention "Tous droits réservés" en hook du menu footer

function paragraphe_copyright ( $items, $args ) {
	if ( isset( $args->theme_location ) && $args->theme_location === 'footer-menu' ) { //On vérifie qu'on est dans le menu footer
    $items .= '<li><p>Tous droits réservés</p></li>';
  }

	return $items;
}

add_filter( 'wp_nav_menu_items', 'paragraphe_copyright', 11, 2 );

// TEST

function modale_contact ( $items, $args ) {
	if ( isset( $args->theme_location ) && $args->theme_location === 'main-menu' ) {//On vérifie qu'on est dans le menu header
    
    ob_start(); // Commence à mettre en tampon la sortie
    get_template_part( 'templates_part/contact' );
    $modale_content = ob_get_clean(); // Récupère le contenu mis en tampon et arrête le tampon

    // Ajoute le contenu de la modale dans un élément de menu
    $items .= '<li>' . $modale_content . '</li>';
  }

	return $items;
}

add_filter( 'wp_nav_menu_items', 'modale_contact', 10, 2 );