<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {
    protected $fillable = ['actor_id', 'action', 'subject_type', 'subject_id', 'meta'];
    protected $casts = ['meta' => 'array'];
    
    public function actor() { return $this->belongsTo(User::class, 'actor_id'); }
}