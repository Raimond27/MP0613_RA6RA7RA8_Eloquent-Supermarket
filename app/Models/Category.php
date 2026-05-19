<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Define the table name explicitly.
    protected $table = 'categories';

    // Define the primary key.
    protected $primaryKey = 'id';

    // Allow these fields to be mass-assigned.
    protected $fillable = [
        'name',
        'description',
        'parent_category',
    ];

    // Define a self-referential relationship to get the parent category.
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category', 'id');
    }
}
