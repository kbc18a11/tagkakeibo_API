<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use App\GoodTag;

class GoodTagController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
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

        //いいね！登録
        $createparam = [
            'user_id' => auth()->id(),
            'tag_id' => $request->tag_id
        ];
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
    }
}
