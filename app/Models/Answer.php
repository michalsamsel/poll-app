<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'question_id',
        'content',        
    ];

    /**
     * Answer belongs to only one question.
     * 
     * @return Question::class
     */
    public function Answers(){
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
