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
    protected $description = 'Kirim pengingat WhatsApp H-2/H-1 ke ibu yang balitanya terdaftar di posyandu dengan jadwal mendatang (H-2/H-1).';

    /**
     * Execute the command.
     *
     * @return int
     */
    public function handle(): int
    {
        $whatsapp = app(WhatsAppService::class);

        $today = Carbon::today();
        $targetDays = [0, 1, 2]; // H-0 (hari ini), H-1, H-2

        // Jadwal mendatang (>= hari ini) yang relevan untuk pengingat
        $jadwals = Jadwal::with('posyandu')
            ->where('tanggal', '>=', $today->toDateString())
            ->get();

        if ($jadwals->isEmpty()) {
            $this->info('Tidak ada jadwal mendatang. Selesai.');
            return Command::SUCCESS;
        }

        $sent = 0;
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

            // Ambil semua orang tua balita di posyandu ini yang punya nomor HP
            $orangTuas = OrangTua::whereHas('balitas', fn ($q) => $q->where('posyandu_id', $posyanduId))
                ->whereNotNull('no_hp_whatsapp')
                ->get();

            foreach ($orangTuas as $ot) {
                $message = "Assalamualaikum Bu, jadwal Posyandu {$jadwal->judul} {$label} "
                    . "($jadwal->tanggal, mulai {$jadwal->waktu_mulai}). "
                    . "Jangan lupa bawa KMS dan timbang anak Anda. Terima kasih.";

                $whatsapp->send($ot->id, null, $ot->no_hp_whatsapp, $message, 'whatsapp');
                $sent++;
            }
        }

        $this->info("Selesai. Pengingat WhatsApp terkirim/catat: {$sent}.");
        return Command::SUCCESS;
    }
}
