<?php

/****************************************/
/* CHARGEMENTS DES SCRIPTS JS ET DU CSS */
/****************************************/


function theme_enqueue_styles() {

// Chargement du CSS personnalisé
  wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . '/style.css' );

// Chargement des scripts JS

  // Charger jQuery en premier
  wp_enqueue_script('jquery');

  // Burger-menu
  wp_enqueue_script('burger-menu-script', get_stylesheet_directory_uri() . '/assets/js/burger-menu-script.js', array(), '1.0', true);

  // Modale de contact
  wp_enqueue_script('scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array('jquery'), '1.0', true);

    $reference = get_field('reference'); // Récupération du champ "Référence" depuis ACF

    // Données à transmettre
    $reference_value = array(
      'referenceValue' => $reference
    );

    // Transmission des données à JavaScript
    wp_localize_script('scripts', 'reference_JS', $reference_value);

  // Script pour les miniatures de la page "single-photos"
  wp_enqueue_script('display-thumbnails', get_stylesheet_directory_uri() . '/assets/js/display-thumbnails.js', array('jquery'), '1.0', true);

  // Lightbox
  wp_enqueue_script('lightbox', get_stylesheet_directory_uri() . '/assets/js/lightbox.js', array(), '1.0', true);

  // Animation du chevron des filtres
  wp_enqueue_script('filters-chevron', get_stylesheet_directory_uri() . '/assets/js/filters-chevron.js', array(), '1.0', true);


  // Ajax pour les filtres de tri des Photos et le bouton "charger plus" pour la pagination
  wp_enqueue_script('load-more_and_filters_ajax', get_stylesheet_directory_uri() . '/assets/js/load-more_and_filters_ajax.js', array('jquery'), '1.0', true);

    // On récupère l'URL qui traite la requête AJAX, et on ajoute un jeton de sécurité "nonce"
    wp_localize_script('load-more_and_filters_ajax', 'loadMore_and_filters_JS', array('ajax_url' => admin_url('admin-ajax.php'),'loadMore_and_filters_nonce' => wp_create_nonce('loadMore_and_filters_nonce')));
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


/**********/
/* GLOBAL */
/**********/


// Ajouter automatiquement le titre du site dans l'en-tête du site
add_theme_support( 'title-tag' );

// Ajouter la prise en charge des images mises en avant
add_theme_support( 'post-thumbnails' );

// Taille d'image personnalisée
add_image_size('nav-thumbnail', 81, 71, true);

/****************************************/
/* MENUS DE NAVIGATION HEADER ET FOOTER */
/****************************************/


// Enregistrement des Menus de Navigation

function register_nmota_menus() {
  register_nav_menu( 'main-menu', __( 'principal', 'text-domain' ) );
  register_nav_menu( 'footer-menu', __( 'footer', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_nmota_menus' );


/*********/
/* HOOKS */
/*********/


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

// Ajout de la mention "Tous droits réservés" en hook du menu footer

function paragraphe_copyright ( $items, $args ) {
	if ( isset( $args->theme_location ) && $args->theme_location === 'footer-menu' ) { //On vérifie qu'on est dans le menu footer
    $items .= '<li><p>Tous droits réservés</p></li>';
  }

	return $items;
}

add_filter( 'wp_nav_menu_items', 'paragraphe_copyright', 11, 2 );


/***************************************/
/* Tri des Photos de la page d'accueil */
/***************************************/


function loadMore_and_filters_photos() {
  // Récupérer les paramètres de pagination et des filtres

  // On récupère le numéro de page actuel de manière sécurisée,
  // en vérifiant que le numéro en question est de type "numérique"; on conserve sa veleur "entière".
  $paged = (isset($_POST['paged']) && is_numeric($_POST['paged'])) ? intval($_POST['paged']) : 1;

  // On récupère de manière sécurisée la catégorie et le format depuis la requête POST
  $category = isset($_POST['categorie']) ? sanitize_text_field($_POST['categorie']) : '';
  $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
  $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'DESC'; // Tri par défaut : du plus récent au plus ancien

  // Vérifier et filtrer le nonce (pour prévenir les attaques CSRF)
  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'loadMore_and_filters_nonce')) {
    wp_send_json_error('Vérification de sécurité échouée');
  }

  $args = array(
    'post_type' => 'photos',
    'posts_per_page' => 8,
    'orderby' => 'date',
    'order' => $sort,
    'paged' => $paged,
  );

  // Ajouter les filtres de catégorie et format si présents
  if (!empty($category)) {
    $args['tax_query'][] = array(
      'taxonomy' => 'categorie', // Nom de la taxonomie personnalisée
      'field' => 'slug', // Type de champ
      'terms' => $category, // Valeur
    );
  }

  if (!empty($format)) {
    $args['tax_query'][] = array(
      'taxonomy' => 'format',
      'field' => 'slug',
      'terms' => $format,
    );
  }

  // On exécute la requête WP_Query
  $filters_query = new WP_Query($args);

  // On initialise la réponse
  $response = array(
    'html' => '', // Initialise le contenu HTML
    'hasMore' => false, // Initialise l'indicateur de photos supplémentaires
    'success' => false, // Initialise le statut de la requête
  );

  if ($filters_query->have_posts()) {
    // On utilise la sortie tampon pour capturer le HTML généré par "get_template_part"
    ob_start();
    while ($filters_query->have_posts()) {
      $filters_query->the_post();
      get_template_part('templates_part/photo_block'); // On va chercher le template partiel.
    }
    $response['html'] = ob_get_clean(); // On récupère le HTML généré
    $response['success'] = true; // La requête a réussi.
  }

  // On vérifie s'il y a davantage de photos disponibles.
  $total_posts = $filters_query->found_posts;
  $posts_per_page = $filters_query->query_vars['posts_per_page'];
  if ($total_posts > ($paged * $posts_per_page)) {
    $response['hasMore'] = true; // On indique qu'il y a encore des photos disponibles.
  }

  wp_reset_postdata();

  wp_send_json($response);

  wp_die();
}

// Ajouter une action pour gérer la requête AJAX
add_action('wp_ajax_loadMore_and_filters', 'loadMore_and_filters_photos'); // Pour les utilisateurs connectés
add_action('wp_ajax_nopriv_loadMore_and_filters', 'loadMore_and_filters_photos'); // Pour les utilisateurs non connectés