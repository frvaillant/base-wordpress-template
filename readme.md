
# Hope you'll enjoy
Ce repository propose des bases pour développer un thème WordPress presque comme si tu étais dans le framework Symfony.

## Installation
Rends-toi dans le dossier "themes" de ton installation WordPress et clone ce repo :  
`cd wp-content/themes`  
`git clone https://github.com/frvaillant/base-wordpress-template your-desired-theme-folder-name`

Ensuite, rends-toi dans le dossier créé :  
`cd your-desired-theme-folder-name`  
et exécute les commandes suivantes :   
`git remote remove origin`  
`git remote add origin https://your-origin-repository.com`  
`composer install`  
`yarn install`  
`yarn encore dev`  

Démarre ton serveur PHP à la racine de ton site :  
`php -S localhost:9999` (choisis le port qui te plaît)  

Termine la configuration de ton WordPress si besoin.  

Dans le back-office, rends-toi dans Apparence -> Thèmes et active "Hope you'll enjoy".

Rends-toi ensuite sur http://localhost:[le-port-que-tu-as-choisi]

Si tu vois la page d'accueil du thème, tout est OK !

## Quick start
On va prendre l'exemple d'un catalogue de formations. Pour cela, on aura besoin :
- d'une page qui affiche le catalogue
- d'une entité "formation"
- de routes supplémentaires à appeler en AJAX par exemple

### Créer une page dans le back-office
Crée une première page depuis ton back-office et appelle-la "Catalogue des formations".

### Créer un template pour tes pages
Dans ton terminal, rends-toi dans le dossier du thème et lance la commande :  
`./create`  
Cela te demandera ce que tu veux créer. Tape "1".  
Donne-lui un slug comme "catalog", puis un nom compréhensible comme "Catalogue des formations".

Un nouveau contrôleur a été créé dans le namespace App\Controller : `App\Controller\CatalogController`.  
La méthode `index` de ce contrôleur devrait avoir une annotation "@Template..." qui ressemble à ça :  
```PHP
/**
* @Template(identifier="catalog", name="Catalogue des formations")
**/
```
Dans ton back-office, tu devrais pouvoir assigner ce template "Catalogue des formations" à ta page.

Une vue Twig a également été créée pour gérer l'affichage de cette page dans View\Catalog\index.html.twig.  

Tu pourrais ensuite facilement ajouter une méthode dans ton controller avec une autre annotation `@Template...` 
et ainsi disposer d'un autre modèle pour d'autres pages liées aux formations.  


### Créer une entité (custom post)
Dans ton terminal, rends-toi dans le dossier du thème et lance la commande :
`./create`  
Cela te demandera ce que tu veux créer. Cette fois, tape "0".  
Donne un nom pour la classe, comme "Formation", un nom pour le back-office au singulier "Formation", puis son nom au pluriel "Formations".  
Tu disposes maintenant d'une entité sous forme d'un custom post disponible dans ton back-office.

Dans le dossier Entity, tu as maintenant une classe Formation.php avec une annotation comme suit :
`@Entity(name="Formation", singular="Formation", plural="Formations")`  
Par défaut, ce custom post supporte tout ce qui est possible côté WordPress. Mais tu peux restreindre ces options en complétant l'annotation :  
Par exemple, `@Entity(name="Formation", singular="Formation", plural="Formations", supports={"editor", "thumbnail"})`.

Tu peux aussi si tu préfères, exclure les "supports" dont tu n'as pas besoin en utilisant exclude={} dans l'annotation.  
Par exemple, si tu ne souhaites pas utiliser l'éditeur de contenu, ni l'image mise en avant pour cette entité :  
`@Entity(name="Formation", singular="Formation", plural="Formations", exclude={"editor", "thumbnail"})`

Tu peux utiliser le plugin ACF ou SCF pour ajouter des champs complémentaires à cette entité.  
Ces champs complémentaires seront automatiquement disponibles dans ton entité en tant que propriété publique.
Tu pourras donc les appeler comme ceci :  
```PHP
    $formation = new Formation($id);
    // Si tu as nommé ton custom field  "champ_complementaire"
    $champComplementaire = $formation->champ_complementaire;
```

Pour utiliser cette entité dans ton code :
```PHP
    $formation = new Formation($id);
    //Tous les champs de cette entité (ceux d'un Post WordPress + les champs complémentaires via ACF sont accessibles directement
    $title = $formation->title; // ->post_title fonctionnera également
    $content = $formation->content; // ->post_content fonctionnera également
    $monChampComplementaire = $formation->mon_champ_complementaire;
```

### La vue
Les vues utilisent Twig. Par défaut, ton contrôleur FormationController a une méthode `index` :

```PHP
/**
* @Template(identifier="formation", name="Formation")
**/
public function index(): Response
    {
        return $this->render('Formation/index.html.twig', []);
    }
}
```

Tu peux la modifier comme ceci :

```PHP
/**
* @Template(identifier="formation", name="Formation")
**/
public function index(): Response
    {
        $page = new \App\Entity\Page(get_the_ID());
        $formation = new \App\Entity\Formation(1);
        return $this->render('Formation/index.html.twig', [
            'page' => $page,
            'formation' => $formation
        ]);
    }
}
```

Et dans ta vue, tu peux afficher les différents contenus :

```twig
    {% block body %}
    <h1>{{ page.title }}</h1>
    <div class="row">
        {{ page.content }}
    </div>
    <div class="formation">
        {{ formation.title }}
    </div>
    {% endblock %}
```

### Repository
Lors de la création de ton entité, un repository dédié a été généré.  
Les repositories se trouvent dans le dossier Model : `App\Model\FormationRepository`.  
Ce repository dispose par défaut d'une méthode `findAll()`, mais tu peux y ajouter les méthodes que tu souhaites.

Dans ton contrôleur, tu peux maintenant :

```PHP
/**
* @Template(identifier="formation", name="Formation")
**/
public function index(FormationRepository $formationRepository)
    {
        $page = new \App\Entity\Page(get_the_ID());
        $formations = $formationRepository->findAll();
        return $this->render('Formation/index.html.twig', [
            'page' => $page,
            'formations' => $formations
        ]);
    }
}
```

et adapter ta vue ainsi :

```twig
    {% block body %}
    ...
        {% for formation in formations %}
            <div class="formation">
                {{ formation.title }}
            </div>
        {% endfor %}
    ...
    {% endblock %}
```

### Twig
Quelques fonctions de WordPress sont disponibles par défaut dans tes vues.  
Par exemple : `{{ bloginfo('url') }}`  
Toutes les fonctions Twig sont disponibles dans `functions/twig.php`, et tu peux en ajouter autant que nécessaire.

Pour implémenter des fonctions Twig ou des filtres :

```PHP
    $twig->addFunction(
        new \Twig\TwigFunction('is_user_logged_in', function () {
            return is_user_logged_in();
        })
    );
    // dans tes vues : {% if is_user_logged_in() %}

    $twig->addFilter(
        new \Twig\TwigFilter('slice', function ($string, $start, $length) {
            return substr($string, $start, $length) . '...';
        })
    );
    // dans tes vues : {{ page.title | slice(0, 10) }}
```

### Besoin d'une route (URL) spécifique sans créer de page
Dans ton contrôleur, crée une méthode et ajoute-lui l'annotation @Route (Symfony\Component\Routing\Annotation\Route) :

```PHP
    /**
     * @Route("/formation-author/{formation}", name="formation_author", methods={"GET", "POST"})
     */
    public function formationAuthor(Formation $formation): Response
    {
        $author = $formation->author; //This returns a WP_User object
        return $this->publish(
            $this->twig->render('Formation/index.html.twig', [
                'author' => $author
            ])
        );
    }
```

### Style et JavaScript
Comme dans Symfony, utilise le dossier `assets` pour ajouter ton CSS (avec SCSS) et ton JavaScript.  
Compile avec `yarn encore dev`.

Tu peux également utiliser Stimulus dans ton thème.

---
