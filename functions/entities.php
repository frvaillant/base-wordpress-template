<?php

use App\Base\Annotations\Entity;
use App\Base\Collectors\EntitiesCollector;
use App\Base\Service\CustomPostFactory;

/**
 * @param Entity $entity
 * @return void
 */
function create_custom_post_type(Entity $entity): void
{
    $cptFactory = new CustomPostFactory($entity);
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
