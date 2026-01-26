<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'image',
        'name',
        'description'
    ];

    #[Scope]
    protected function search($query, $search)
    {
        $query->when($search, function($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
}
