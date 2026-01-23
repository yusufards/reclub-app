<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'created_by'];

    public function host()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'venue_sport');
    }
}