<?php

namespace App\Http\Controllers;

use App\Tag;
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
     * @param \App\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
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
        if ($tag && !$tag->user_idCheck(auth()->id())) {
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
     * @param \App\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
