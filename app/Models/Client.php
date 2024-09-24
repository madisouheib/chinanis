<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';

    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    // Optionally, if you want to cast certain attributes to specific types, use this array
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
