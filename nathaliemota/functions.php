<?php

// Chargement du CSS personnalisé

function theme_enqueue_styles() {
  wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . './style.css' );
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

