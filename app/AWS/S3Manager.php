<?php


namespace App\AWS;

use App\AWS\AWSManager;
use Illuminate\Support\Facades\Storage;

class S3Manager implements AWSManager
{
    private $folderpath;

    private $s3Disk;

    /***
     * S3Manager constructor.
     * @param string $folderpath
     */
    public function __construct(string $folderpath)
    {
        $this->folderpath = $folderpath;
        $this->s3Disk = Storage::disk('s3');
    }

    /***
     * S3にファイルが存在するのかを検証、存在すればtrue,存在しなければfalse
     * @param string $path ファイルのパス
     * @return bool
     */
    public function isFile(string $path): bool
    {
        //ファイルは存在するか？
        if ($this->s3Disk->exists($path)) return true;

        return false;
    }

    /***
     * S3にファイル保存し、保存したファイルのパスを返す
     * @param $flie
     * @return string
     */
    public function filUpload($flie): string
    {
        //S3にファイルを保存
        $path = $this->s3Disk->putFile($this->folderpath, $flie, 'public');

        return $path;
    }

    /***
     * ファイルを削除
     * @param string $path
     */
    public function fileDelete(string $path): void
    {
        $this->s3Disk->delete($path);
    }

}
