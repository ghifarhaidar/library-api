<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'author_id',
        'isbn',
        'year',
        'in_stock',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }


    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
    
    public function borrowers()
    {
        return $this->belongsToMany(Client::class, 'borrowings')
        ->withPivot('borrowed_at', 'due_at', 'returned_at', 'status')
        ->withTimestamps();
    }
}
