<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GoodTag;

class GoodTagController extends Controller
{

    /**
     * Store a newly created resource in storage.
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
        $validationResult = GoodTag::createvalidator($request->all());

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            $param['createResult'] = false;
            $param['error'] = $validationResult->messages();
            return response()->json($param);
        }

        //登録する値
        $createparam = [
            'user_id' => auth()->id(),
            'tag_id' => $request->tag_id
        ];

        $goodtag = new GoodTag();
        //すでにいいねしているか？
        if ($goodtag->isGood($createparam['user_id'], $createparam['tag_id'])) {
            $param['createResult'] = false;
            return response()->json($param);
        }

        //いいね！登録
        GoodTag::create($createparam);

        return response()->json($param);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $param = [
            'deleteResult' => true,
        ];

        $good = GoodTag::find($id);
        //対象の'いいね'は存在するか？ || リクエストしたユーザーと対象の'いいね'のuser_idは一致するか？
        if (!$good || !$good->user_idCheck(auth()->id())) {
            $param['deleteResult'] = false;
            $param['error'] = '取り消しができません';
            return response()->json($param);
        }

        //いいねを取り消し
        $good->delete();

        return response()->json($param);
    }
}
