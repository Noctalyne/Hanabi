<?php

namespace App\Service;

class UtilsServices
{    
    /**
     * @param string $string
     * @return string
    */
    
    // Fonction qui nettoie les données envoyés
    public static function cleanInput(string $string)
    {
        return htmlspecialchars(strip_tags(trim($string)), ENT_NOQUOTES);
    }
}


