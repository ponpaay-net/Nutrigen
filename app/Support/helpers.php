<?php

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

if (! function_exists('mask_sensitive')) {
    /**
     * Samarkan nilai data sensitif (NIK, no KK, no HP) di tampilan read-only.
     *
     * Menjaga bagian tertentu (contoh 4 digit awal + 2 digit akhir) untuk
     * konteks, sementara bagian tengah diganti asterisk. Sesuai praktik
     * masking PII: jangan tampilkan nilai asli penuh ke pengguna non-wajib.
     *
     * @param  string|null  $value  nilai asli dari kolom terenkripsi (sudah di-dekripsi oleh model)
     * @param  int  $leading  jumlah digit yang tetap tampil di depan
     * @param  int  $trailing  jumlah digit yang tetap tampil di belakang
     */
    function mask_sensitive(?string $value, int $leading = 4, int $trailing = 2): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        // Bersihkan karakter non-alfanumerik supaya jumlah digit akurat; jika
        // memang punya spasi/pemisah (mis. "12 3456"), tetap mask by char count.
        $clean = preg_replace('/\s+/', '', (string) $value);

        $length = mb_strlen($clean);
        if ($length <= ($leading + $trailing)) {
            // Terlalu pendek untuk dibiarkan: tampilkan semua bintang.
            return str_repeat('*', $length);
        }

        $head = mb_substr($clean, 0, $leading);
        $tail = mb_substr($clean, -$trailing);
        $mask = str_repeat('*', max(0, $length - $leading - $trailing));

        return $head . $mask . $tail;
    }
}