<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class Controller
{
    public function transformPagination(LengthAwarePaginator $paginator) {
        return [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'items' => $paginator->items(),
            'current_page' => $paginator->currentPage()
        ];
    }
}
