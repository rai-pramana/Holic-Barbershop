<?php

namespace App\Concerns;

use Illuminate\Http\Request;

trait Sortable
{
    /**
     * Terapkan sorting dari query string (?sort=kolom&dir=asc|desc).
     * Hanya kolom dalam whitelist yang dipakai — anti SQL injection.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $allowed  daftar kolom yang boleh di-sort
     * @param  string $default  kolom default
     * @param  string $defaultDir
     * @return array{0: \Illuminate\Database\Eloquent\Builder, 1: string, 2: string}
     */
    protected function applySort($query, Request $request, array $allowed, string $default, string $defaultDir = 'desc'): array
    {
        $sort = $request->query('sort', $default);
        $dir  = strtolower($request->query('dir', $defaultDir)) === 'asc' ? 'asc' : 'desc';

        if (! in_array($sort, $allowed, true)) {
            $sort = $default;
            $dir  = $defaultDir;
        }

        return [$query->orderBy($sort, $dir), $sort, $dir];
    }
}
