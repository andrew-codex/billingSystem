<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class ConsumerMeterHistory extends Model
{
 use HasFactory;


   protected $table = 'consumer_meter_history';

    protected $fillable = [
        'consumer_id',
        'meter_id',
        'transaction_type',
        'start_date',
        'end_date',
        'remarks',
        'changed_by',
    ];

  
    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }

  
    public function meter()
    {
        return $this->belongsTo(ElectricMeter::class, 'meter_id');
    }

 
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

}
