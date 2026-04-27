<?php
require_once __DIR__ . '/../models/Livre.php';
require_once __DIR__ . '/../includes/database.php';

class LivreController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $livres = Livre::getAllDisponibles($this->db);
        require __DIR__ . '/../views/livres/index.php';
    }

    public function show($id)
    {
        $livre = Livre::getById($this->db, $id);
        require __DIR__ . '/../views/livres/show.php';
    }
}
