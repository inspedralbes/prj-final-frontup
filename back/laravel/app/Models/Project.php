<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'nombre',
        'user_id',
        'html_code',
        'css_code',
        'js_code',
        'statuts'
    ];
    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function likes(){
        return $this->hasMany(Likes::class, 'project_id');
    }
}
