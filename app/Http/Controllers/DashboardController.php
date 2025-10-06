<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'totalReports' => 125,
            'completedReports' => 89,
            'pendingReports' => 23,
            'overdueReports' => 13,
            'reportGrowth' => '+12%',
            'completionRate' => '71%',
            'pendingRate' => '18%',
            'overdueRate' => '10%',
            'recentActivities' => [
                [
                    'title' => 'Report #1024 Completed',
                    'description' => 'Monthly sales report has been approved',
                    'time' => '2 hours ago'
                ],
                [
                    'title' => 'New Report Submitted',
                    'description' => 'Training progress report submitted by John Doe',
                    'time' => '4 hours ago'
                ],
                [
                    'title' => 'Report #1020 Overdue',
                    'description' => 'Quarterly review report is past deadline',
                    'time' => '1 day ago'
                ]
            ]
        ];

        return view('dashboard', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
