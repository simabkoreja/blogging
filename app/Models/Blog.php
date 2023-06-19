<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'image',
        'user_id',
    ];

    public function getImageAttribute($value)
    {
        if(!empty($value)){
            return 'storage/' . $value;
        }
        return $value;
    }

    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "public";
        $destination_path = "uploads";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

    // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'blog_id')->latest();
    }

    public function user()  {
        return $this->belongsTo(User::class, 'user_id');
    }
}
