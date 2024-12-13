<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckList extends Model
{
    use HasFactory;
    const STATUS = [
        'default' => 0,
        'done' => 1,
    ];
    protected $table = 'check_lists';
    protected $fillable = ['title', 'card_id', 'status'];

    public function checkListChilds()
    {
        return $this->hasMany(CheckListChild::class);
    }
}
