<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRecord extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'training_records';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'venue',
        'proof_uploaded',
        'office_code',
        'nature_of_training',
        'nature_of_training_other',
        'scope',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'proof_uploaded' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the user that owns the training record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the office associated with the training record.
     */
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_code', 'code');
    }
    
    /**
     * Get the proof associated with the training record.
     */
    public function proof()
    {
        return $this->hasOne(TrainingProof::class, 'training_id', 'id');
    }
}