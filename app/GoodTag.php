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

    /**
     * 既にいいねしてるか同課の確認
     * @param Int $user_id
     * @param Int $tag_id
     * @return bool
     */
    public function isGood(Int $user_id, Int $tag_id)
    {
        return (bool)$this->where('user_id', $user_id)->where('tag_id', $tag_id)->first();
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
