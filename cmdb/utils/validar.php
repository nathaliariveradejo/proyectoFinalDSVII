<?php
class Validar
{
    public static function email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function longitud($str, $min, $max)
    {
        $len = mb_strlen($str);
        return ($len >= $min && $len <= $max);
    }

    public static function fecha($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
