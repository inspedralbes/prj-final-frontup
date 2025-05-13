<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NivellUsuaris extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nivells_usuaris';

    protected $fillable = [
        'title',
        'description',
        'initial_html',
        'initial_css',
        'initial_js',
        'expected_html',
        'expected_css',
        'expected_js',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}