<?php

use App\Helpers\PaginationHelper;

if (!function_exists('getPaginationRange')) {
    function getPaginationRange($paginator, $maxVisible = 4)
    {
        return PaginationHelper::getPaginationRange($paginator, $maxVisible);
    }
}