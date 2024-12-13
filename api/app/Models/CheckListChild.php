<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckListChild extends Model
{
    use HasFactory;
    const STATUS = [
        'default' => 0,
        'done' => 1
    ];
    protected $table = 'check_list_child';
    protected $fillable = ['title', 'check_list_id', 'status'];
}
