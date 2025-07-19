<?php
class Sanitizar
{
    public static function texto($str)
    {
        $str = trim($str);
        $str = stripslashes($str);
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        return $str;
    }

    public static function entero($valor)
    {
        return filter_var($valor, FILTER_SANITIZE_NUMBER_INT);
    }
}
