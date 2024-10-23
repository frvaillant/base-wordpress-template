# Hope you'll enjoy
Ce repository propose des bases pour développer un thème wordpress quasiment comme en étant dans le framework symfony.  

## Installation
Rendez vous le dossier "themes" de votre installation de wordpress et clonez y ce repo
`cd wp-content/themes`  
`git clone https://github.com/frvaillant/base-wordpress-template your-desired-theme-folder-name`  

Rendez-vous dans le dossier créé, lancez y les commandes suivantes :
`cd your-desired-theme-folder-name`
`git remote remove origin`  
optionnel : `git remote add origin https//your-origin-repository.com`  
`composer install`  
`yarn install`  
`yarn encore dev`  

Démarrez votre serveur php à la racine de votre site  
`php -S localhost:9999` (choisissez le port qui vous plait)  

Terminez la configuration de votre wordpress si besoin. 

Dans le back-office, rendez-vous dans apparence->thèmes et activez "hope you'll enjoy"  

Rendez-vous sur http://localhost:[le-port-que-vous-avez-choisi]  

Si vous voyez la page d'accueil du thème, c'est que tout est OK :-)


## Quick start
Nous allons prendre l'exemple d'un catalogue de formations. Pour cela nous aurons besoin  
- d'une page qui affiche le catalogue
- d'une entité "formation"
- De routes complémentaires à appeler en ajax par exemple

### Créer une page dans la back office
Créez une première page depuis votre back office et appelez la "Catalogue des formations".

### Créer un template pour vos pages
Dans votre terminal, rendez-vous dans le dossier du thème et lancez :  
`php console/create`
Cela vous demandera ce que vous voulez créer. Tapez "template".  
Donnez-lui un nom "catalog" par exemple, Puis un nom compréhensible "Catalogue des formations".  


Un nouveau controller a été créé dans le namespace App\Controller : `App\Controller\CatalogController`.  
La méthode index de ce controller devrait avoir une annotation "@Template...".  
Et dans votre backoffice, vous devriez pouvoir assigner ce template[=modèle] -> "catalogue des formations" à votre page.  

Une vue Twig a également été créée pour gérer l'affichage de cette page dans View\Catalog\index.html.twig

### Créer une entité (custom post)
Dans votre terminal, rendez-vous dans le dossier du thème et lancez :  
`php console/create`
Cela vous demandera ce que vous voulez créer. Tapez cette fois "entity".  
Donnez le nom pour la classe : "Formation"  
Un nom pour le back office au singulier : "Formation"  
Puis son nom au pluriel : "Formations"  
Vous disposez désormais d'une entité sous la forme d'un custom post disponible dans votre back office.  

Dans le dossier Entity, vous avez désormais une classe Formation.php qui utilise une annotation :  
`@Entity(name="Formation", singular="Formation", plural="Formations")`  
Par défaut, ce custom post supporte tout ce qui est possible côté wordpress. Mais vous pouvez restreindre ces options en complétant l'annotation :  
Par exemple : `@Entity(name="Formation", singular="Formation", plural="Formations", supports={"editor", "thumbnail"})`

Vous pouvez utiliser le plugin ACF ou SCF pour ajouter des champs complémentaires à cette entité.  

Pour utiliser cette entité dans le code :  
```PHP 
    $formation = new Formation($id);
    //Tous les champs de cette entité (ceux d'un Post wordpress + les champs complémentaires via ACF sont accessibles directement 
    $title = $formation->title; // ->post_title fonctionnera également
    $content = $formation->content; // ->post_content fonctionnera également
    $monChampComplementaire = $formation->mon_champ_complementaire;
```

### La vue  
Les vues utilisent twig. Par défaut, votre controlleur FormationController a une méthode index :

```PHP 
/**
* @Template(identifier="formation", name="Formation")
**/
public function index()
    {
        return $this->publish(
            $this->twig->render('Formation/index.html.twig', [
            ])
        );
    }
}
```

Cette méthode sera appelée pour visualiser votre page "Catalogue des formations".
Vous pouvez la modifier comme ceci :

```PHP
/**
* @Template(identifier="formation", name="Formation")
**/
public function index()
    {
        $page = new \App\Entity\Page(get_the_ID());
        $formation = new \App\Entity\Formation(1);
        return $this->publish(
            $this->twig->render('Formation/index.html.twig', [
                'page' => $page,
                'formation' => $formation
            ])
        );
    }
}
```

Et dans votre vue, vous pouvez afficher les différents contenus :

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
Lors de la création de votre entité, nous vous avons généré un repository dédié.  
Les repositories sont dans le dossier Model. `App\Model\FormationRepository`  
Ce repository dispose par défaut d'une méthode findAll() mais vous pouvez y créer toutes les méthodes que vous souhaitez.  

Ainsi dans votre controlleur, vous pouvez désormais : 
```PHP
/**
* @Template(identifier="formation", name="Formation")
**/
public function index(FormationRepository $formationRepository)
    {
        $page = new \App\Entity\Page(get_the_ID());
        $formations =$formationRepository->findAll();
        return $this->publish(
            $this->twig->render('Formation/index.html.twig', [
                'page' => $page,
                'formations' => $formations
            ])
        );
    }
}
```
et donc adapter votre vue avec  

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
Quelques fonctions de wordpress sont disponibles par défaut dans vos vues.  
Par exemple `{{ bloginfo('url') }}`  
Toutes les fonctions twig sont disponibles dans functions/twig.php et vous pouvez en ajouter autant que nécessaire.  
Vous pouvez implémenter des fonctions twig ou des filtres :  

```PHP 
    $twig->addFunction(
        new \Twig\TwigFunction('is_user_logged_in', function () {
            return is_user_logged_in();
        })
    );
    // dans vos vues : {% if is_user_logged_in() %}
    
    $twig->addFilter(
        new \Twig\TwigFilter('slice', function ($string, $start, $length) {
            return substr($string, $start, $length) . '...';
        })
    );
    // dans vos vues : {{ page.title | slice(0, 10) }}

```
### Besoin d'une route (URL) spécifique sans pour autant créer une page
Dans votre controller créez une méthode et ajoutez lui l'annotation @Route (Symfony\Component\Routing\Annotation\Route) : 

```PHP 
    /**
     * @Route("/formation-author/{formation}", name="formation_author", methods={"GET", "POST"})
     */
    public function formationAuthor(Formation $formation): Response
    {
        $author = $formation->author; //This return a WP_User object
        return $this->publish(
            $this->twig->render('Formation/index.html.twig', [
                'author' => $author
            ])
        );
    }
```

Attention, les méthodes qui utilisent l'annotation @Route doivent renvoyer une réponse de type Symfony\Component\HttpFoundation\Response et être typées comme tel.  

### Style et javascript

Comme dans symfony, utilisez le dossier assets pour ajouter votre css (avec scss) et votre javascript.  
Compilez avec `yarn encore dev`  

Vous pouvez également utiliser stimulus dans votre thème.

## Notes sur l'injection de dépendances et le param converter

```PHP 
    /**
     * @Route("/formation-author/{formation}", name="formation_author", methods={"GET", "POST"})
     */
    public function formationAuthor(Formation $formation): Response
    {
    }
```
En procédant ainsi sur les routes déclarées avec l'annotation @Route, le param converter instanciera automatiquement votre formation à partir de l'id fourni en paramètre.


Sur les méthodes des controlleurs utilisant l'annotation @Route ou @Template, vous pouvez injecter tous les services dont
vous avez besoin à condition que ces services puissent être instanciés en faisant :
```PHP 
    $service = new MonService()
```

Une injection de dépendances plus performante arrive bientôt.