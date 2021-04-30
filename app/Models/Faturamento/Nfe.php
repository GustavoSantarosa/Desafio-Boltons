<?php

namespace App\Models\Faturamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nfe extends Model
{
    use HasFactory;

    protected $table = "nfes";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chnfe',
        'nnf',
        'vnf',
        'xml',
    ];
}
