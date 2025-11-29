<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model {
    // use HasFactory;
    protected $table = 'foods';

    protected $fillable = ['name','slug','category_id','description','price','qty','image','is_active'];


    public function category(){
        return $this->belongsTo(Category::class);
    }
}
