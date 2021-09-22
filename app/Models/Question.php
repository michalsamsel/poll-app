<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'content',
        'duplicate_answers',
        'multiply_answers',
    ];

    /**
     * One question have many answers.
     * 
     * @return Answer::class[]
     */
    public function Answers(){
        return $this->hasMany(Answer::class);
    }
}
