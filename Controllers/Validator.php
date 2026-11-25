<?php
/** Validation des formulaires côté serveur. */
class Validator
{
    public static function nom($nom)
    {
        if (!preg_match("/^[\p{L}][\p{L}' \\-]{1,99}$/u", $nom)) {
            return "Le nom complet doit contenir 2 à 100 caractères (lettres, espaces, apostrophe ou tiret).";
        }
        return null;
    }

    public static function email($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            return 'Adresse e-mail invalide.';
        }
        return null;
    }

    public static function password($password, $confirm, $required = true)
    {
        $errors = array();

        if ($password === '' && !$required) {
            return $errors;
        }
        if (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }
        return $errors;
    }

    public static function user($post, $passwordRequired = true)
    {
        $errors = array();
        $nom = isset($post['nom']) && is_string($post['nom']) ? trim($post['nom']) : '';
        $email = isset($post['email']) && is_string($post['email']) ? trim($post['email']) : '';
        $password = isset($post['password']) && is_string($post['password']) ? $post['password'] : '';
        $confirm = isset($post['password_confirm']) && is_string($post['password_confirm']) ? $post['password_confirm'] : '';

        if ($nom === '' || $email === '' || ($passwordRequired && $password === '')) {
            $errors[] = 'Tous les champs obligatoires doivent être remplis.';
        }
        if ($nom !== '') {
            $error = self::nom($nom);
            if ($error !== null) { $errors[] = $error; }
        }
        if ($email !== '') {
            $error = self::email($email);
            if ($error !== null) { $errors[] = $error; }
        }
        if ($password !== '' || $passwordRequired) {
            $passwordErrors = self::password($password, $confirm, $passwordRequired);
            $errors = array_merge($errors, $passwordErrors);
        }
        return $errors;
    }
}
