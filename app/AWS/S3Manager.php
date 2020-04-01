<?php


namespace App\AWS;

use App\AWS\AWSManager;
use Illuminate\Support\Facades\Storage;

class S3Manager implements AWSManager
{
    private $folderpath;

    /***
     * S3Manager constructor.
     * @param string $folderpath
     */
    public function __construct(string $folderpath)
    {
        $this->folderpath = $folderpath;
    }

    /***
     * S3にファイルが存在するのかを検証、存在すればtrue,存在しなければfalse
     * @param string $path ファイルのパス
     * @return bool
     */
    public function isFile(string $path):bool
    {
        //ファイルは存在するか？
        if (Storage::disk('s3')->exists($path))return true;

        return false;
    }

    /***
     * S3にファイル保存し、保存したファイルのパスを返す
     * @param $flie
     * @return string
     */
    public function filUpload($flie):string
    {
        //S3にファイルを保存
        $path = Storage::disk('s3')->putFile($this->folderpath, $flie, 'public');

        //S3の保存したもののパスを取得
        $file_path = Storage::disk('s3')->url($path);

        return $file_path;
    }
}
