<?php

function getStr($string)
{
    return trim(strip_tags($string));
}

function getInt($int)
{
    return abs((int)$int);
}