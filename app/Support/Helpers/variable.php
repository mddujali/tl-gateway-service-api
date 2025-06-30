<?php

declare(strict_types=1);

if ( ! function_exists('isset_value')) {
    function isset_value(&$value, $default = null): mixed
    {
        return $value ?? $default;
    }
}
