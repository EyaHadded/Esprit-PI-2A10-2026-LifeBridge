<?php
/**
 * Connexion unique à la base de données avec PDO.
 * Les requêtes préparées sont utilisées dans UserService.
 */
class Database
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=lifebridge;charset=utf8mb4',
                    'root',
                    '',
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    )
                );
            } catch (PDOException $e) {
                error_log($e->getMessage());
                die('Impossible de se connecter à la base de données. Vérifiez la configuration MySQL.');
            }
        }

        return self::$pdo;
    }
}
