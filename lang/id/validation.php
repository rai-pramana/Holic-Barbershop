<?php

// Pesan validasi Bahasa Indonesia — dipakai otomatis karena APP_LOCALE=id.
// Pesan kustom per-controller (parameter ke-2 $request->validate) tetap menang.

return [

    'required' => 'Kolom :attribute wajib diisi.',
    'string'   => 'Kolom :attribute harus berupa teks.',
    'numeric'  => 'Kolom :attribute harus berupa angka.',
    'integer'  => 'Kolom :attribute harus berupa bilangan bulat.',
    'email'    => 'Kolom :attribute harus berupa alamat email yang valid.',
    'date'     => 'Kolom :attribute harus berupa tanggal yang valid.',
    'boolean'  => 'Kolom :attribute harus bernilai benar atau salah.',
    'exists'   => ':Attribute yang dipilih tidak valid.',
    'unique'   => ':Attribute sudah digunakan.',
    'confirmed'=> 'Konfirmasi :attribute tidak cocok.',
    'regex'    => 'Format :attribute tidak valid.',
    'date_format' => 'Kolom :attribute harus berformat :format.',
    'after_or_equal' => 'Kolom :attribute harus tanggal setelah atau sama dengan :date.',

    'max' => [
        'string' => 'Kolom :attribute maksimal :max karakter.',
    ],
    'min' => [
        'numeric' => 'Kolom :attribute minimal :min.',
        'string'  => 'Kolom :attribute minimal :min karakter.',
    ],
    'between' => [
        'numeric' => 'Kolom :attribute harus antara :min dan :max.',
    ],

    'attributes' => [
        'name'             => 'nama',
        'email'            => 'email',
        'phone'            => 'nomor HP',
        'guest_name'       => 'nama pelanggan',
        'guest_phone'      => 'nomor HP',
        'password'         => 'password',
        'current_password' => 'password saat ini',
        'service_id'       => 'layanan',
        'barber_id'        => 'barber',
        'branch_id'        => 'cabang',
        'address'          => 'alamat',
        'city'             => 'kota',
        'description'      => 'deskripsi',
        'specialty'        => 'spesialisasi',
        'bio'              => 'bio',
        'open_time'        => 'jam buka',
        'close_time'       => 'jam tutup',
        'queue_prefix'     => 'prefix antrean',
        'duration_minutes' => 'durasi',
        'price'            => 'harga',
        'queue_number'     => 'nomor antrean',
        'notes'            => 'catatan',
        'code'             => 'kode',
        'contact'          => 'kontak',
        'date'             => 'tanggal',
        'date_from'        => 'tanggal mulai',
        'date_to'          => 'tanggal akhir',
        'is_active'        => 'status aktif',
        'is_available'     => 'ketersediaan',
    ],

];
