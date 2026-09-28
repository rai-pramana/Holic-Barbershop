<?php

namespace App\Concerns;

use Illuminate\Http\Request;

trait Sortable
{
    /**
     * Terapkan sorting dari query string (?sort=kolom&dir=asc|desc).
     * Hanya kolom dalam whitelist yang dipakai — anti SQL injection.
     * Tiebreaker id otomatis agar paginasi deterministik.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $allowed  daftar kolom yang boleh di-sort
     * @param  string $default  kolom default
     * @param  string $defaultDir
     * @param  string|null $table  nama tabel untuk kualifikasi kolom + tiebreaker (null = tanpa kualifikasi)
     * @return array{0: \Illuminate\Database\Eloquent\Builder, 1: string, 2: string}
     */
    protected function applySort($query, Request $request, array $allowed, string $default, string $defaultDir = 'desc', ?string $table = null): array
    {
        $sort = $request->query('sort', $default);
        $dir  = strtolower($request->query('dir', $defaultDir)) === 'asc' ? 'asc' : 'desc';

        if (! in_array($sort, $allowed, true)) {
            $sort = $default;
            $dir  = $defaultDir;
        }

        $column = $table ? $table . '.' . $sort : $sort;
        $query->orderBy($column, $dir);
        // Tiebreaker id: cegah baris bocor/duplikat antar halaman saat nilai sort kembar.
        $query->orderBy(($table ? $table . '.' : '') . 'id', $dir);

        return [$query, $sort, $dir];
    }
}
