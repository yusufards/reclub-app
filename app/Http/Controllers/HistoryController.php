<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $histories = Room::where(function($query) use ($user) {
                        $query->where('host_id', $user->id)
                              ->orWhereHas('participants', function($q) use ($user) {
                                  $q->where('user_id', $user->id);
                              });
                    })
                    ->where('start_datetime', '<', now()->subHours(2))
                    ->with(['sport', 'venue', 'host', 'participants.user'])
                    ->orderBy('start_datetime', 'desc')
                    ->paginate(10);
        
        return view('user.history', compact('histories'));
    }
}