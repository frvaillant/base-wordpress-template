<?php

use App\Annotations\Entity;
use App\Collectors\EntitiesCollector;
use App\Service\CptFactory;

/**
 * Entités complémentaires
 * @param $entityName
 * @param $singular
 * @param $plurial
 */
function create_custom_post_type(Entity $entity): void
{
    $cptFactory = new CptFactory($entity);
    register_post_type( $entity->getName(), $cptFactory->createCustomPostArguments());
}

/**
 * @return void
 */
function entities(): void
{
    $entityCollector = new EntitiesCollector();

    /** @var Entity $entity */
    foreach ($entityCollector->getEntities() as $entity) {
        create_custom_post_type($entity);
    }
}
add_action( 'init', 'entities', 0 );

