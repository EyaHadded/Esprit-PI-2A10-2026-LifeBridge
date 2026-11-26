<?php
/** Accès à la table users : les opérations CRUD utilisent PDO. */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/User.php';

class UserService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    // READ : récupérer tous les utilisateurs.
    public function getAll()
    {
        $statement = $this->db->query('SELECT * FROM users ORDER BY id ASC');
        $rows = $statement->fetchAll();
        $users = array();

        foreach ($rows as $row) {
            $users[] = User::fromArray($row);
        }
        return $users;
    }

    // READ : récupérer les derniers utilisateurs inscrits.
    public function getLatest($number)
    {
        $statement = $this->db->prepare('SELECT * FROM users ORDER BY id DESC LIMIT :number');
        $statement->bindValue(':number', (int) $number, PDO::PARAM_INT);
        $statement->execute();
        $rows = $statement->fetchAll();
        $users = array();

        foreach ($rows as $row) {
            $users[] = User::fromArray($row);
        }
        return $users;
    }

    public function findById($id)
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute(array('id' => $id));
        $row = $statement->fetch();

        if ($row) {
            return User::fromArray($row);
        }
        return null;
    }

    public function findByEmail($email)
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(array('email' => $email));
        $row = $statement->fetch();

        if ($row) {
            return User::fromArray($row);
        }
        return null;
    }

    public function countAll()
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public function countByRole($role)
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM users WHERE role = :role');
        $statement->execute(array('role' => $role));
        return (int) $statement->fetchColumn();
    }

    public function emailExists($email, $excludeId = 0)
    {
        $statement = $this->db->prepare('SELECT id FROM users WHERE email = :email AND id <> :id LIMIT 1');
        $statement->execute(array('email' => $email, 'id' => $excludeId));
        return (bool) $statement->fetch();
    }

    // CREATE : ajouter un utilisateur. Le mot de passe doit déjà être haché.
    public function insert($user)
    {
        $statement = $this->db->prepare(
            'INSERT INTO users (nom, email, password, role) VALUES (:nom, :email, :password, :role)'
        );
        return $statement->execute(array(
            'nom' => $user->getNom(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole()
        ));
    }

    // UPDATE : modifier les informations d'un utilisateur.
    public function update($id, $nom, $email, $role, $passwordHash)
    {
        if ($passwordHash !== null) {
            $statement = $this->db->prepare(
                'UPDATE users SET nom = :nom, email = :email, role = :role, password = :password WHERE id = :id'
            );
            return $statement->execute(array(
                'nom' => $nom, 'email' => $email, 'role' => $role,
                'password' => $passwordHash, 'id' => $id
            ));
        }

        $statement = $this->db->prepare('UPDATE users SET nom = :nom, email = :email, role = :role WHERE id = :id');
        return $statement->execute(array('nom' => $nom, 'email' => $email, 'role' => $role, 'id' => $id));
    }

    // UPDATE : modifier le profil sans permettre à l'utilisateur de changer son rôle.
    public function updateProfile($id, $nom, $email, $passwordHash = null)
    {
        if ($passwordHash !== null) {
            $statement = $this->db->prepare(
                'UPDATE users SET nom = :nom, email = :email, password = :password WHERE id = :id'
            );
            return $statement->execute(array(
                'nom' => $nom, 'email' => $email, 'password' => $passwordHash, 'id' => $id
            ));
        }

        $statement = $this->db->prepare('UPDATE users SET nom = :nom, email = :email WHERE id = :id');
        return $statement->execute(array('nom' => $nom, 'email' => $email, 'id' => $id));
    }

    // DELETE : supprimer un utilisateur.
    public function delete($id)
    {
        $statement = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $statement->execute(array('id' => $id));
    }
}
