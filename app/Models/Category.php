<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getparent_category():BelongsTo{
        return $this->belongsTo(Category::class,'parent_id');
    }
    public function getmaster_category():BelongsTo{
        return $this->belongsTo(Category::class,'master_id');
    }
}
