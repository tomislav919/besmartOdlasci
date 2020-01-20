<?php


namespace App\Classes;


class Replace
{
    public static function croLetters($string)
    {
        //zamjeni sva hrvatska slova čćžšđ sa cczsd
        $new_str = str_replace('ć', 'c', $string);
        $new_str = str_replace('č', 'c', $new_str);
        $new_str = str_replace('ž', 'z', $new_str);
        $new_str = str_replace('š', 's', $new_str);
        $new_str = str_replace('đ', 'd', $new_str);
        $new_str = str_replace('Č', 'C', $new_str);
        $new_str = str_replace('Ć', 'C', $new_str);
        $new_str = str_replace('Ž', 'Z', $new_str);
        $new_str = str_replace('Š', 'S', $new_str);
        $new_str = str_replace('Đ', 'D', $new_str);

        return $new_str;
    }
}
