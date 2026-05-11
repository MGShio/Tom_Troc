<?php

class HomeController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $books = BookManager::getAllAvailable($this->db) ?: [];
        $books = array_slice($books, 0, 6);
        require __DIR__ . '/../views/home/index.php';
    }
}
