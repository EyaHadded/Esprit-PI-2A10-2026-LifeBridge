<?php
// Quelques lignes de démonstration affichées dans le backoffice.
class Model
{
    public static function users()
    {
        return array(
            array('Sarra Ben Ali', 'sarra@email.com', '10/01/2026', 'Actif'),
            array('Mohamed Trabelsi', 'mohamed@email.com', '09/01/2026', 'Actif'),
            array('Aya Khaled', 'aya@email.com', '08/01/2026', 'Actif')
        );
    }

    public static function dons()
    {
        return array(
            array('Don de sang', 'Ali Mejri', '10/01/2026', 'Tunis'),
            array("Don d'organes", 'Leila Mansouri', '09/01/2026', 'Sousse'),
            array('Don de sang', 'Hichem Dhaouadi', '08/01/2026', 'Sfax')
        );
    }
}
