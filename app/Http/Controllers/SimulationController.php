<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Services\GameSimulator;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    protected $simulator;

    public function __construct(GameSimulator $simulator)
    {
        $this->simulator = $simulator;
    }

    /**
     * Simulate a contest (admin only)
     */
    public function simulate($contestId)
    {
        $contest = Contest::findOrFail($contestId);

        if ($contest->status !== 'live' && $contest->status !== 'upcoming') {
            return back()->with('error', 'Contest has already been simulated.');
        }

        // Lock contest if not already locked
        if ($contest->status === 'upcoming') {
            $contest->lock();
        }

        // Run simulation
        try {
            $this->simulator->simulateContest($contest);

            return back()->with('success', 'Contest simulated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Simulation failed: ' . $e->getMessage());
        }
    }
}
