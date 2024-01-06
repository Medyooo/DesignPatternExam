
# Titre du Projet

## Table des Matières
- [Réponses aux Questions](#réponses-aux-questions)
- [Design Pattern: Dependency Injection](#design-pattern-dependency-injection)
- [Contexte](#contexte)
- [Avantages/Inconvénients](#avantagesinconvénients)
- [Variation Optionnelle](#variation-optionnelle)
- [Diagramme de Classes UML](#diagramme-de-classes-uml)
- [Lancer le Projet](#lancer-le-projet)
- [Code Source de la Démo](#code-source-de-la-démo)

## Réponses aux Questions
### Programmer vers une interface plutôt qu'une implémentation

**Avantages :**
- **Découplage :** Les composants du programme interagissent avec des interfaces, pas des implémentations directes, ce qui réduit les dépendances entre les composants.
- **Flexibilité :** Les objets peuvent changer de comportements en temps réel en changeant les implémentations concrètes derrière les interfaces.
- **Testabilité :** Les interfaces facilitent les tests unitaires en permettant de créer des objets simulés (mocks) qui implémentent l'interface.
- **Extensibilité :** Il est plus facile d'ajouter de nouvelles implémentations sans perturber le système existant.

**Exemple de code en PHP :**
```php
// Interface de processeur de paiement
interface PaymentProcessor {
    public function process($amount);
}

// Implémentation pour le processeur Stripe
class StripeProcessor implements PaymentProcessor {
    public function process($amount) {
        echo "Paiement de $amount traité via Stripe.\n";
    }
}

// Service de paiement utilisant un processeur de paiement
class PaymentService {
    private $processor;

    public function __construct(PaymentProcessor $processor) {
        $this->processor = $processor;
    }

    public function makePayment($amount) {
        $this->processor->process($amount);
    }
}

// Utilisation du service avec Stripe
$stripeProcessor = new StripeProcessor();
$paymentService = new PaymentService($stripeProcessor);
$paymentService->makePayment(50);  // Sortie: Paiement de 50 traité via Stripe.
```

**Diagramme :**
![Diagramme Exemple](/project/images/ex1exemple2.png "Diagramme Exemple")

### Préférer la composition à l'héritage

**Justifications :**
- **Souplesse :** Avec la composition, il est possible de modifier le comportement d'un objet à l'exécution en changeant ses composants, ce qui n'est pas possible avec l'héritage fixe.
- **Réduction de la complexité :** L'héritage peut conduire à des hiérarchies compliquées et rigides, tandis que la composition favorise des structures plus simples et modulaires.
- **Évitement des problèmes d'héritage multiple :** L'héritage multiple peut conduire à des problèmes complexes tels que le "diamant de la mort", qui sont évités avec la composition.

**Exemple de code en PHP :**
```php
// Classe de base pour le comportement de mouvement
class MovementBehavior {
    public function move() {
        echo "Se déplace d'une manière générique.\n";
    }
}

// Comportement de mouvement spécifique pour la marche
class WalkingBehavior extends MovementBehavior {
    public function move() {
        echo "Se déplace en marchant.\n";
    }
}

// Comportement de mouvement spécifique pour le vol
class FlyingBehavior extends MovementBehavior {
    public function move() {
        echo "Se déplace en volant.\n";
    }
}

// Classe Robot utilisant la composition pour son comportement de mouvement
class Robot {
    private $movementBehavior;

    public function __construct(MovementBehavior $movementBehavior) {
        $this->movementBehavior = $movementBehavior;
    }

    public function move() {
        $this->movementBehavior->move();
    }

    // Permet de changer le comportement de mouvement à la volée
    public function setMovementBehavior(MovementBehavior $movementBehavior) {
        $this->movementBehavior = $movementBehavior;
    }
}

// Utilisation
$walkingRobot = new Robot(new WalkingBehavior());
$walkingRobot->move();  // Sortie: Se déplace en marchant.

// Changement de comportement à la volée
$walkingRobot->setMovementBehavior(new FlyingBehavior());
$walkingRobot->move();  // Sortie: Se déplace en volant.

```

**Diagramme :**
![Diagramme Exemple](/project/images/ex2exemple2.png "Diagramme Exemple")


### Définition d'une interface en programmation orientée objet

Une interface en programmation orientée objet est un contrat qui définit un ensemble de méthodes abstraites sans implémentations spécifiques. Les classes qui implémentent cette interface s'engagent à fournir des implémentations concrètes pour toutes les méthodes définies. Elle sert à définir une forme standard que doivent prendre les objets pour pouvoir interagir entre eux, permettant ainsi une plus grande modularité et flexibilité dans la conception et l'implémentation des systèmes logiciels.


## Design Pattern: Dependency Injection

### 1. Choix du Design Pattern
**Design Pattern choisi :**  Injection de Dépendances (Dependency Injection)

**Source :** Catalogue du Gang of Four (GoF).

### 2. Contexte d'Utilisation
**Contexte fictif :**  Développement d'une application web pour afficher des informations météorologiques avec la flexibilité d'utiliser différents services de données météorologiques sans modification du code principal.

### 3. Avantages/Inconvénients
**Avantages :**
- **Flexibilité :**  Les classes ne sont pas liées à des implémentations spécifiques de leurs dépendances.
- **Testabilité :** Facilite les tests en permettant l'insertion de fausses dépendances.
- **Maintenabilité :** Le code est plus propre et plus facile à maintenir grâce à la centralisation des dépendances.

**Inconvénients :**
- **Complexité :**  Peut introduire une complexité supplémentaire avec la gestion d'un conteneur d'injection de dépendances.
- **Sur-ingénierie :** Peut être considéré comme excessif pour les petits projets.

### Variation Optionnelle : Utilisation d'un Conteneur d'Injection de Dépendances

#### Description de la Variation :
Un conteneur d'injection de dépendances est un objet centralisé qui connaît les différents services et leurs dépendances. Il est responsable de la création et de la fourniture des instances de service en résolvant leurs dépendances. Cela simplifie la gestion des dépendances, surtout dans les grandes applications.

#### Mise en œuvre de la Variation :
- **Création d'un conteneur :** Implémenter un conteneur simple qui stocke des fonctions anonymes ou des classes responsables de la création de services spécifiques.
- **Résolution des dépendances :** Le conteneur doit être capable de résoudre automatiquement les dépendances des services lors de leur création.

#### Code Exemplaire :

```php
<?php
class DependencyContainer {
    protected $services = [];

    public function set($name, $service) {
        $this->services[$name] = $service;
    }

    public function get($name) {
        if (!isset($this->services[$name])) {
            throw new Exception("Service not found: " . $name);
        }
        return call_user_func($this->services[$name], $this);
    }
}

// Configuration du conteneur
$container = new DependencyContainer();
$container->set('weatherService', function($c) {
    return new OpenWeatherMapService(); // ou une autre implémentation
});
$container->set('app', function($c) {
    return new WeatherApp($c->get('weatherService'));
});

// Utilisation de l'application via le conteneur
$app = $container->get('app');
$app->showWeather("Paris");
?>
```

#### Justification de la Variation :
- **Gestion Centralisée :** Un conteneur centralise la gestion des dépendances, rendant le code plus propre et plus facile à maintenir.
- **Flexibilité Améliorée :** Il devient plus facile de changer les implémentations des services sans modifier le code consommateur.
- **Meilleure Scalabilité :**  Au fur et à mesure que le projet grandit, ajouter de nouvelles dépendances et services devient plus gérable.

## Lancer le Projet
Pour exécuter cette démo du design pattern d'Injection de Dépendances en PHP, suivez ces étapes :

1. **Clonez le dépôt :** Commencez par cloner le dépôt GitHub où se trouve le projet. Utilisez la commande `git clone [URL_du_dépôt]` dans votre terminal pour télécharger le projet sur votre machine locale.

2. **Assurez-vous d'avoir PHP installé :** Votre ordinateur doit avoir PHP installé pour exécuter des scripts PHP. Vous pouvez vérifier cela en exécutant `php -v` dans votre terminal.

3. **Ouvrez un terminal :** Ouvrez un terminal ou une invite de commande.

4. **Naviguez jusqu'au script :** Utilisez la commande `cd [nom_du_dossier_cloné]` pour naviguer jusqu'au répertoire où vous avez cloné le dépôt.

5. **Exécutez le script :** Tapez `php dependency_injection_demo.php` dans votre terminal et appuyez sur Entrée. Si tout est correctement configuré, vous devriez voir le résultat de l'exécution du script.

## Code Source de la Démo
```php
<?php
// Interface définissant le contrat pour les services météorologiques.
// Tous les services météorologiques doivent implémenter cette interface.
interface WeatherService {
    // Méthode pour obtenir la météo en fonction de la localisation.
    public function getWeather($location);
}

// Implémentation concrète de l'interface WeatherService utilisant OpenWeatherMap.
class OpenWeatherMapService implements WeatherService {
    // Renvoie la météo pour une localisation spécifique.
    // Ici, un exemple simplifié est retourné. Dans une application réelle,
    // cela pourrait impliquer de récupérer des données depuis une API externe.
    public function getWeather($location) {
        return "Temps ensoleillé à $location";
    }
}

// Classe principale de l'application météorologique.
class WeatherApp {
    // Référence à un service météorologique, suivant le principe d'injection de dépendance.
    private $weatherService;

    // Le constructeur accepte n'importe quel service implémentant WeatherService.
    // Cela rend la classe WeatherApp flexible et facile à étendre ou modifier.
    public function __construct(WeatherService $weatherService) {
        $this->weatherService = $weatherService;
    }

    // Méthode pour afficher la météo pour une localisation spécifique.
    // Utilise le service météorologique injecté pour obtenir les données.
    public function showWeather($location) {
        echo $this->weatherService->getWeather($location);
    }
}

// Création d'un service météorologique OpenWeatherMap.
$service = new OpenWeatherMapService();
// Création d'une instance de WeatherApp avec le service OpenWeatherMap injecté.
$app = new WeatherApp($service);
// Affichage de la météo pour Paris en utilisant l'application météorologique.
$app->showWeather("Paris");  // Sortie : Temps ensoleillé à Paris
?>

```

## Conclusion
Ce README fournit une vue d'ensemble de l'implémentation du design pattern d'Injection de Dépendances en PHP. Il inclut le contexte de son utilisation, les avantages et les inconvénients, une implémentation en PHP, ainsi que les instructions pour exécuter une démo simple. Pour toute question ou clarification, n'hésitez pas à consulter la documentation PHP ou à contacter le créateur de ce dépôt.
