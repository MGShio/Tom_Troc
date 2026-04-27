<?php
// Vérification de la connexion
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

// Connexion de l'utilisateur
function login($email, $password)
{
    $email = db_escape($email);
    $password = db_escape($password);
    $query = "SELECT * FROM utilisateurs WHERE email = '$email' AND mot_de_passe = '$password'";
    $result = db_query($query);
    if ($result && mysqli_num_rows($result) == 1) {
        $user = fetch_classes($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        return true;
    }
    return false;
}

// Inscription de l'utilisateur
function register($nom, $email, $password)
{
    $nom = db_escape($nom);
    $email = db_escape($email);
    $password = db_escape($password);
    $query = "INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES ('$nom', '$email', '$password')";
    return db_query($query);
}

// Récupération des informations de l'utilisateur
function get_user($user_id)
{
    $user_id = (int)$user_id;
    $query = "SELECT * FROM utilisateurs WHERE id = $user_id";
    $result = db_query($query);
    return $result ? fetch_classes($result) : null;
}