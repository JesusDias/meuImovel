<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'description', 'slug'
    ];

    public function realStates()
    {
        return $this->belongsToMany(RealState::class, 'real_state_categories');//uma categoria tem varios imoveis
        
        //O segundo parâmetro que ta levando esse nome da tabela está ai porque no banco de dados está com nome diferente.
    }
}
