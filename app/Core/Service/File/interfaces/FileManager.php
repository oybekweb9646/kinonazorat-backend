<?php

namespace App\Core\Service\File\interfaces;


use App\Http\Requests\File\FileUploadRequest;

interface FileManager
{
    public function upload();

    public function download(int $file_id);
    public function save(FileUploadRequest $fileUploadRequest);
}
