<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenyesuaianRencanaProjectSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_addendum' => 'ADD-001',
                'kode_project'  => 'PRJ-A100',
                'aktivitas'     => 'Analisis Kebutuhan',
                'level'         => 1,
                'parent_id'     => null,
                'bobot'         => 10.5,
                'tanggal_mulai' => '2025-10-01',
                'tanggal_akhir' => '2025-10-07',
                'minggu_ke'     => 40,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode_addendum' => 'ADD-002',
                'kode_project'  => 'PRJ-A100',
                'aktivitas'     => 'Desain Database',
                'level'         => 2,
                'parent_id'     => 1,
                'bobot'         => 8.0,
                'tanggal_mulai' => '2025-10-05',
                'tanggal_akhir' => '2025-10-12',
                'minggu_ke'     => 41,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode_addendum' => 'ADD-003',
                'kode_project'  => 'PRJ-A100',
                'aktivitas'     => 'Setup Environment',
                'level'         => 2,
                'parent_id'     => 1,
                'bobot'         => 6.0,
                'tanggal_mulai' => '2025-10-03',
                'tanggal_akhir' => '2025-10-06',
                'minggu_ke'     => 40,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode_addendum' => 'ADD-004',
                'kode_project'  => 'PRJ-A200',
                'aktivitas'     => 'Frontend Development',
                'level'         => 1,
                'parent_id'     => null,
                'bobot'         => 12.0,
                'tanggal_mulai' => '2025-10-08',
                'tanggal_akhir' => '2025-10-20',
                'minggu_ke'     => 41,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode_addendum' => 'ADD-005',
                'kode_project'  => 'PRJ-A200',
                'aktivitas'     => 'Backend API',
                'level'         => 1,
                'parent_id'     => null,
                'bobot'         => 15.0,
                'tanggal_mulai' => '2025-10-10',
                'tanggal_akhir' => '2025-10-25',
                'minggu_ke'     => 42,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            // 👉 tinggal diterusin sampai ADD-020
        ];

        // generate sisa data biar 20 rows
        for ($i = 6; $i <= 20; $i++) {
            $data[] = [
                'kode_addendum' => 'ADD-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'kode_project'  => 'PRJ-B' . rand(100, 999),
                'aktivitas'     => 'Task ke-' . $i,
                'level'         => rand(1, 3),
                'parent_id'     => null,
                'bobot'         => rand(5, 20),
                'tanggal_mulai' => Carbon::now()->addDays($i)->format('Y-m-d'),
                'tanggal_akhir' => Carbon::now()->addDays($i + 3)->format('Y-m-d'),
                'minggu_ke'     => Carbon::now()->addDays($i)->weekOfYear,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        DB::table('penyesuaian_rencana_projects')->insert($data);
    }
}
