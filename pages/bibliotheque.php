<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header('Location: ' . BASE_URL . 'pages/connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_livre'])) {
    $titre = db_escape($_POST['titre']);
    $auteur = db_escape($_POST['auteur']);
    $description = db_escape($_POST['description']);
    $statut = db_escape($_POST['statut']);
    $user_id = (int)$_SESSION['user_id'];

    $query = "INSERT INTO livres (titre, auteur, description, statut, utilisateur_id) VALUES ('$titre', '$auteur', '$description', '$statut', $user_id)";
    db_query($query);
    header('Location: ' . BASE_URL . 'pages/bibliotheque.php');
    exit;
}

require_once '../includes/header.php';
?>

<section class="section">
    <h2>Ma bibliothèque</h2>
    <a href="#ajouter-livre" class="btn">Ajouter un livre</a>

    <div class="books-grid">
        <?php
        $livres = get_livres_utilisateur($_SESSION['user_id']);
        foreach ($livres as $livre) {
            echo '<div class="book-card">';
            if (!empty($livre['image'])) {
                echo '<img src="' . BASE_URL . 'assets/images/' . htmlspecialchars($livre['image']) . '" alt="' . htmlspecialchars($livre['titre']) . '">';
            } else {
                echo '<div style="width: 150px; height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center;">';
                echo 'Pas d\'image';
                echo '</div>';
            }
            echo '<h3>' . htmlspecialchars($livre['titre']) . '</h3>';
            echo '<p>par ' . htmlspecialchars($livre['auteur']) . '</p>';
            echo '<p>Statut : ' . htmlspecialchars($livre['statut']) . '</p>';
            echo '<a href="' . BASE_URL . 'pages/detail_livre.php?id=' . $livre['id'] . '" class="btn">Voir</a>';
            echo '</div>';
        }
        ?>
    </div>
</section>

<section id="ajouter-livre" class="section">
    <h2>Ajouter un livre</h2>
    <form method="post">
        <div>
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" required>
        </div>
        <div>
            <label for="auteur">Auteur :</label>
            <input type="text" id="auteur" name="auteur" required>
        </div>
        <div>
            <label for="description">Description :</label>
            <textarea id="description" name="description" rows="5"></textarea>
        </div>
        <div>
            <label for="statut">Statut :</label>
            <select id="statut" name="statut" required>
                <option value="disponible">Disponible</option>
                <option value="indisponible">Indisponible</option>
                <option value="echange">En échange</option>
            </select>
        </div>
        <button type="submit" name="ajouter_livre" class="btn">Ajouter</button>
    </form>
</section>

<?php
require_once '../includes/footer.php';
?>