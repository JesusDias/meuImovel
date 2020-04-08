<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    //sobrescrevendo o nome da tabela
    protected $table = 'real_state';

    //listando os dados que eu permito que sejam persistidos
    protected $fillable = [
        'user_id', 'title', 'description', 'content',
        'price', 'slug', 'badrooms', 'bathrooms', 'property_area', 'total_property_area'
    ];
    public function user()
    {
        return $this->belongsTo(User::class); //um imovel tem um dono
    }

	public function categories()
	{
		return $this->belongsToMany(Category::class, 'real_state_categories');//um imovel tem varias categorias
    }
    
    //O segundo parâmetro que ta levando esse nome da tabela está ai porque no banco de dados está com nome diferente.
}
