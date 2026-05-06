<?php
class User
{
    private $id;
    private $name;
    private $email;
    private $password;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
    }

    public static function getById($db, $id)
    {
        $stmt = $db->prepare('SELECT id, name, email, password FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public static function getByEmail($db, $email)
    {
        $stmt = $db->prepare('SELECT id, name, email, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public function save($db)
    {
        if ($this->id) {
            $stmt = $db->prepare('UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?');
            $result = $stmt->execute([$this->name, $this->email, $this->password, $this->id]);
        } else {
            $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $result = $stmt->execute([$this->name, $this->email, $this->password]);
            if ($result) {
                $this->id = $db->lastInsertId();
            }
        }
        return $result;
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }

    // Setters
    public function setName($name) { $this->name = $name; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = password_hash($password, PASSWORD_DEFAULT); }
}