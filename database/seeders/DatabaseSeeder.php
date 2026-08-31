<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Puskesmas;
use App\Models\Posyandu;
use App\Models\Kader;
use App\Models\OrangTua;
use App\Models\Balita;
use App\Models\Pengukuran;
use App\Models\Jadwal;
use App\Services\GrowthCalculationService;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application with 80 realistic balitas (~27 per posyandu),
     * comprehensive multi-month KMS history, and diverse clinical states.
     */
    public function run(): void
    {
        $calculator = new GrowthCalculationService();
        $now = Carbon::now();

        // -------------------------------------------------------------
        // 1. PUSKESMAS & PETUGAS GIZI
        // -------------------------------------------------------------
        $userPuskesmas = User::create([
            'name' => 'dr. Cut Nyak Sarah, S.Gz',
            'email' => 'puskesmas@nutrigen.com',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'role' => 'puskesmas',
        ]);

        $puskesmas = Puskesmas::create([
            'user_id' => $userPuskesmas->id,
            'nama' => 'Puskesmas Kuta Alam',
            'kode_faskes' => 'P11710101',
            'alamat' => 'Jl. T. Nyak Arief No. 24, Kuta Alam, Kota Banda Aceh',
        ]);

        // -------------------------------------------------------------
        // 2. POSYANDU (3 POSYANDU)
        // -------------------------------------------------------------
        $posyandu1 = Posyandu::create([
            'puskesmas_id' => $puskesmas->id,
            'nama' => 'Posyandu Bunga Tanjung VII',
            'desa_kelurahan' => 'Gampong Lampulo',
            'alamat' => 'Balai Pertemuan Warga, Lr. Samudra No. 12, Lampulo',
        ]);

        $posyandu2 = Posyandu::create([
            'puskesmas_id' => $puskesmas->id,
            'nama' => 'Posyandu Melati Sejahtera',
            'desa_kelurahan' => 'Gampong Peunayong',
            'alamat' => 'Kompleks Rukun Warga, Jl. Panglima Polem No. 45, Peunayong',
        ]);

        $posyandu3 = Posyandu::create([
            'puskesmas_id' => $puskesmas->id,
            'nama' => 'Posyandu Cempaka Harapan',
            'desa_kelurahan' => 'Gampong Bandar Baru',
            'alamat' => 'Balai Desa Bandar Baru, Jl. Seulanga No. 8, Bandar Baru',
        ]);

        $posyandus = [$posyandu1, $posyandu2, $posyandu3];

        // -------------------------------------------------------------
        // 3. KADER POSYANDU
        // -------------------------------------------------------------
        $kaderList = [
            [
                'name' => 'Cut Malahayati, A.Md.Keb',
                'email' => 'kader@nutrigen.com',
                'posyandu' => $posyandu1,
                'no_hp' => '081269001234',
            ],
            [
                'name' => 'Cut Malahayati (Kader 1)',
                'email' => 'kader1@gmail.com',
                'posyandu' => $posyandu1,
                'no_hp' => '081269001235',
            ],
            [
                'name' => 'Cut Malahayati (Kader 1 Nutrigen)',
                'email' => 'kader1@nutrigen.com',
                'posyandu' => $posyandu1,
                'no_hp' => '081269001236',
            ],
            [
                'name' => 'Siti Rahmah, S.Pd',
                'email' => 'kader2@nutrigen.com',
                'posyandu' => $posyandu2,
                'no_hp' => '081377889901',
            ],
            [
                'name' => 'Nurul Fauziah',
                'email' => 'kader3@nutrigen.com',
                'posyandu' => $posyandu3,
                'no_hp' => '085260112233',
            ],
        ];

        $kaders = [];
        foreach ($kaderList as $k) {
            $userKader = User::create([
                'name' => $k['name'],
                'email' => $k['email'],
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role' => 'kader',
            ]);

            $kaders[] = Kader::create([
                'user_id' => $userKader->id,
                'posyandu_id' => $k['posyandu']->id,
                'nama' => $k['name'],
                'no_hp' => $k['no_hp'],
            ]);
        }

        // -------------------------------------------------------------
        // 4. DATA ORANG TUA REALISTIS (45 KELUARGA)
        // -------------------------------------------------------------
        $parentNames = [
            ['Cut Annisa Zahra', 'Teuku Farhan Maulana', 'Gampong Lampulo, RT 02/RW 01'],
            ['Rina Agustina', 'Muhammad Rizky Pratama', 'Gampong Lampulo, Lr. Cakalang No. 14'],
            ['Nurhaliza, S.E.', 'Dedi Syahputra', 'Gampong Peunayong, Jl. T. Hasan Dek No. 5B'],
            ['Sri Wahyuni', 'Bambang Haryanto', 'Gampong Bandar Baru, Kompleks TVRI No. 8'],
            ['Maisarah, S.Pd', 'Zulfikar Arifin', 'Gampong Lampulo, Lr. Pukat Trawl No. 22'],
            ['Fitri Handayani', 'Hendri Saputra', 'Gampong Peunayong, Lr. Khadijah No. 17'],
            ['Dewi Sartika', 'Ahmad Fauzi', 'Gampong Bandar Baru, Jl. T. Nyak Arief No. 34'],
            ['Lestari Ningsih', 'Irfan Hakim', 'Gampong Lampulo, RT 04/RW 02'],
            ['Eka Putri Rahayu', 'Rahmat Hidayat', 'Gampong Peunayong, Jl. Ahmad Yani No. 89'],
            ['Nurhasanah', 'Faisal Tanjung', 'Gampong Bandar Baru, Lr. Jeumpa No. 3'],
            ['Tia Rahmawati', 'Agus Setiawan', 'Gampong Lampulo, Lr. PPI Lampulo No. 5'],
            ['Rini Kusuma Wardani', 'Dian Permana', 'Gampong Peunayong, Jl. KH Ahmad Dahlan No. 12'],
            ['Cut Putri Mayang Sari', 'M. Danial Syah', 'Gampong Bandar Baru, Kompleks Unsyiah Blok D-4'],
            ['Indah Permatasari', 'Yusuf Pratama', 'Gampong Lampulo, Lr. Samudra No. 9'],
            ['Siti Aminah', 'Rizal Pahlevi', 'Gampong Peunayong, Jl. Kartini No. 27'],
            ['Zahratul Ula', 'M. Iqbal Ramadhan', 'Gampong Bandar Baru, Jl. Tgk Chik Ditiro No. 101'],
            ['Marlina, S.Kep', 'Teuku Zulkarnain', 'Gampong Lampulo, Lr. Nelayan No. 3'],
            ['Yuliana Safitri', 'Fahmi Idris', 'Gampong Peunayong, Jl. Peunayong Lama No. 18'],
            ['Hasanah Putri', 'T. Fachrul Razi', 'Gampong Bandar Baru, Lr. Meulu No. 7'],
            ['Cut Mutia Rahmi', 'Munawar Khalil', 'Gampong Lampulo, Jl. Tanggul Samudra No. 41'],
            ['Desi Ratnasari', 'Andi Nugraha', 'Gampong Peunayong, Lr. Merak No. 11'],
            ['Wardah Hayati', 'Iskandar Muda', 'Gampong Bandar Baru, Kompleks BPD Blok C'],
            ['Syelifa Nanda', 'Rizwan Maulana', 'Gampong Lampulo, RT 01/RW 03'],
            ['Khadijah Marwan', 'Aulia Rahman', 'Gampong Peunayong, Jl. Panglima Polem No. 88'],
            ['Cut Riska Amalia', 'Fikri Syahrial', 'Gampong Bandar Baru, Lr. Bungong Jeumpa No. 15'],
            ['Nurlailawati', 'Safrizal', 'Gampong Lampulo, Lr. Pasi No. 2'],
            ['Rahmi Novita', 'Herman Syah', 'Gampong Peunayong, Jl. Jendral Sudirman No. 40'],
            ['Putri Balqis', 'T. Reza Pahlevi', 'Gampong Bandar Baru, Kompleks Baperis Blok A-2'],
            ['Harnum Melati', 'Zainal Abidin', 'Gampong Lampulo, RT 03/RW 01'],
            ['Anita Zahara', 'Bahrul Ulum', 'Gampong Peunayong, Jl. Tentara Pelajar No. 6'],
            ['Cut Syarifah', 'Mahmud Syah', 'Gampong Bandar Baru, Jl. Teuku Umar No. 55'],
            ['Maulida Sari', 'Syamsul Bahri', 'Gampong Lampulo, Lr. Samudra Indah No. 18'],
            ['Fatimah Az-Zahra', 'Teuku M. Yusuf', 'Gampong Peunayong, Jl. Cut Nyak Dien No. 22'],
            ['Raudhatul Jannah', 'Rudi Hartono', 'Gampong Bandar Baru, Kompleks PU No. 14'],
            ['Nurul Afifah', 'Ilham Wahyudi', 'Gampong Lampulo, Lr. Bunga Mawar No. 7'],
            ['Husna Mufida', 'Zulkifli', 'Gampong Peunayong, Lr. Cempaka No. 20'],
            ['Cut Dara Meutia', 'T. Syahrul', 'Gampong Bandar Baru, Jl. Seulanga No. 19'],
            ['Suryani', 'Darmawan', 'Gampong Lampulo, Lr. Perikanan No. 16'],
            ['Ratna Juwita', 'Budi Santoso', 'Gampong Peunayong, Jl. Cut Meutia No. 4'],
            ['Hayatun Nufus', 'Khairul Anwar', 'Gampong Bandar Baru, Lr. Flamboyan No. 2'],
            ['Zubairah', 'Teuku Alamsyah', 'Gampong Lampulo, RT 05/RW 02'],
            ['Rosdiana', 'Mansyur', 'Gampong Peunayong, Lr. Kenanga No. 12'],
            ['Safura', 'M. Nasir', 'Gampong Bandar Baru, Jl. T. Hasan Dek No. 90'],
            ['Cut Nurul Huda', 'Teuku Firdaus', 'Gampong Lampulo, Lr. Samudra No. 33'],
            ['Aisyah Humairah', 'Ikhwanul Muslimin', 'Gampong Peunayong, Jl. WR Monginsidi No. 7'],
        ];

        $pekerjaanIbuList = [
            'Ibu Rumah Tangga', 'Guru PNS', 'Wiraswasta', 'Bidan', 'Perawat',
            'Pegawai Swasta', 'Dosen', 'PNS Dinas Kesehatan', 'Pedagang', 'Apoteker'
        ];
        $pekerjaanAyahList = [
            'Wiraswasta', 'PNS Pemko Banda Aceh', 'Nelayan', 'Pedagang', 'Karyawan BUMN',
            'Guru SMA', 'Arsitek', 'Tenaga Medis', 'Mekanik', 'Karyawan Swasta', 'Dosen'
        ];

        $orangTuas = [];
        foreach ($parentNames as $idx => $p) {
            $userIbu = User::create([
                'name' => $p[0],
                'email' => 'ibu' . ($idx + 1) . '@nutrigen.com',
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role' => 'ibu',
            ]);

            $noKk = '117101' . '1506' . str_pad($idx + 1, 6, '0', STR_PAD_LEFT);
            $nikIbu = '117101' . '5504' . str_pad($idx + 1, 6, '0', STR_PAD_LEFT);
            $nikAyah = '117101' . '1208' . str_pad($idx + 1, 6, '0', STR_PAD_LEFT);
            $pekIbu = $pekerjaanIbuList[$idx % count($pekerjaanIbuList)];
            $pekAyah = $pekerjaanAyahList[$idx % count($pekerjaanAyahList)];

            $orangTuas[] = OrangTua::create([
                'user_id' => $userIbu->id,
                'no_kk' => $noKk,
                'nik_ibu' => $nikIbu,
                'nik_ayah' => $nikAyah,
                'nama_ibu' => $p[0],
                'nama_ayah' => $p[1],
                'pekerjaan_ibu' => $pekIbu,
                'pekerjaan_ayah' => $pekAyah,
                'no_hp_whatsapp' => '081269' . str_pad($idx + 10, 6, '0', STR_PAD_LEFT),
                'alamat' => $p[2] . ', Banda Aceh',
                'kecamatan' => 'Kuta Alam',
            ]);
        }

        // -------------------------------------------------------------
        // 5. MASTER DATA 80 BALITA REALISTIS
        // -------------------------------------------------------------
        $rawBalitas = [
            // --- POSYANDU 1: GAMPONG LAMPULO (28 Balita) ---
            ['Muhammad Al-Fatih Pratama', 'L', 14, 3.2, 50.0, 34.0, 'Normal'],
            ['Aisyah Humaira Syahputra', 'P', 8, 3.0, 49.0, 33.5, 'Normal'],
            ['Khadijah Azzahra', 'P', 18, 2.5, 46.5, 32.0, 'Stunting'],
            ['Safiyya Salsabila Hakim', 'P', 15, 2.7, 48.0, 33.0, 'Risiko'],
            ['Ziyad Atharizz Permana', 'L', 30, 3.5, 51.5, 35.0, 'Normal'],
            ['Shafira Nurul Izzah', 'P', 24, 3.2, 50.0, 34.0, 'Normal'],
            ['Zea Khalisa Farhan', 'P', 13, 2.8, 47.5, 33.0, 'Risiko'],
            ['Arsenio Daffa Pratama', 'L', 20, 3.4, 51.0, 35.0, 'Normal'],
            ['Nadzira Shaqueena', 'P', 6, 3.0, 48.5, 33.5, 'Normal'],
            ['Muhammad Hanif Al-Ghazi', 'L', 4, 3.3, 50.0, 34.0, 'Normal'], // Bayi < 6 bln
            ['Cut Alesha Maryam', 'P', 5, 2.9, 48.0, 33.0, 'Normal'],      // Bayi < 6 bln
            ['Teuku Bilal Arkan', 'L', 11, 3.1, 49.5, 34.0, 'Normal'],
            ['Raffa Danendra Pratama', 'L', 9, 3.0, 49.0, 33.5, 'Normal'],
            ['Nabila Syakira Farhan', 'P', 22, 2.6, 46.0, 32.0, 'Stunting'],
            ['Ibrahim Rasyid Maulana', 'L', 16, 3.2, 50.0, 34.2, 'Normal'],
            ['Adiba Khanza Az-Zahra', 'P', 7, 3.0, 48.5, 33.0, 'Normal'],
            ['Althaf Fathan Syah', 'L', 28, 3.4, 51.0, 35.0, 'Normal'],
            ['Cut Syakilla Ramadhani', 'P', 12, 2.7, 47.0, 32.5, 'Risiko'],
            ['Kenzo Abimanyu', 'L', 17, 3.3, 50.5, 34.5, 'Normal'],
            ['Zoya Almahyra', 'P', 3, 3.1, 49.0, 34.0, 'Normal'],           // Bayi < 6 bln
            ['Teuku Gibran Al-Farizi', 'L', 26, 3.5, 51.5, 35.2, 'Normal'],
            ['Inara Shaqueena Zulfikar', 'P', 19, 2.5, 45.5, 31.5, 'Stunting'],
            ['Dzaky Asadel Putra', 'L', 10, 3.2, 49.5, 34.0, 'Normal'],
            ['Mikhaela Putri Safitri', 'P', 21, 3.1, 50.0, 34.0, 'Normal'],
            ['Raffasya Zayn Harun', 'L', 15, 2.8, 47.5, 33.0, 'Risiko'],
            ['Cut Nahla Khadijah', 'P', 32, 3.4, 51.0, 35.0, 'Normal'],
            ['Fatih Ar-Rayyan', 'L', 36, 3.3, 50.5, 34.8, 'Normal'],
            ['Azkia Medina Putri', 'P', 2, 3.0, 48.5, 33.5, 'Normal'],      // Bayi < 6 bln

            // --- POSYANDU 2: GAMPONG PEUNAYONG (26 Balita) ---
            ['Teuku Rayyan Al-Ghifari', 'L', 22, 2.8, 48.0, 33.0, 'Risiko'],
            ['Arkana Zikri Hidayat', 'L', 11, 3.3, 50.5, 34.5, 'Normal'],
            ['Kenzo Arshaka Tanjung', 'L', 9, 3.2, 50.0, 34.0, 'Normal'],
            ['Cut Kayla Putri Danial', 'P', 19, 2.4, 45.0, 31.5, 'Stunting'],
            ['Ibrahim Malik Syah', 'L', 3, 3.3, 50.0, 34.5, 'Normal'],       // Bayi < 6 bln
            ['Mikayla Almahyra Syahputra', 'P', 16, 3.1, 49.0, 33.8, 'Normal'],
            ['Farzan Ahza Dedi', 'L', 13, 3.0, 49.0, 33.5, 'Normal'],
            ['Ayra Mysha Hendri', 'P', 8, 2.9, 48.5, 33.0, 'Normal'],
            ['Muhammad Daffa Rahmat', 'L', 25, 3.4, 51.0, 35.0, 'Normal'],
            ['Kezia Aqeela Dian', 'P', 14, 2.6, 46.5, 32.0, 'Risiko'],
            ['Teuku Arka Faisal', 'L', 29, 3.5, 51.5, 35.5, 'Normal'],
            ['Shanum Azkadina Rizal', 'P', 7, 3.0, 49.0, 33.5, 'Normal'],
            ['Ghaisan Ahmad Fauzan', 'L', 5, 3.2, 50.0, 34.0, 'Normal'],    // Bayi < 6 bln
            ['Kanaya Tabitha Hendri', 'P', 17, 2.5, 45.8, 31.8, 'Stunting'],
            ['Haidar Zhafran Dedi', 'L', 21, 3.2, 50.0, 34.2, 'Normal'],
            ['Cut Syifa Maulida', 'P', 10, 3.1, 49.5, 33.8, 'Normal'],
            ['Atharizz Calief Rahmat', 'L', 27, 3.3, 50.5, 34.8, 'Normal'],
            ['Zalfa Naura Dian', 'P', 4, 2.9, 48.0, 33.0, 'Normal'],        // Bayi < 6 bln
            ['Rayyan Ghibran Pahlevi', 'L', 12, 2.8, 47.5, 33.0, 'Risiko'],
            ['Khalisa Salsabila Hendri', 'P', 23, 3.1, 50.0, 34.0, 'Normal'],
            ['Malik Al-Jabbar Dedi', 'L', 31, 3.4, 51.0, 35.0, 'Normal'],
            ['Zahra Callista Syahputra', 'P', 18, 2.6, 46.0, 32.0, 'Stunting'],
            ['Naufal Zikri Rahmat', 'L', 8, 3.2, 49.5, 34.0, 'Normal'],
            ['Aqila Humairah Dian', 'P', 15, 3.0, 49.0, 33.5, 'Normal'],
            ['Teuku Kenzie Al-Ayyubi', 'L', 34, 3.5, 52.0, 35.5, 'Normal'],
            ['Cut Zahra Amira', 'P', 1, 3.1, 49.0, 34.0, 'Normal'],         // Bayi < 6 bln

            // --- POSYANDU 3: GAMPONG BANDAR BARU (26 Balita) ---
            ['Cut Nayla Khairunnisa', 'P', 5, 3.1, 49.5, 34.0, 'Normal'],    // Bayi < 6 bln
            ['Bilal Ramadhan Fauzi', 'L', 28, 3.4, 51.0, 35.0, 'Normal'],
            ['Ameera Dzahin Setiawan', 'P', 4, 3.0, 49.0, 33.5, 'Normal'],   // Bayi < 6 bln
            ['Fathir Ahmad Pratama', 'L', 7, 3.1, 49.5, 34.0, 'Normal'],
            ['Dzakiandra Rafisqy', 'L', 10, 3.2, 50.0, 34.0, 'Normal'],
            ['Muhammad Zaidan Fauzi', 'L', 15, 3.2, 50.0, 34.2, 'Normal'],
            ['Cut Alesha Putri Bambang', 'P', 20, 3.0, 49.0, 33.5, 'Normal'],
            ['Rasyid Al-Ghifari Tanjung', 'L', 12, 2.7, 47.0, 32.5, 'Risiko'],
            ['Nadine Azkadina Danial', 'P', 25, 3.3, 50.5, 34.8, 'Normal'],
            ['Teuku Arfan Iqbal', 'L', 16, 3.1, 49.5, 34.0, 'Normal'],
            ['Syakira Humairah Bambang', 'P', 22, 2.5, 45.5, 31.5, 'Stunting'],
            ['Alvaro Gavriel Fauzi', 'L', 9, 3.2, 50.0, 34.0, 'Normal'],
            ['Cut Misha Azzahra Danial', 'P', 6, 2.9, 48.5, 33.0, 'Normal'],
            ['Daffa Ibnu Tanjung', 'L', 18, 3.4, 51.0, 35.0, 'Normal'],
            ['Siti Khansa Iqbal', 'P', 14, 2.8, 47.5, 33.0, 'Risiko'],
            ['Teuku Barra Danial', 'L', 30, 3.5, 51.5, 35.5, 'Normal'],
            ['Cut Queenira Bambang', 'P', 11, 3.0, 49.0, 33.5, 'Normal'],
            ['Muhammad Azzam Fauzi', 'L', 3, 3.2, 49.5, 34.0, 'Normal'],    // Bayi < 6 bln
            ['Raline Shahia Tanjung', 'P', 27, 3.3, 50.5, 34.5, 'Normal'],
            ['Fathan Al-Farisi Iqbal', 'L', 21, 2.6, 46.2, 32.0, 'Stunting'],
            ['Cut Kiara Danial', 'P', 13, 3.1, 49.5, 34.0, 'Normal'],
            ['Zhafran Khalid Bambang', 'L', 8, 3.2, 50.0, 34.2, 'Normal'],
            ['Azkadina Naura Fauzi', 'P', 19, 3.0, 49.0, 33.5, 'Normal'],
            ['Teuku Reynard Tanjung', 'L', 24, 3.4, 51.0, 35.0, 'Normal'],
            ['Cut Nadia Humairah', 'P', 5, 3.0, 49.0, 33.5, 'Normal'],       // Bayi < 6 bln
            ['Rayyan Zhafar Iqbal', 'L', 35, 3.5, 52.0, 35.5, 'Normal'],
        ];

        $totalBalitas = count($rawBalitas);

        foreach ($rawBalitas as $idx => $b) {
            // Posyandu allocation:
            // idx 0..27 (28 balitas) -> Posyandu 1 (Lampulo)
            // idx 28..53 (26 balitas) -> Posyandu 2 (Peunayong)
            // idx 54..79 (26 balitas) -> Posyandu 3 (Bandar Baru)
            if ($idx < 28) {
                $posyandu = $posyandu1;
                $kader = $kaders[0];
            } elseif ($idx < 54) {
                $posyandu = $posyandu2;
                $kader = $kaders[1];
            } else {
                $posyandu = $posyandu3;
                $kader = $kaders[2];
            }

            $parent = $orangTuas[$idx % count($orangTuas)];

            $nama = $b[0];
            $jk = $b[1];
            $umurBulan = $b[2];
            $beratLahir = $b[3];
            $panjangLahir = $b[4];
            $lkLahir = $b[5];
            $targetStatus = $b[6];

            $tglLahir = $now->copy()->subMonths($umurBulan)->subDays(rand(1, 20));
            $nikBalita = '117101' . $tglLahir->format('dmy') . str_pad($idx + 1, 4, '0', STR_PAD_LEFT);
            $bpjsBalita = '000' . rand(1000000000, 9999999999);

            $balita = Balita::create([
                'orang_tua_id' => $parent->id,
                'posyandu_id' => $posyandu->id,
                'nik' => $nikBalita,
                'no_bpjs' => $bpjsBalita,
                'nama' => $nama,
                'jenis_kelamin' => $jk,
                'tanggal_lahir' => $tglLahir->format('Y-m-d'),
                'berat_lahir' => $beratLahir,
                'panjang_lahir' => $panjangLahir,
                'lingkar_kepala_lahir' => $lkLahir,
            ]);

            // Tentukan berapa bulan riwayat pengukuran (1 s/d 4 bulan)
            $historyMonths = min(4, max(1, $umurBulan));

            // Tentukan apakah balita ini SUDAH diukur di bulan berjalan (Agustus 2026 / m=0)
            // Sekitar 70% sudah diukur, 30% belum diukur (untuk variasi filter dashboard)
            $isMeasuredThisMonth = ($idx % 10) < 7;
            $startMonth = $isMeasuredThisMonth ? 0 : 1;

            // Apakah balita ini absen bulan lalu (m=1)?
            $isAbsentLastMonth = ($idx % 15 === 0);

            // Simulasikan parameter dasar pertumbuhan
            for ($m = $historyMonths - 1; $m >= $startMonth; $m--) {
                if ($m === 1 && $isAbsentLastMonth) {
                    continue; // Lewatkan bulan lalu untuk membuat data 'absen_bulan_lalu'
                }

                $tglUkur = $now->copy()->subMonths($m)->startOfMonth()->addDays(rand(3, 20));
                $curAge = $umurBulan - $m;
                if ($curAge < 0) $curAge = 0;

                // Base values calculated from age and target status
                if ($targetStatus === 'Stunting') {
                    $tbVal = 48.0 + ($curAge * 1.5); // Pertumbuhan tinggi terhambat (< -2 SD)
                    $bbVal = 3.0 + ($curAge * 0.28);
                    $lkVal = 33.0 + ($curAge * 0.35);
                    $naikStatus = ($m === $startMonth) ? 'T' : 'N';
                    $valStatus = ($m === 0 && $idx % 4 === 0) ? 'pending' : 'approved';
                    $catKader = 'Nafsu makan kurang, riwayat BBLR / sakit.';
                    $catVal = 'Diberikan paket intervensi PMT & konseling gizi Puskesmas.';
                } elseif ($targetStatus === 'Risiko') {
                    $tbVal = 49.0 + ($curAge * 1.8);
                    $bbVal = 3.2 + ($curAge * 0.32);
                    $lkVal = 33.5 + ($curAge * 0.4);
                    $naikStatus = 'T'; // Tidak naik
                    $valStatus = ($m === 0 && $idx % 3 === 0) ? 'pending' : 'approved';
                    $catKader = 'Kenaikan BB di bawah KBM (<200g), evaluasi MPASI.';
                    $catVal = 'Konseling pemberian makanan kaya protein hewani.';
                } else {
                    $tbVal = 50.0 + ($curAge * 2.1); // Normal ideal
                    $bbVal = 3.3 + ($curAge * 0.48);
                    $lkVal = 34.0 + ($curAge * 0.45);
                    $naikStatus = ($m === $historyMonths - 1) ? 'B' : 'N';
                    $valStatus = ($m === 0 && $idx % 5 === 0) ? 'pending' : 'approved';
                    $catKader = 'Balita sehat, nafsu makan baik dan aktif.';
                    $catVal = 'Pertumbuhan normal sesuai usia.';
                }

                // Cek simulasi revisi / reject khusus untuk 2 balita testing
                if ($m === 0 && ($idx === 4 || $idx === 32)) {
                    $valStatus = 'rejected';
                    $bbVal += 3.5; // Typo ekstrem untuk memicu anomali validasi
                    $catKader = 'Timbangan terbaca melonjak (kemungkinan salah catat).';
                    $catVal = 'Anomali kenaikan ekstrem dalam 1 bulan. Mohon timbang ulang balita.';
                }

                $asiStatus = ($curAge <= 6);

                $calcResult = $calculator->calculate(
                    $balita->tanggal_lahir,
                    $tglUkur,
                    $balita->jenis_kelamin,
                    $bbVal,
                    $tbVal
                );

                Pengukuran::create([
                    'balita_id' => $balita->id,
                    'kader_id' => $kader->id,
                    'tanggal_ukur' => $tglUkur,
                    'umur_bulan' => $calcResult['umur_bulan'],
                    'berat_badan' => round($bbVal, 2),
                    'tinggi_badan' => round($tbVal, 1),
                    'lingkar_kepala' => round($lkVal, 1),
                    'asi_eksklusif' => $asiStatus,
                    'z_score_bbu' => $calcResult['z_score_bbu'],
                    'z_score_tbu' => $calcResult['z_score_tbu'],
                    'status_gizi' => $calcResult['status_gizi'],
                    'status_kenaikan' => $naikStatus,
                    'status_validasi' => $valStatus,
                    'catatan_validator' => $catVal,
                    'catatan_kader' => $catKader,
                ]);
            }
        }

        // -------------------------------------------------------------
        // 6. JADWAL POSYANDU REALISTIS (6 JADWAL)
        // -------------------------------------------------------------
        $jadwalData = [
            [
                'posyandu_id' => $posyandu1->id,
                'judul' => 'Layanan Penimbangan & Imunisasi Balita Agustus 2026',
                'lokasi' => 'Balai Pertemuan Warga Gampong Lampulo',
                'tanggal' => $now->copy()->addDays(4)->format('Y-m-d'),
                'waktu_mulai' => '08:30',
                'waktu_selesai' => '11:30',
                'catatan' => 'Membawa buku KIA & kartu BPJS anak. Tersedia PMT Bubur Kacang Hijau + Telur Puyuh.',
            ],
            [
                'posyandu_id' => $posyandu2->id,
                'judul' => 'Pemberian Vitamin A & Obat Cacing Balita',
                'lokasi' => 'Kompleks Rukun Warga Peunayong',
                'tanggal' => $now->copy()->addDays(10)->format('Y-m-d'),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '12:00',
                'catatan' => 'Bulan kapsul Vitamin A (Biru untuk 6-11 bulan, Merah untuk 12-59 bulan).',
            ],
            [
                'posyandu_id' => $posyandu3->id,
                'judul' => 'Konseling Gizi Balita & PMT Berkelanjutan',
                'lokasi' => 'Balai Desa Bandar Baru',
                'tanggal' => $now->copy()->addDays(18)->format('Y-m-d'),
                'waktu_mulai' => '08:30',
                'waktu_selesai' => '11:30',
                'catatan' => 'Didampingi oleh Ahli Gizi Puskesmas Kuta Alam untuk balita berat badan kurang.',
            ],
            [
                'posyandu_id' => $posyandu1->id,
                'judul' => 'Sweeping Penimbangan Balita Rentan Stunting',
                'lokasi' => 'Wilayah RT 03 & 04 Gampong Lampulo',
                'tanggal' => $now->copy()->addDays(25)->format('Y-m-d'),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '12:00',
                'catatan' => 'Kunjungan rumah bagi balita yang absen pada hari H Posyandu.',
            ],
            [
                'posyandu_id' => $posyandu2->id,
                'judul' => 'Edukasi MPASI Kaya Protein Hewani',
                'lokasi' => 'Kompleks Rukun Warga Peunayong',
                'tanggal' => $now->copy()->addDays(28)->format('Y-m-d'),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '11:30',
                'catatan' => 'Demo masak MPASI berbahan ikan lokal dan telur bersama Kader & Tenaga Gizi.',
            ],
            [
                'posyandu_id' => $posyandu1->id,
                'judul' => 'Penimbangan Rutin & Imunisasi Balita Juli 2026',
                'lokasi' => 'Balai Pertemuan Warga Gampong Lampulo',
                'tanggal' => $now->copy()->subDays(28)->format('Y-m-d'),
                'waktu_mulai' => '08:30',
                'waktu_selesai' => '11:30',
                'catatan' => 'Kegiatan selesai dilaksanakan. Kehadiran 94% dari total sasaran balita.',
            ],
        ];

        foreach ($jadwalData as $jd) {
            $kaderForPosyandu = Kader::where('posyandu_id', $jd['posyandu_id'])->first();
            $jd['kader_id'] = $kaderForPosyandu ? $kaderForPosyandu->id : $kaders[0]->id;
            Jadwal::create($jd);
        }
    }
}
