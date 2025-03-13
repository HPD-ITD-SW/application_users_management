<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationType extends Model
{
    protected $table = 'application_types';

    protected $fillable = [
        'type_name',
        'description',
    ];

    public $timestamps = false; // assuming no timestamps based on provided info

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
