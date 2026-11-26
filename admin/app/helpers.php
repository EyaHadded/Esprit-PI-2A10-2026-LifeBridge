<?php
function isActive($page, $currentPage)
{
    if ($page === $currentPage) {
        return 'active';
    }
    return '';
}

// e() est normalement chargé depuis config/security.php.
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
