<?php
// Exemple de fonction pour récupérer les derniers livres
function get_derniers_livres()
{
    // Ici, tu pourrais faire une requête SQL pour récupérer les livres depuis la base de données
    // Pour l'exemple, je retourne un tableau statique
    return [
        ['titre' => 'Ether', 'auteur' => 'Laurent Genefort', 'image' => 'book1.jpg'],
        ['titre' => 'The Knife Table', 'auteur' => 'Norman Williams', 'image' => 'book2.jpg'],
        ['titre' => 'Wabi Sabi', 'auteur' => 'Beth Kempton', 'image' => 'book3.jpg'],
        ['titre' => 'Milk & Honey', 'auteur' => 'Rupi Kaur', 'image' => 'book4.jpg'],
    ];
}

// Fonction pour afficher un livre
function afficher_livre($livre)
{
    echo '<div class="book-card">';
    echo '    <img src="' . BASE_URL . 'assets/images/' . $livre['image'] . '" alt="' . $livre['titre'] . '">';
    echo '    <h3>' . $livre['titre'] . '</h3>';
    echo '    <p>par ' . $livre['auteur'] . '</p>';
    echo '</div>';
}
