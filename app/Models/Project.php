<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkSession;
use App\Models\Invoice;

class Project extends Model
{
    use HasFactory;

    public function work_session() {
        return $this->hasMany(WorkSession::class);
    }

    public function invoice() {
        return $this->hasMany(Invoice::class);
    }

       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'client', 'email', 'phone', 'is_completed',
    ];
}
