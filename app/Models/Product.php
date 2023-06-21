<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable  = ['name', 'type', 'price', 'amount', 'category_id'];

    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps()->withPivot(['status','trade_id','id']);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
