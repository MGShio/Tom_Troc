<?php
// Connexion à la base de données
function db_connect()
{
    static $connection;
    if (!isset($connection)) {
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($connection === false) {
            error_log("Database connection failed: " . mysqli_connect_error());
            return false;
        }
        if (!mysqli_set_charset($connection, "utf8mb4")) {
            error_log("Error setting character set: " . mysqli_error($connection));
        }
    }
    return $connection;
}

// Exécution de requêtes
function db_query($query)
{
    $connection = db_connect();
    if ($connection === false) {
        error_log("Database connection not available");
        return false;
    }
    $result = mysqli_query($connection, $query);
    if ($result === false) {
        error_log("Query failed: " . mysqli_error($connection));
    }
    return $result;
}

// Échappement des données
function db_escape($value)
{
    $connection = db_connect();
    if ($connection === false) {
        error_log("Database connection not available for escaping");
        return '';
    }
    return mysqli_real_escape_string($connection, $value);
}

// Close database connection
function db_close()
{
    static $connection;
    if (isset($connection) && $connection !== false) {
        mysqli_close($connection);
        $connection = null;
    }
}

register_shutdown_function('db_close');