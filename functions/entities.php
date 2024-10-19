<?php

/**
 * Entités complémentaires
 * @param $entityName
 * @param $singular
 * @param $plurial
 */

function create_custom_post_type($entityName, $singular, $plurial, $supports = ['title', 'author', 'revisions', 'custom-fields', 'tags', 'page-attributes'])
{
    // On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
    $labels = array(
        // Le nom au pluriel
        'name'                => _x( $plurial, 'Post Type General Name'),
        // Le nom au singulier
        'singular_name'       => _x( $singular, 'Post Type Singular Name'),
        // Le libellé affiché dans le menu
        'menu_name'           => __($plurial),
        // Les différents libellés de l'administration
        'all_items'           => __( 'Les ' . $plurial),
        'view_item'           => __( 'Voir les ' .$plurial),
        'add_new_item'        => __( 'Ajouter ' . $singular),
        'add_new'             => __( 'Ajouter'),
        'edit_item'           => __( 'Editer ' . $singular),
        'update_item'         => __( 'Modifier ' . $singular),
        'search_items'        => __( 'Rechercher ' . $singular),
        'not_found'           => __( 'Non trouvé'),
        'not_found_in_trash'  => __( 'Non trouvé dans la corbeille'),
    );

    // On peut définir ici d'autres options pour notre custom post type

    $args = array(
        'label'               => __($plurial),
        'description'         => __( 'Tous sur les ' . $plurial),
        'labels'              => $labels,
        // On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
        'supports'            => $supports,
        'taxonomies'          => array($entityName,  'post_tag' ),
        /*
        * Différentes options supplémentaires
        */
        'show_in_rest'        => true,
        'hierarchical'        => false,
        'public'              => true,
        'has_archive'         => true,
        'rewrite'			  => array( 'slug' => $entityName),

    );

    register_post_type( $entityName, $args );
}



function entities() {
    $entityCollector = new \App\Collectors\EntitiesCollector();
    /** @var \App\Annotations\Entity $entity */
    foreach ($entityCollector->getEntities() as $entity) {
        create_custom_post_type($entity->getName(), $entity->getSingular(), $entity->getPlural());
    }
}
add_action( 'init', 'entities', 0 );

