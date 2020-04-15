<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Tag extends Model
{
    //
    protected $table = 'Tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','name', 'profit_type', 'comment'
    ];

    /**
     * 初期登録のバリデーションの条件
     * @var array
     */
    private static $createrules = [
        'name' => ['required', 'string', 'max:255'],
        'profit_type' => ['required', 'integer','between:-1,1'],
        'comment' => ['max:255']
    ];

    /**
     * 更新のバリデーションの条件
     * @var array
     */
    private static $updaterules = [
        'name' => ['required', 'string', 'max:255'],
        'profit_type' => ['required', 'integer','between:-1,1'],
        'comment' => ['max:255']
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

    /**
     * 更新のバリデーションの検証
     * @param array
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function updatevalidator(array $array)
    {
        # code...
        return Validator::make($array, Tag::$updaterules);
    }

    /**
     * 対象のタグと比較するユーザidが同じか？
     * @param int $id //ユーザid
     * @return bool
     */
    public function user_idCheck(int $id):bool
    {
        return $this->user_id === $id;
    }
}
