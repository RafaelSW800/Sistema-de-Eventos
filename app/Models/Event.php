<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//colocar o event em inicial maiúscula se não der algum erro desconhecido.
class event extends Model
{
    use HasFactory;

    //Essa seção diz ao Laravel que o Items é do tipo array e data.
    protected $casts =[
        'Items' => 'array'
    ];
    protected $dates = ['Date'];
}