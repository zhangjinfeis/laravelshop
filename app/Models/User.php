<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = "user";



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'U';


    /**
     * 会员所属城市
     * @author zjf ${date}
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function belong_city(){
        return $this->belongsTo('App\Models\City','city_id','id');
    }

    /**
     * 会员所属机构
     * @author zjf ${date}
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(){
        return $this->belongsTo('App\Models\Unit','unit_id','id');
    }

    /**
     * 会员所属职位
     * @author zjf ${date}
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profession(){
        return $this->belongsTo('App\Models\Profession','profession_id','id');
    }




}
