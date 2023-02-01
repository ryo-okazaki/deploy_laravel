<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
