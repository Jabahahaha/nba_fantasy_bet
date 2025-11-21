<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $contests = Contest::orderBy('contest_date', 'desc')->get();
        $users = User::orderBy('points_balance', 'desc')->take(10)->get();

        return view('admin.dashboard', compact('contests', 'users'));
    }

    /**
     * Show contest creation form
     */
    public function createContest()
    {
        return view('admin.create-contest');
    }

    /**
     * Show data update page
     */
    public function updateData()
    {
        return view('admin.update-data');
    }

    /**
     * Update player roster
     */
    public function updateRoster(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $filename = 'players_' . time() . '.csv';
        $file->storeAs('', $filename);

        // Run import command
        \Artisan::call('import:players', ['file' => $filename]);

        $output = \Artisan::output();

        return back()->with('success', 'Roster updated successfully!')
            ->with('roster_output', $output);
    }

    /**
     * Update game schedule
     */
    public function updateSchedule(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $filename = 'games_' . time() . '.csv';
        $file->storeAs('', $filename);

        // Run import command
        \Artisan::call('import:games', ['file' => $filename]);

        $output = \Artisan::output();

        return back()->with('success', 'Schedule updated successfully!')
            ->with('schedule_output', $output);
    }
}
