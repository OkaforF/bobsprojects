<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\WorkSession;

class Invoice extends Model
{
    use HasFactory;

         /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'due_date', 'total_cost', 'is_paid',
    ];

    public function work_session() {
        return $this->hasMany(WorkSession::class);
    }
}

