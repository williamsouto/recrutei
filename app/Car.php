<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $primaryKey = 'car_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_id', 'user_id', 'category', 'model', 'motor_power', 'ports', 'board', 'type_vehicle',
        'year', 'fuel', 'mileage', 'exchange', 'direction', 'color', 'options',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Check if the car is related to a user
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
