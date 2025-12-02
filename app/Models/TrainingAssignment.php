<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingRecord;
use App\Models\User;

class TrainingAssignment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'training_assignments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'training_id',
        'staff_id',
        'assigned_by',
        'assigned_date',
        'deadline',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'assigned_date' => 'date',
        'deadline' => 'date',
    ];

    /**
     * Get the training record that was assigned.
     */
    public function training()
    {
        return $this->belongsTo(TrainingRecord::class, 'training_id');
    }

    /**
     * Get the staff member who was assigned the training.
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'user_id');
    }

    /**
     * Get the user who assigned the training.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'user_id');
    }
}
