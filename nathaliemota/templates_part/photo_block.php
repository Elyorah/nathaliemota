<?php

// Template qui gère l'affichage d'un block photo

$post_permalink = get_permalink();

// Récupérer la référence et la catégorie
$reference = get_field('reference');
$categories = get_the_terms(get_the_ID(), 'categorie');
// $title = get_the_title();

if ($categories && !is_wp_error($categories)) {
    $category_names = wp_list_pluck($categories, 'name');
    $category_names = implode(', ', $category_names);
} else {
    $category_names = '';
}

// Ajout de l'index pour chaque photo
global $photo_index; // Déclaration de la variable globale pour l'index

if (!isset($photo_index)) {
    $photo_index = 0; // Initialisation de l'index
} else {
    $photo_index++; // Incrément de l'index
}

?>

<div class="photo-block" data-index="<?php echo esc_attr($photo_index); ?>">
    <a href="<?php echo esc_url($post_permalink); ?>">

        <?php the_post_thumbnail('full', [          // Affichage de la photo
            'class' => 'main-photo',                // Ajout de la classe 'main-photo'
            'data-reference' => esc_attr($reference),
            'data-category' => esc_attr($category_names)
        ]); ?>

        <div class="photo-overlay">                         <!-- Affichage de l'overlay noire -->
            <div class="icon-eye">                          <!-- Icone "oeil" qui redirige vers la page de photo single -->
                <a href="<?php echo esc_url($post_permalink); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/lightbox-eye.svg" alt="View Photo">
                </a>
            </div>
            <div class="icon-fullscreen">                   <!-- Icone rectangulaire qui affiche la photo en plein écran dans la lightbox -->
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/lightbox-fullscreen.svg" alt="Fullscreen">
            </div>

            <div class="photo-details">
                <div class="photo-reference poppins-medium"><?php echo esc_html($reference); ?></div>
                <div class="photo-category spacemono-regular"><?php echo esc_html($category_names); ?></div>
            </div>

        </div>

    </a>
</div>
