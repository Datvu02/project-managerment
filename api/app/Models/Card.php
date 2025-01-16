<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    const STATUS =
    [
         'default' => 0,
         'is_almost_expired' => 2,
         'expired' => 1,
         'done' => 3,
         'submit' => 4,
    ];
    const COMPLETION_STATUS =
    [
         'default' => 0,
         'submitted' => 1,
         'checkSubmitDone' => 2,
         'checkSubmitFailed' => 3,
    ];
    protected $table = 'cards';
    protected $fillable = ['title', 'description', 'status', 'directory_id', 'index', 'deadline','user_id', 'completion_status', 'completion_deadline'];

    public function labels()
    {
        return $this->belongsToMany(Label::class)->withTimestamps();
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }

    public function checkLists()
    {
        return $this->hasMany(CheckList::class);
    }
}
