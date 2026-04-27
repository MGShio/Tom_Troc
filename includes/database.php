<?php
// Connexion à la base de données
function db_connect()
{
    static $connection;
    if (!isset($connection)) {
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
    if ($connection === false) {
        return mysqli_connect_error();
    }
    return $connection;
}

// Exécution de requêtes
function db_query($query)
{
    $connection = db_connect();
    $result = mysqli_query($connection, $query);
    return $result;
}

// Échappement des données
function db_escape($value)
{
    $connection = db_connect();
    return mysqli_real_escape_string($connection, $value);
}
?>