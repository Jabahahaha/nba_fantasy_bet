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
        try {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt',
            ]);

            $file = $request->file('csv_file');

            if (!$file) {
                return back()->with('error', 'No file uploaded.');
            }

            $filename = 'players_' . time() . '.csv';
            $path = $file->storeAs('', $filename);

            if (!$path) {
                return back()->with('error', 'Failed to save uploaded file.');
            }

            $exitCode = \Artisan::call('import:players', ['file' => $filename]);
            $output = \Artisan::output();

            \Storage::delete($filename);

            if ($exitCode !== 0) {
                return back()->with('error', 'Import failed. Check the file format.')
                    ->with('roster_output', $output);
            }

            return back()->with('success', 'Roster updated successfully!')
                ->with('roster_output', $output);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update game schedule
     */
    public function updateSchedule(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt',
            ]);

            $file = $request->file('csv_file');

            if (!$file) {
                return back()->with('error', 'No file uploaded.');
            }

            $filename = 'games_' . time() . '.csv';
            $path = $file->storeAs('', $filename);

            if (!$path) {
                return back()->with('error', 'Failed to save uploaded file.');
            }

            $exitCode = \Artisan::call('import:games', ['file' => $filename]);
            $output = \Artisan::output();

            \Storage::delete($filename);

            if ($exitCode !== 0) {
                return back()->with('error', 'Import failed. Check the file format.')
                    ->with('schedule_output', $output);
            }

            return back()->with('success', 'Schedule updated successfully!')
                ->with('schedule_output', $output);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
