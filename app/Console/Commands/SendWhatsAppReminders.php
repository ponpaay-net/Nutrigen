<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use App\Models\OrangTua;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendWhatsAppReminders extends Command
{
    /**
     * Name & signature.
     *
     * @var string
     */
    protected $signature = 'whatsapp:reminders';

    /**
     * Description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat WhatsApp H-2/H-1 ke ibu yang balitanya terdaftar di posyandu dengan jadwal mendatang (H-2/H-1). Dilengkapi throttle + cooldown harian untuk menghindari blokir/ban nomor Fonnte.';

    /**
     * Execute the command.
     *
     * @return int
     */
    public function handle(): int
    {
        $whatsapp = app(WhatsAppService::class);

        // --- Parameter throttle (dari config/services.php, bisa override via .env) ---
        $delaySeconds   = (int) config('services.wa.throttle.delay_seconds', 5);
        $batchSize      = (int) config('services.wa.throttle.batch_size', 8);
        $batchPause     = (int) config('services.wa.throttle.batch_pause_seconds', 60);

        $today = Carbon::today();
        $targetDays = [0, 1, 2]; // H-0 (hari ini), H-1, H-2

        $jadwals = Jadwal::with('posyandu')
            ->where('tanggal', '>=', $today->toDateString())
            ->get();

        if ($jadwals->isEmpty()) {
            $this->info('Tidak ada jadwal mendatang. Selesai.');
            return Command::SUCCESS;
        }

        $sent = 0;
        $skipped = 0;
        $processedInBatch = 0;

        foreach ($jadwals as $jadwal) {
            $daysUntil = (int) $today->diffInDays($jadwal->tanggal, false);

            if (!in_array($daysUntil, $targetDays, true)) {
                continue; // hanya H-0, H-1, H-2
            }

            $label = $daysUntil === 0 ? 'hari ini' : ($daysUntil === 1 ? 'besok' : 'lusa');
            $posyanduId = $jadwal->posyandu_id;

            if (!$posyanduId) {
                continue;
            }

            $orangTuas = OrangTua::whereHas('balitas', fn ($q) => $q->where('posyandu_id', $posyanduId))
                ->whereNotNull('no_hp_whatsapp')
                ->get();

            foreach ($orangTuas as $ot) {
                $phone = $ot->no_hp_whatsapp;

                // --- Cooldown: 1 nomor maksimal 1x sehari (anti pola spam) ---
                if ($whatsapp->alreadySentToday($phone)) {
                    $skipped++;
                    $this->warn("Skip (sudah kirim hari ini): {$phone}");
                    continue;
                }

                // --- Personalisasi: pakai nama ibu/ayah biar tidak terlihat bot ---
                $nama = $ot->nama_ibu ?: ($ot->nama_ayah ?: 'Ibu/Bapak');
                $message = "Assalamualaikum {$nama}, jadwal Posyandu {$jadwal->judul} {$label} "
                    . "({$jadwal->tanggal}, mulai {$jadwal->waktu_mulai}). "
                    . "Jangan lupa bawa KMS dan timbang anak Anda. Terima kasih.";

                $whatsapp->send($ot->id, null, $phone, $message, 'whatsapp');
                $sent++;
                $processedInBatch++;

                // --- Jeda antar pesan (anti rate-limit/ban) ---
                if ($delaySeconds > 0) {
                    sleep($delaySeconds);
                }

                // --- Istirahat tiap batch ---
                if ($batchSize > 0 && $processedInBatch % $batchSize === 0) {
                    $this->info("Istirahat {$batchPause}s setelah {$processedInBatch} pesan...");
                    if ($batchPause > 0) {
                        sleep($batchPause);
                    }
                }
            }
        }

        $this->info("Selesai. Terkirim: {$sent}, dilewati (cooldown): {$skipped}.");
        return Command::SUCCESS;
    }
}
