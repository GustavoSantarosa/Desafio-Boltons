<?php

namespace App\Models\Faturamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nfe extends Model
{
    use HasFactory;

    protected $table = "nfes";
    public $timestamps = false;

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

    public function findByChnfe($accessKey)
    {
        return $this->select()->where("chnfe", $accessKey)->first();
    }
}
