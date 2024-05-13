<?php

// Chargement du CSS personnalisé

function theme_enqueue_styles() {
  wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . './style.css' );

  // Chargement des scripts JS

  wp_enqueue_script('jquery'); //Charger jQuery en premier

  wp_enqueue_script('burger-menu-script', get_stylesheet_directory_uri() . '/assets/js/burger-menu-script.js', array(), '1.0', true);
  wp_enqueue_script('scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array('jquery'), '1.0', true);

    $reference = get_field('reference'); // Récupération du champ "Référence" depuis ACF

    // Données à transmettre
    $reference_value = array(
      'referenceValue' => $reference
    );

    // Transmettre les données à votre script JavaScript
    wp_localize_script('scripts', 'referenceJS', $reference_value);

  wp_enqueue_script('display-thumbnails', get_stylesheet_directory_uri() . '/assets/js/display-thumbnails.js', array('jquery'), '1.0', true);
  wp_enqueue_script('load-more', get_stylesheet_directory_uri() . '/assets/js/load-more.js', array('jquery'), '1.0', true);
  
    // Génération du nonce (jeton de sécurité) et ajout dans une variable JavaScript pour utilisation côté client
    $load_more_nonce = wp_create_nonce('load_more_photos_nonce');

    wp_localize_script('load-more', 'loadMore_js', array('ajax_url' => admin_url('admin-ajax.php'), 'load_more_photos_nonce' => $load_more_nonce));

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

// Ajout de la modale de contact en tant que hook du menu header

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

////////////////////////////////////////////////////////////////
// Chargement de photos supplémentaires sur la page d'accueil //
////////////////////////////////////////////////////////////////

  function load_more_photos () {

    // On récupère le numéro de page actuel avec GET 'paged', de manière sécurisée,
    // en vérifiant que le numéro en question est de type "numérique"; on conserve sa veleur "entière".    
    $paged = (isset($_POST['paged']) && is_numeric($_POST['paged'])) ? intval($_POST['paged']) : 1;

    // Vérifier et filtrer le nonce (pour prévenir les attaques CSRF)
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'load_more_photos_nonce')) {
      wp_send_json_error('Vérification de sécurité échouée');
    }

    $load_more_args = array(
      'post_type' => 'photos',
      'posts_per_page' => 8,
      'orderby' => 'date',
      'order' => 'DESC',
      'paged' => $paged,
    );

    $load_more_query = new WP_Query($load_more_args);

    $response = array(
      'html' => '', // Initialise le contenu HTML
      'hasMore' => false, // Initialise l'indicateur de photos supplémentaires
      'success' => false, // Initialise le statut de la requête
    );

    if ($load_more_query->have_posts()) {
      ob_start();
      while ($load_more_query->have_posts()) {
        $load_more_query->the_post();
        get_template_part('templates_part/photo_block'); // On va chercher le template partiel.
      }
      $response['html'] = ob_get_clean();
      $response['success'] = true; // La requête a réussi.
    }

    // On vérifie s'il y a davantage de photos disponibles.
    $total_posts = $load_more_query->found_posts;
    $posts_per_page = $load_more_query->query_vars['posts_per_page'];
    if ($total_posts > ($paged * $posts_per_page)) {
      $response['hasMore'] = true; // On indique qu'il y a encore des photos disponibles.
    }

    wp_reset_postdata();

    wp_send_json($response);

    wp_die();
  }

  // Ajouter une action pour gérer la requête AJAX
  add_action('wp_ajax_load_more_photos', 'load_more_photos'); // Pour les utilisateurs connectés
  add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos'); // Pour les utilisateurs non connectés