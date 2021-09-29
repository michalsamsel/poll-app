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
        'many_answers',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'duplicate_answers' => 'boolean',
        'many_answers' => 'boolean',
    ];

    /**
     * One question have many answers.
     * 
     * @return Answer::class[]
     */
    public function Answers(){
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    /**
     * One question have many results.
     * 
     * @return Answer::class[]
     */
    public function Results(){
        return $this->hasManyThrough(Answer::class, Question::class, 'question_id', 'answer_id', 'id', 'id');
    }
}
