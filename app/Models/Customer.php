<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
    ];

    #[Scope]
    protected function search($query, $search): void
    {
        $query->when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
}
