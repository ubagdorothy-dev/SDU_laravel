<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    // Set the table name if it deviates from Laravel's convention (e.g., 'offices' is fine)
    protected $table = 'offices';

    // Disable timestamps if you don't have 'created_at'/'updated_at' columns (which your schema lacks)
    public $timestamps = false; 

    // Define the fillable attributes if you plan to create/update records
    protected $fillable = [
        'name',
        'code',
    ];
    
    // The primary key is 'id' by default, which is correct for your schema.
}