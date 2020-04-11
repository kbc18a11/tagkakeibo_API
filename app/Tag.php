<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Tag extends Model
{
    //
    protected $table = 'Tags';

    /**
     * 初期登録のバリデーションの条件
     * @var array
     */
    private static $createrules = [
        'user_id' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:255'],
        'profit_type' => ['required', 'integer','between:-1,1'],
    ];

    /**
     * 初期登録のバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function createvalidator(array $array)
    {
        # code...
        return Validator::make($array, Tag::$createrules);
    }
}
