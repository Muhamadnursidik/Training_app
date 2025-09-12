<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Master\Rencanaproject\Models\Model as RencanaProject;
use Carbon\Carbon;

class RencanaProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample data untuk testing
        $projects = [
            [
                'kode_project' => 'PRJ001',
                'nama_project' => 'Sistem Informasi Manajemen',
                'activities' => [
                    [
                        'aktivitas' => 'Analisis Kebutuhan Sistem',
                        'level' => 1,
                        'bobot' => 20,
                        'tanggal_mulai' => '2024-01-01',
                        'tanggal_akhir' => '2024-01-15',
                        'children' => [
                            [
                                'aktivitas' => 'Wawancara dengan Stakeholder',
                                'level' => 2,
                                'bobot' => 10,
                                'tanggal_mulai' => '2024-01-01',
                                'tanggal_akhir' => '2024-01-05',
                            ],
                            [
                                'aktivitas' => 'Dokumentasi Kebutuhan',
                                'level' => 2,
                                'bobot' => 10,
                                'tanggal_mulai' => '2024-01-06',
                                'tanggal_akhir' => '2024-01-15',
                            ]
                        ]
                    ],
                    [
                        'aktivitas' => 'Desain Sistem',
                        'level' => 1,
                        'bobot' => 25,
                        'tanggal_mulai' => '2024-01-16',
                        'tanggal_akhir' => '2024-02-15',
                        'children' => [
                            [
                                'aktivitas' => 'Database Design',
                                'level' => 2,
                                'bobot' => 12,
                                'tanggal_mulai' => '2024-01-16',
                                'tanggal_akhir' => '2024-01-30',
                            ],
                            [
                                'aktivitas' => 'UI/UX Design',
                                'level' => 2,
                                'bobot' => 13,
                                'tanggal_mulai' => '2024-01-31',
                                'tanggal_akhir' => '2024-02-15',
                                'children' => [
                                    [
                                        'aktivitas' => 'Wireframe',
                                        'level' => 3,
                                        'bobot' => 5,
                                        'tanggal_mulai' => '2024-01-31',
                                        'tanggal_akhir' => '2024-02-05',
                                    ],
                                    [
                                        'aktivitas' => 'Prototype',
                                        'level' => 3,
                                        'bobot' => 8,
                                        'tanggal_mulai' => '2024-02-06',
                                        'tanggal_akhir' => '2024-02-15',
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'aktivitas' => 'Development',
                        'level' => 1,
                        'bobot' => 40,
                        'tanggal_mulai' => '2024-02-16',
                        'tanggal_akhir' => '2024-04-30',
                        'children' => [
                            [
                                'aktivitas' => 'Backend Development',
                                'level' => 2,
                                'bobot' => 20,
                                'tanggal_mulai' => '2024-02-16',
                                'tanggal_akhir' => '2024-03-31',
                            ],
                            [
                                'aktivitas' => 'Frontend Development',
                                'level' => 2,
                                'bobot' => 20,
                                'tanggal_mulai' => '2024-03-01',
                                'tanggal_akhir' => '2024-04-30',
                            ]
                        ]
                    ],
                    [
                        'aktivitas' => 'Testing',
                        'level' => 1,
                        'bobot' => 10,
                        'tanggal_mulai' => '2024-05-01',
                        'tanggal_akhir' => '2024-05-15',
                        'children' => [
                            [
                                'aktivitas' => 'Unit Testing',
                                'level' => 2,
                                'bobot' => 5,
                                'tanggal_mulai' => '2024-05-01',
                                'tanggal_akhir' => '2024-05-07',
                            ],
                            [
                                'aktivitas' => 'Integration Testing',
                                'level' => 2,
                                'bobot' => 5,
                                'tanggal_mulai' => '2024-05-08',
                                'tanggal_akhir' => '2024-05-15',
                            ]
                        ]
                    ],
                    [
                        'aktivitas' => 'Deployment & Go Live',
                        'level' => 1,
                        'bobot' => 5,
                        'tanggal_mulai' => '2024-05-16',
                        'tanggal_akhir' => '2024-05-31',
                    ]
                ]
            ],
            [
                'kode_project' => 'PRJ002',
                'nama_project' => 'Mobile Application',
                'activities' => [
                    [
                        'aktivitas' => 'Planning & Research',
                        'level' => 1,
                        'bobot' => 15,
                        'tanggal_mulai' => '2024-02-01',
                        'tanggal_akhir' => '2024-02-14',
                        'children' => [
                            [
                                'aktivitas' => 'Market Research',
                                'level' => 2,
                                'bobot' => 8,
                                'tanggal_mulai' => '2024-02-01',
                                'tanggal_akhir' => '2024-02-07',
                            ],
                            [
                                'aktivitas' => 'Competitor Analysis',
                                'level' => 2,
                                'bobot' => 7,
                                'tanggal_mulai' => '2024-02-08',
                                'tanggal_akhir' => '2024-02-14',
                            ]
                        ]
                    ],
                    [
                        'aktivitas' => 'App Design',
                        'level' => 1,
                        'bobot' => 20,
                        'tanggal_mulai' => '2024-02-15',
                        'tanggal_akhir' => '2024-03-15',
                        'children' => [
                            [
                                'aktivitas' => 'User Flow Design',
                                'level' => 2,
                                'bobot' => 10,
                                'tanggal_mulai' => '2024-02-15',
                                'tanggal_akhir' => '2024-02-29',
                            ],
                            [
                                'aktivitas' => 'Screen Design',
                                'level' => 2,
                                'bobot' => 10,
                                'tanggal_mulai' => '2024-03-01',
                                'tanggal_akhir' => '2024-03-15',
                            ]
                        ]
                    ],
                    [
                        'aktivitas' => 'Mobile Development',
                        'level' => 1,
                        'bobot' => 50,
                        'tanggal_mulai' => '2024-03-16',
                        'tanggal_akhir' => '2024-06-15',
                        'children' => [
                            [
                                'aktivitas' => 'Android Development',
                                'level' => 2,
                                'bobot' => 25,
                                'tanggal_mulai' => '2024-03-16',
                                'tanggal_akhir' => '2024-05-15',
                            ],
                            [
                                'aktivitas' => 'iOS Development',
                                'level' => 2,
                                'bobot' => 25,
                                'tanggal_mulai' => '2024-04-01',
                                'tanggal_akhir' => '2024-06-15',
                            ]
                        ]
                    ],
                    [
                        'aktivitas' => 'Quality Assurance',
                        'level' => 1,
                        'bobot' => 10,
                        'tanggal_mulai' => '2024-06-16',
                        'tanggal_akhir' => '2024-06-30',
                    ],
                    [
                        'aktivitas' => 'App Store Release',
                        'level' => 1,
                        'bobot' => 5,
                        'tanggal_mulai' => '2024-07-01',
                        'tanggal_akhir' => '2024-07-15',
                    ]
                ]
            ]
        ];

        foreach ($projects as $projectData) {
            $this->createActivitiesRecursive($projectData['kode_project'], $projectData['activities']);
        }

        $this->command->info('Rencana Project seeder completed successfully!');
    }

    /**
     * Create activities recursively
     */
    private function createActivitiesRecursive($kodeProject, $activities, $parentId = null, $level = 1)
    {
        foreach ($activities as $activityData) {
            $mingguKe = null;
            if (!empty($activityData['tanggal_mulai'])) {
                $mingguKe = Carbon::parse($activityData['tanggal_mulai'])->weekOfYear;
            }

            $activity = RencanaProject::create([
                'kode_project' => $kodeProject,
                'aktivitas' => $activityData['aktivitas'],
                'level' => $parentId ? $level : ($activityData['level'] ?? 1),
                'parent_id' => $parentId,
                'bobot' => $activityData['bobot'] ?? 0,
                'tanggal_mulai' => $activityData['tanggal_mulai'] ?? null,
                'tanggal_akhir' => $activityData['tanggal_akhir'] ?? null,
                'minggu_ke' => $mingguKe,
            ]);

            // Create children recursively if exist
            if (isset($activityData['children']) && is_array($activityData['children'])) {
                $this->createActivitiesRecursive(
                    $kodeProject, 
                    $activityData['children'], 
                    $activity->id, 
                    $activity->level + 1
                );
            }
        }
    }

    /**
     * Generate random project data for testing
     */
    private function generateTestData()
    {
        $projectCodes = ['PRJ003', 'PRJ004', 'PRJ005'];
        $activities = [
            'Inisiasi Project', 'Perencanaan Detail', 'Eksekusi Phase 1', 
            'Review & Evaluasi', 'Eksekusi Phase 2', 'Testing & QA',
            'User Acceptance Test', 'Go Live', 'Post Implementation Review'
        ];

        foreach ($projectCodes as $code) {
            $startDate = Carbon::now()->addDays(rand(-30, 30));
            
            foreach ($activities as $index => $activity) {
                $activityStartDate = $startDate->copy()->addDays($index * 7);
                $activityEndDate = $activityStartDate->copy()->addDays(rand(3, 10));

                RencanaProject::create([
                    'kode_project' => $code,
                    'aktivitas' => $activity,
                    'level' => 1,
                    'parent_id' => null,
                    'bobot' => rand(5, 20),
                    'tanggal_mulai' => $activityStartDate->format('Y-m-d'),
                    'tanggal_akhir' => $activityEndDate->format('Y-m-d'),
                    'minggu_ke' => $activityStartDate->weekOfYear,
                ]);
            }
        }
    }
}