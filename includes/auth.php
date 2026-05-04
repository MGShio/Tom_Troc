<?php
// Vérification de la connexion
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

// Connexion de l'utilisateur
function login($email, $password)
{
    $connection = db_connect();
    $stmt = mysqli_prepare($connection, "SELECT id, email, mot_de_passe FROM utilisateurs WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $db_email, $hashed_password);
    if (mysqli_stmt_fetch($stmt) && $hashed_password) {
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_email'] = $db_email;
            mysqli_stmt_close($stmt);
            return true;
        }
    }
    mysqli_stmt_close($stmt);
    return false;
}

// Inscription de l'utilisateur
function register($nom, $email, $password)
{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $connection = db_connect();
    $stmt = mysqli_prepare($connection, "INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $nom, $email, $hashed_password);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Récupération des informations de l'utilisateur
function get_user($user_id)
{
    $connection = db_connect();
    $stmt = mysqli_prepare($connection, "SELECT * FROM utilisateurs WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;
}