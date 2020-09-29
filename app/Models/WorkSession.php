<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class WorkSession extends Model
{
    use HasFactory;

    public function project() {
        return $this->belongsToOne('App\Project');
    }
         /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'minutes_worked', 'description',
    ];
}
