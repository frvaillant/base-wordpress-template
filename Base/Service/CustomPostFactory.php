<?php

namespace App\Base\Service;

use App\Base\Annotations\Entity;

/**
 * This class creates elements to define a new custom post as an entity
 */
final class CustomPostFactory
{
    private Entity $entity;
    private string $entityName;
    private string $singular;
    private string $plural;
    private array $supports;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->entityName = $entity->getName();
        $this->singular = $entity->getSingular();
        $this->plural = $entity->getPlural();
        $this->supports = $entity->getSupports();
    }

    /**
     * @return array
     */
    public function createCustomPostLabels(): array
    {
        return [
            'name' => _x( $this->plural, 'Post Type General Name'),
            'singular_name' => _x( $this->singular, 'Post Type Singular Name'),
            'menu_name' => __($this->plural),
            'all_items' => __( 'Les ' . $this->plural),
            'view_item' => __( 'Voir les ' .$this->plural),
            'add_new_item' => __( 'Ajouter 1 ' . $this->singular),
            'add_new' => __( 'Ajouter'),
            'edit_item' => __( 'Editer ' . $this->singular),
            'update_item' => __( 'Modifier ' . $this->singular),
            'search_items' => __( 'Rechercher ' . $this->singular),
            'not_found' => __( 'Non trouvé'),
            'not_found_in_trash' => __( 'Non trouvé dans la corbeille'),
        ];
    }

    /**
     * @return array
     */
    public function createCustomPostArguments(): array
    {
        return [
            'label' => __($this->plural),
            'description' => __( 'Tous sur les ' . $this->plural),
            'labels' => $this->createCustomPostLabels(),
            'supports' => $this->supports,
            'taxonomies' => ['post_tag'],
            'show_in_rest' => true,
            'hierarchical' => false,
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => $this->entityName],
        ];
    }
}
