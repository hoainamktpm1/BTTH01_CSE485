<?php

function is_number($number, $min = 0, $max = 100): bool
{
    return ($number >= $min and $number <= $max);
}

function is_text($text, $min = 0, $max = 1000)
{
    $length = mb_strlen($text);
    return ($length >= $min and $length <= $max);
}

function is_author_id($member_id, array $member_list): bool
{
    foreach ($member_list as $member) {
        if ($member['ma_tgia'] == $member_id) {
            return true;
        }
    }
    return false;
}

function is_category_id($category_id, array $category_list): bool
{
    foreach ($category_list as $category) {
        if ($category['ma_tloai'] == $category_id) {
            return true;
        }
    }
    return false;
}