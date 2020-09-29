<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class InvoicesToWorksession extends Model
{
    use HasFactory;
    
    public function invoice() {
        return $this->hasMany(Invoice::class, 'invoices_to_worksession', 'invoice_id', 'work_session_id');
    }

    protected $table = 'invoices_to_worksessions';
    protected $fillable = ['work_session_id', 'invoice_id'];
}

