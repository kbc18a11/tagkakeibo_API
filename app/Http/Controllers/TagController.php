<?php

namespace App\Http\Controllers;

use App\Tag;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $keyword = $request->keyword;
        //検索キーワードは存在するか？
        if ($keyword){
            return response()->json(tag::getKeywordSearch($keyword));
        }
        //検索キーワードが存在しない場合
        return response()->json(tag::getTagsAll());
    }

    /**
     * タグの新規登録
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $param = [
            'createResult' => true,
        ];
        //バリデーションの検証
        $validationResult = Tag::createvalidator($request->all());

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            $param['createResult'] = false;
            $param['error'] = $validationResult->messages();
            return response()->json($param);
        }

        //タグ登録
        $createparam = [
            'user_id' => auth()->id(),
            'name' => $request->name,
            'profit_type' => $request->profit_type,
            'comment' => $request->comment
        ];
        Tag::create($createparam);


        return response()->json($param);
    }

    /**
     * Display the specified resource.
     *
     * @param int $user_id
     * @return \Illuminate\Http\Response
     */
    public function show(int $user_id)
    {
        //
        //対象のユーザーは存在しないのか？
        if (!\App\User::find($user_id)){
            return response()->json(null);
        }

        return response()->json(tag::getUserTag($user_id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $param = [
            'updateResult' => true,
        ];
        //バリデーションの検証
        $validationResult = Tag::updatevalidator($request->all());

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            $param['updateResult'] = false;
            $param['error'] = $validationResult->messages();
            return response()->json($param);
        }

        $tag = Tag::find($id);
        //指定したidのタグは存在するか？ && 指定したタグのuser_idとユーザーのidが一致しないか？
        if (!$tag || !$tag->user_idCheck(auth()->id())) {
            $param['updateResult'] = false;
            $param['error'] = '更新できないタグを更新しようとしています';
            return response()->json($param);
        }

        //タグ更新
        $updateparam = [
            'user_id' => auth()->id(),
            'name' => $request->name,
            'profit_type' => $request->profit_type,
            'comment' => $request->comment
        ];
        $tag->fill($updateparam)->save();

        return response()->json($param);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
        $param = [
            'deleteResult' => true,
        ];

        $tag = Tag::find($id);
        //指定したidのタグは存在するか？ || 指定したタグのuser_idとユーザーのidが一致しないか？
        if (!$tag || !$tag->user_idCheck(auth()->id())) {
            $param['deleteResult'] = false;
            $param['error'] = '更新できないタグを更新しようとしています';
            return response()->json($param);
        }

        //削除対象のタグの'いいね'を全て削除する
        $tag->goodTagDeletes();

        //タグの削除
        $tag->delete();

        return $param;
    }
}
