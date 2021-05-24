#!/usr/bin/env php
<?php
require_once ( __DIR__ . '/EntityCreator/EntityCreate.php');
require_once ( __DIR__ . '/TemplateCreator/TemplateCreate.php');
require_once ( __DIR__ . '/Colors.php');


$action = readline('Que voulez vous créer ? (entity/template) :');
if($action === 'entity') {
    $entityName = readline('Donnez lui un nom pour le code (Post, Article ...) :');
    $singular = readline('Son nom en Francais pour l\'affichage au singulier (Article, Journal ...) :');
    $plurial = readline('Son nom en Francais pour l\'affichage au pluriel (Articles, Journaux ...) :');
}

if($action === 'template') {
    $className = readline('Nom de la class (Post, Article ...) :');
    $frenchTemplateName = readline('Son nom en Francais (Affichage article; page publication ...) :');
}

if ($action) {
    switch ($action) {
        case 'entity':
            if ($entityName && $singular && $plurial) {
                $creator = new EntityCreate([
                    'entity' => $entityName,
                    'singular' => $singular,
                    'plurial' => $plurial
                ]);
                $creator->create();
                echo $creator->getMessage()['status']($creator->getMessage()['message']);
            } else {
                echo error('Il manque des arguments ! Attendus : php console/create entity "entityName" "singular name" "plurial name"');
            }
            break;
        case 'template':
            if ($className && $frenchTemplateName) {
                $creator = new TemplateCreate([
                    'className' => $className,
                    'frenchName' => $frenchTemplateName,
                ]);
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

function message($message) {
    $colors = new Colors();
    return $colors->getColoredString($message, 'green');
}

