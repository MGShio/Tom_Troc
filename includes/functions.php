<?php
// Fonction wrapper pour récupérer les résultats sous forme de tableau associatif
function fetch_classes($result)
{
    return mysqli_fetch_assoc($result);
}

// Récupération des livres disponibles
function get_livres_disponibles()
{
    $query = "SELECT * FROM livres WHERE statut = 'disponible'";
    $result = db_query($query);
    $livres = [];
    while ($row = fetch_classes($result)) {
        $livres[] = $row;
    }
    return $livres;
}

// Récupération des livres d'un utilisateur
function get_livres_utilisateur($user_id)
{
    $user_id = (int)$user_id;
    $query = "SELECT * FROM livres WHERE utilisateur_id = $user_id";
    $result = db_query($query);
    $livres = [];
    while ($row = fetch_classes($result)) {
        $livres[] = $row;
    }
    return $livres;
}

// Recherche de livres
function rechercher_livres($terme)
{
    $terme = db_escape($terme);
    $query = "SELECT * FROM livres WHERE titre LIKE '%$terme%' AND statut = 'disponible'";
    $result = db_query($query);
    $livres = [];
    while ($row = fetch_classes($result)) {
        $livres[] = $row;
    }
    return $livres;
}

// Récupération des détails d'un livre
function get_livre($livre_id)
{
    $livre_id = (int)$livre_id;
    $query = "SELECT livres.*, utilisateurs.nom FROM livres JOIN utilisateurs ON livres.utilisateur_id = utilisateurs.id WHERE livres.id = $livre_id";
    $result = db_query($query);
    return $result ? fetch_classes($result) : null;
}

// Récupération des messages d'un utilisateur
function get_messages_utilisateur($user_id)
{
    $user_id = (int)$user_id;
    $query = "SELECT * FROM messages WHERE destinataire_id = $user_id ORDER BY date_envoi DESC";
    $result = db_query($query);
    $messages = [];
    while ($row = fetch_classes($result)) {
        $messages[] = $row;
    }
    return $messages;
}

// Récupération d'un fil de discussion
function get_fil_discussion($user_id, $correspondant_id)
{
    $user_id = (int)$user_id;
    $correspondant_id = (int)$correspondant_id;
    $query = "SELECT * FROM messages WHERE (expediteur_id = $user_id AND destinataire_id = $correspondant_id) OR (expediteur_id = $correspondant_id AND destinataire_id = $user_id) ORDER BY date_envoi ASC";
    $result = db_query($query);
    $messages = [];
    while ($row = fetch_classes($result)) {
        $messages[] = $row;
    }
    return $messages;
}

// Envoi d'un message
function envoyer_message($expediteur_id, $destinataire_id, $contenu)
{
    $expediteur_id = (int)$expediteur_id;
    $destinataire_id = (int)$destinataire_id;
    $contenu = db_escape($contenu);
    $query = "INSERT INTO messages (expediteur_id, destinataire_id, contenu, date_envoi) VALUES ($expediteur_id, $destinataire_id, '$contenu', NOW())";
    return db_query($query);
}

// Récupération des derniers livres ajoutés (par défaut 6)
function get_derniers_livres($limit = 6)
{
    $limit = (int)$limit;
    if ($limit <= 0) {
        $limit = 6;
    }
    $query = "SELECT * FROM livres WHERE statut = 'disponible' ORDER BY id DESC LIMIT $limit";
    $result = db_query($query);
    $livres = [];
    while ($row = fetch_classes($result)) {
        $livres[] = $row;
    }
    return $livres;
}
