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
        $bookManager = new BookManager($this->db);
        $books = $bookManager->getRecent(4);
        require __DIR__ . '/../views/home/index.php';
    }
}
