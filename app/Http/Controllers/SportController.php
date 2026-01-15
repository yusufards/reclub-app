<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SportController extends Controller
{
    // Admin only via Resource Route
    public function index() {
        $sports = Sport::with('host')->paginate(10); // 'host' refers to creator via created_by
        return view('admin.sports.index', compact('sports'));
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:sports']);
        
        $sport = Sport::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id()
        ]);

        ActivityLog::create([
            'actor_id' => Auth::id(),
            'action' => 'sport_created',
            'subject_type' => Sport::class,
            'subject_id' => $sport->id
        ]);

        return back()->with('success', 'Sport created');
    }

    public function destroy(Sport $sport) {
        $sport->delete();
        return back()->with('success', 'Sport deleted');
    }
}