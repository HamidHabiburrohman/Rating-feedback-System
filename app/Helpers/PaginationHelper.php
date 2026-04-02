<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationHelper
{
    /**
     * Mendapatkan range halaman untuk pagination dengan dynamic visible pages
     * 
     * @param LengthAwarePaginator $paginator
     * @param int $maxVisible
     * @return array
     */
    public static function getPaginationRange(LengthAwarePaginator $paginator, int $maxVisible = 4): array
    {
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        
        // Jika total halaman kurang dari max visible, tampilkan semua
        if ($last <= $maxVisible) {
            return range(1, $last);
        }
        
        $half = floor($maxVisible / 2);
        $start = max(1, $current - $half);
        $end = min($last, $current + $half);
        
        // Adjust jika di awal
        if ($start <= 2) {
            $start = 1;
            $end = min($maxVisible, $last);
        }
        
        // Adjust jika di akhir
        if ($end >= $last - 1) {
            $start = max(1, $last - $maxVisible + 1);
            $end = $last;
        }
        
        $range = range($start, $end);
        
        // Tambahkan ellipsis jika perlu
        if ($start > 2) {
            array_unshift($range, '...');
            array_unshift($range, 1);
        } elseif ($start == 2) {
            array_unshift($range, 1);
        }
        
        if ($end < $last - 1) {
            array_push($range, '...');
            array_push($range, $last);
        } elseif ($end == $last - 1) {
            array_push($range, $last);
        }
        
        return $range;
    }
}