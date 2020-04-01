<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\AWS\S3Manager;

class UsersController extends Controller
{
    //
    /**
     * user create
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        # code...
        $param = [
            'createResult' => true,
        ];

        //バリデーションの検証
        $validationResult =  User::createvalidator($request->all());

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            $param['createResult'] = false;
            $param['error'] = $validationResult->messages();
            return response()->json($param);
        }

        //ユーザー登録
        $createparam = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'icon' => 'default_icon.png',
        ];

        User::create($createparam);

        return response()->json($param);
    }

    /**
     * ユーザー情報の更新
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        # code...
        $param = [
            'createResult' => true,
        ];

        //バリデーションの検証
        $validationResult =  User::updatevalidator($request->all());

        //バリデーションの結果が駄目か？
        if ($validationResult->fails()) {
            # code...
            $param['createResult'] = false;
            $param['error'] = $validationResult->messages();
            return response()->json($param);
        }

        //アイコン画像の保存を開始
        $s3 = new S3Manager('icon');

        //画像の名前を取り出す
        $iconName = $s3->filUpload($request->file('icon'));;

        //ユーザー登録
        $updateparam = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'icon' => $iconName,
        ];
        User::updated($updateparam);



        return response()->json($param);
    }
}
