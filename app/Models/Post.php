<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = [
        'title',
        'content',
        'date_of_post',
        'category_id',
        'img',
        'view'
      ];

}
