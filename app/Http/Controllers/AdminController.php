<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function users() {
        $users = User::paginate(20);
        return view('admin.users', compact('users'));
    }

    public function activityLogs(Request $request) {
        $query = ActivityLog::with('actor');
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->latest()->paginate(20);
        return view('admin.activity', compact('logs'));
    }
}