<?php
class Campaign
{
    public static function all()
    {
        return array(
            array('jour' => '20', 'mois' => 'Déc.', 'titre' => 'Campagne de don de sang', 'ville' => 'Tunis'),
            array('jour' => '15', 'mois' => 'Jan.', 'titre' => "Sensibilisation au don d'organes", 'ville' => 'Sfax'),
            array('jour' => '10', 'mois' => 'Fév.', 'titre' => 'Journée de solidarité', 'ville' => 'Sousse')
        );
    }
}
