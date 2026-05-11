<?php
class User
{
    private $id;
    private $name;
    private $email;
    private $password;

    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->password = $data['password'] ?? '';
        }
    }

    public static function getById($db, $id)
    {
        $stmt = $db->prepare('SELECT id, name, email, password FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }

    public static function getByEmail($db, $email)
    {
        $stmt = $db->prepare('SELECT id, name, email, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
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

    /**
     * Get user ID
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get user email
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user name
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set user email
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set user password (hashed)
     * @param string $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
}