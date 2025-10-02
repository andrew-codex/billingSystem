<?php

namespace App\Models;
use App\Models\Consumer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricMeter extends Model
{
use HasFactory;
 protected $table = 'electric_meters'; 

    public const STATUS_ACTIVE   = 'active';
    public const STATUS_UNASSIGNED = 'unassigned';
    public const STATUS_DAMAGED  = 'damaged';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable =[
        'electric_meter_number',
        'status',
        'consumer_id',
        'installation_date',
          'house_type'
    ];



    public function consumer()
{
    return $this->belongsTo(Consumer::class);
}

    public function history()
    {
        return $this->hasMany(ConsumerMeterHistory::class, 'meter_id');
    }


}
