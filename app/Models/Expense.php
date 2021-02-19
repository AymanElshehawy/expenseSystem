<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Expense extends Model
{
    use HasFactory;

    public function getFilePathAttribute()
    {
        return (!empty($this->file)) ? URL::to(Storage::url($this->file)) : "";
    }

    public function scopeCurrentUser()
    {
        return $this->where('user_id', '=', auth()->id());
    }
}
