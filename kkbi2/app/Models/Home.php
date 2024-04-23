<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Home extends Model
{
    use HasFactory, Notifiable;
    
    protected $table = 'MKFPASMI';
    public $incrementing=false;
    protected $primaryKey = 'pass_no'; //changed to pass_no
    public $timestamps = false;
}
