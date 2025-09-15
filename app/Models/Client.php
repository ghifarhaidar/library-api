<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
    
    public function borrowedBooks()
    {
        return $this->belongsToMany(Book::class, 'borrowings')
        ->withPivot('borrowed_at', 'due_at', 'returned_at', 'status')
        ->withTimestamps();
    }
}
