<?php
/**
 * config/config.php
 * Codes d'inscription pour les rôles à privilèges.
 * Le rôle "citoyen" est libre ; les deux autres exigent le code correspondant.
 * CHANGEZ ces codes avant de les donner à quelqu'un.
 */
return [
    'registration_codes' => [
        'agent_sante' => 'SANTE-2026',
        'securite'    => 'ADMIN-2026',
    ],
];
