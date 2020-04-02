<?php


namespace App\module;

use Intervention\Image\Facades\Image;

class ImageOperation implements moduleinterface
{
    public function __construct()
    {
    }

    /***
     * 画像をリサイズする
     * @param $beforeImg
     * @param array $size
     */
    public function filUpload($beforeImg,array $size)
    {
        //S3にファイルを保存
        Image::make($beforeImg)->resize($size[0], $size[1]);

        //return $path;
    }
}
