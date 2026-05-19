<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Attributes that can be mass-assigned.
    protected $fillable = ['name'];

    // Relationship: A role has many users.
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
