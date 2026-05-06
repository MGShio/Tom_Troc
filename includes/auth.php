<?php
// Vérification de la connexion
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

// Connexion de l'user
function login($email, $password)
{
    $connection = db_connect();
    $stmt = $connection->prepare('SELECT id, email, password FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !empty($user['password']) && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        return true;
    }

    return false;
}

// Inscription de l'user
function register($name, $email, $password)
{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $connection = db_connect();
    $stmt = $connection->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
    return $stmt->execute([$name, $email, $hashed_password]);
}

// Récupération des informations de l'user
function get_user($user_id)
{
    $connection = db_connect();
    $stmt = $connection->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}