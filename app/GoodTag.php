<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class GoodTag extends Model
{
    /***
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id', 'tag_id'
    ];

    /**
     * 初期登録のバリデーションの条件
     * @var array
     */
    private static $createrules = [
        'tag_id' => ['exists:tags,id']
    ];


    /**
     * 初期登録のバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function createvalidator(array $array)
    {
        # code...
        return Validator::make($array, GoodTag::$createrules);
    }

}
