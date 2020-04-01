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
     * S3にファイル保存
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
