<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\GoodTag;
use Illuminate\Support\Facades\DB;

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
        'user_id', 'name', 'profit_type', 'comment'
    ];

    /**
     * 初期登録のバリデーションの条件
     * @var array
     */
    private static $createrules = [
        'name' => ['required', 'string', 'max:255'],
        'profit_type' => ['required', 'integer', 'between:-1,1'],
        'comment' => ['max:255']
    ];

    /**
     * 更新のバリデーションの条件
     * @var array
     */
    private static $updaterules = [
        'name' => ['required', 'string', 'max:255'],
        'profit_type' => ['required', 'integer', 'between:-1,1'],
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
    public function user_idCheck(int $id): bool
    {
        return $this->user_id === $id;
    }

    /**
     * idが一致する'いいね'を削除する
     * @return int
     */
    public function goodTagDeletes()
    {
        return GoodTag::where('tag_id', $this->id)->delete();
    }

    /**
     * 全てのタグをそれに対するいいねの数を取得
     * @return array
     */
    public static function getTagsAll()
    {
        //実行SQL
        $_SQL = '#いいねされているもの
                SELECT
                    tags.id as tag_id,
                    tags.name as tag_name,
                    tags.comment as tag_comment,
                    tags.profit_type as tag_profit_type,
                    users.name as user_name,
                    COUNT(*) as good
                FROM
                    tags,
                    users,
                    good_tags
                WHERE
                    tags.user_id = users.id
                AND tags.id = good_tags.tag_id
                GROUP BY
                    tags.id,
                    tags.name,
                    users.name,
                    tags.comment,
                    tags.profit_type

                UNION

                #いいねされてないもの
                SELECT
                    tags.id as tag_id,
                    tags.name as tag_name,
                    tags.comment as tag_comment,
                    tags.profit_type as tag_profit_type,
                    users.name as user_name,
                    0 as good
                FROM
                    tags,
                    users
                WHERE
                    tags.user_id = users.id
                AND NOT EXISTS(
                        SELECT
                            *
                        FROM
                            good_tags
                        WHERE
                            tags.id = good_tags.tag_id
                    )
                GROUP BY
                    tags.id,
                    tags.name,
                    users.name,
                    tags.comment,
                    tags.profit_type

                #全体のソート
                ORDER BY
                    tag_id;';

                return DB::select($_SQL);
    }
}
