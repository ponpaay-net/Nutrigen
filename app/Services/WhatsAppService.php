<?php

namespace App\Services;

use App\Models\NotificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * TINGGI-02 — Layanan kirim notifikasi WhatsApp dengan driver yang bisa
 * dikonfigurasi lewat config('services.wa').
 *
 * Driver default adalah 'log': TIDAK memanggil gateway mana pun, hanya
 * mencatat ke notification_logs + log aplikasi. Dengan begitu alur
 * "kirim pesan + tercatat" sudah berfungsi dan bisa dites TANPA perlu
 * akun, token, email, maupun nomor HP.
 *
 * Untuk pengiriman sungguhan, set WA_DRIVER + token di .env:
 *   WA_DRIVER=fonnte  + FONNTE_TOKEN=...
 *   WA_DRIVER=wablas  + WABLAS_TOKEN=...
 *
 * Kapan pun driver gagal/melempar exception, layanan ini tetap mencatat
 * log dengan status 'failed' dan tidak pernah memblokir alur utama.
 */
class WhatsAppService
{
    /**
     * Kirim pesan lalu catat hasilnya ke notification_logs.
     *
     * @return array{status:string, driver:string, log_id:int|null, message:string|null}
     */
    public function send(
        ?int $orangTuaId,
        ?int $pengukuranId,
        string $phone,
        string $message,
        string $channel = 'whatsapp'
    ): array {
        $driver = config('services.wa.driver', 'log');

        try {
            $result = match ($driver) {
                'fonnte' => $this->viaFonnte($phone, $message),
                'wablas' => $this->viaWablas($phone, $message),
                default  => $this->viaLog($phone, $message),
            };
            $status = $result['status'];
        } catch (\Throwable $e) {
            $status = 'failed';
            $result = ['status' => 'failed', 'message' => $e->getMessage()];
        }

        $log = NotificationLog::create([
            'orang_tua_id'  => $orangTuaId,
            'pengukuran_id' => $pengukuranId,
            'channel'       => $channel,
            'status'        => $status,
            'payload'       => ['to' => $phone, 'message' => $message],
            'response_body' => $result['message'] ?? null,
        ]);

        return [
            'status'  => $status,
            'driver'  => $driver,
            'log_id'  => $log->id,
            'message' => $result['message'] ?? null,
        ];
    }

    /** Driver default: tidak kirim ke mana pun, hanya catat (demo-safe). */
    private function viaLog(string $phone, string $message): array
    {
        Log::info("[WhatsAppService::log] to={$phone} msg={$message}");

        return ['status' => 'sent', 'message' => 'simulated (log driver)'];
    }

    /** Gateway Fonnte (https://fonnte.com) — gratis untuk kuota kecil. */
    private function viaFonnte(string $phone, string $message): array
    {
        $token = config('services.wa.fonnte_token');
        $response = Http::asForm()->timeout(15)->post('https://api.fonnte.com/send', [
            'token'   => $token,
            'target'  => $phone,
            'message' => $message,
        ]);
        $body = $response->body();
        $json = $response->json() ?: [];
        $ok = $response->successful()
            && (($json['status'] ?? null) === 'true' || ($json['status'] ?? null) === true);

        return ['status' => $ok ? 'sent' : 'failed', 'message' => $body];
    }

    /** Gateway Wablas (https://wablas.com) — gratis untuk kuota kecil. */
    private function viaWablas(string $phone, string $message): array
    {
        $token = config('services.wa.wablas_token');
        $response = Http::asForm()->timeout(15)->post('https://api.wablas.com/api/v2/send-message', [
            'token'   => $token,
            'phone'   => $phone,
            'message' => $message,
        ]);
        $body = $response->body();
        $json = $response->json() ?: [];
        $ok = $response->successful()
            && (($json['status'] ?? null) === true
                || ($json['status'] ?? null) === 'success'
                || ($json['status'] ?? null) === '200');

        return ['status' => $ok ? 'sent' : 'failed', 'message' => $body];
    }
}
