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
