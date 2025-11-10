<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResvType extends Model
{
    use HasFactory;

    protected $table = 'shop_reserv_typess';

    protected $fillable = ['RtBit', 'RtName'];

}
