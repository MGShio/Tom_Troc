<?php
/**
 * Classe utilitaire pour TomTroc
 * Contient des méthodes statiques utilisables partout dans l'application
 */
class Utils
{
    /**
     * Redirige vers une URL
     * @param string $url URL de destination
     */
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit();
    }

    /**
     * Vérifie si l'utilisateur est connecté
     * @return bool True si connecté, false sinon
     */
    public static function isUserConnected(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Formate une date en texte lisible (ex: "2 ans", "3 mois", "5 jours")
     * @param string|null $dateString Date à formater
     * @return string Date formatée
     */
    public static function format(?string $dateString): string
    {
        if ($dateString === null || $dateString === '') {
            return "à l'instant";
        }
        
        try {
            $date = new DateTime($dateString);
            $now = new DateTime();
            $interval = $now->diff($date);
            
            if ($interval->y >= 1) {
                return $interval->y . ' an' . ($interval->y > 1 ? 's' : '');
            } elseif ($interval->m >= 1) {
                return $interval->m . ' mois';
            } elseif ($interval->d >= 1) {
                return $interval->d . ' jour' . ($interval->d > 1 ? 's' : '');
            } elseif ($interval->h >= 1) {
                return $interval->h . ' heure' . ($interval->h > 1 ? 's' : '');
            } else {
                return "à l'instant";
            }
        } catch (Exception $e) {
            return "à l'instant";
        }
    }

    /**
     * Génère un token CSRF
     * @return string Token généré
     */
    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie un token CSRF
     * @param string $token Token à vérifier
     * @return bool True si valide
     */
    public static function verifyCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Nettoie une chaîne pour l'affichage HTML
     * @param string $string Chaîne à nettoyer
     * @return string Chaîne nettoyée
     */
    public static function sanitize(string $string): string
    {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Vérifie si une chaîne est vide
     * @param mixed $value Valeur à vérifier
     * @return bool True si vide
     */
    public static function isEmpty($value): bool
    {
        return empty(trim($value));
    }
}
