#!/usr/bin/env php
<?php
require_once ( __DIR__ . '/EntityCreator/EntityCreate.php');
require_once ( __DIR__ . '/TemplateCreator/TemplateCreate.php');
require_once ( __DIR__ . '/Colors.php');

if (isset($argv[1])) {
    $action = $argv[1];
    switch ($action) {
        case 'entity':
            if (isset($argv[2]) && isset($argv[3]) && isset($argv[4])) {
                $creator = new EntityCreate($argv);
                $creator->create();
                echo $creator->getMessage()['status']($creator->getMessage()['message']);
            } else {
                echo error('Il manque des arguments ! Attendus : php console/create entity "entityName" "singular name" "plurial name"');
            }
            break;
        case 'template':
            if (isset($argv[2]) && isset($argv[3])) {
                $creator = new TemplateCreate($argv);
                $creator->create();
                echo $creator->getMessage()['status']($creator->getMessage()['message']);
            } else {
                echo error('Il manque des arguments ! Attendus : php console/create template "className" "french name"');
            }
            break;

        default:
            echo nothing();
            break;
    }
} else {
    echo error("Vous n'avez pas précisé ce que vous vouliez créer");
}

function error($error)
{
    if(!$error || $error === '') {
        $error = 'Nous n\'avons pas réussi à déterminer la source de l\'erreur';
    }
    $colors = new Colors();
    return $colors->getColoredString(
        PHP_EOL . PHP_EOL . ':-( *********** ERROR ***********' . PHP_EOL . $error . PHP_EOL . '**********************************' . PHP_EOL . PHP_EOL,
        'red'
    );
}

function success($message)
{
    $colors = new Colors();
    return $colors->getColoredString(
        PHP_EOL . PHP_EOL . ':-) ********** SUCCESS ***********' . PHP_EOL . $message . PHP_EOL . '**********************************' . PHP_EOL . PHP_EOL,
        'green'
    );
}


function nothing()
{
    $colors = new Colors();
    return $colors->getColoredString(
        PHP_EOL . PHP_EOL . ':-| ********** ??? ***********' . PHP_EOL . 'Rien n\'a été effectué' . PHP_EOL . '******************************' . PHP_EOL . PHP_EOL,
        'yellow'
    );
}
