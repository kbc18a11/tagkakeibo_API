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
            'icon' => 'icon/defaultIcons/default_icon.png',
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
            'updateResult' => true,
            'usreInfo' => null
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

        //対象のユーザーデータを取り出し
        $user = User::find($request->id);

        $s3 = new S3Manager('icon');

        //デフォルトのアイコンじゃなく、既存のユーザーアイコンは存在しているか？
        if ($user->icon !== 'icon/defaultIcons/default_icon.png' &&$s3->isFile($user->icon)){
            //既存のアイコンを削除
            $s3->fileDelete($user->icon);
        }

        //アップロードして、画像の名前を取り出す
        $iconName = $s3->filUpload($request->file('icon'));;


        //ユーザー登録
        $updateparam = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'icon' => $iconName,
        ];
        $user->fill($updateparam)->save();
        User::updated($updateparam);

        $param['usreInfo'] = $updateparam;

        return response()->json($param);
    }
}
