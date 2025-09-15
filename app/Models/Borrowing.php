<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'client_id',
        'book_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status'
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function book(){
        return $this->belongsTo(Book::class);
    }
}
