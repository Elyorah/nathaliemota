<?php

// Chargement du CSS personnalisé

function theme_enqueue_styles() {
  wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . '/assets/css/theme.css' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

// Ajouter automatiquement le titre du site dans l'en-tête du site
add_theme_support( 'title-tag' );

// Ajouter la prise en charge des images mises en avant
add_theme_support( 'post-thumbnails' );

// Enregistrement du Menu de Navigation et du Menu Footer

function register_main_menu() {
  register_nav_menu( 'main-menu', __( 'Menu principal', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_main_menu' );

function register_footer_menu() {
  register_nav_menu( 'footer-menu', __( 'Menu footer', 'text-domain' ) );
}
add_action( 'after_setup_theme', 'register_footer_menu' );

