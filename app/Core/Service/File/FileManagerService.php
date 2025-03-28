<?php

namespace App\Core\Service\File;

use App\Core\Service\File\interfaces\FileManager;
use App\Http\Dto\File\FileDto;
use App\Http\Dto\File\FileInfoDto;
use App\Http\Requests\File\FileUploadRequest;
use App\Models\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileManagerService implements FileManager
{
    private FileUploadRequest $fileUploadRequest;
    private string $path;
    private string $disk;

    public function __construct(
        string $path,
        string $disk
    )
    {
        $this->path = $path;
        $this->disk = $disk;
    }

    public function upload(): FileDto|FileInfoDto
    {

        $file = $this->fileUploadRequest->file('file');
        $name = $file->hashName();
        $hash_file = md5_file($file->getRealPath());
        if (!empty($fileInfo = $this->check($hash_file))) {
            return new FileInfoDto($fileInfo->id, $fileInfo->original_name, true);
        }
        if (!Storage::put($this->path, $file)) {
            throw new \DomainException("File don't upload");
        }

        return new FileDto(
            $name,
            $file->getClientOriginalName(),
            $file->getClientMimeType(),
            "/storage/" . $this->path,
            $this->disk,
            $hash_file,
            $file->getSize(),
            $this->fileUploadRequest->get('collection')
        );
    }

    /**
     * @param FileUploadRequest $fileUploadRequest
     * @return FileInfoDto
     */
    public function save(FileUploadRequest $fileUploadRequest): FileInfoDto
    {

        $this->fileUploadRequest = $fileUploadRequest;
        $fileDto = $this->upload();

        if ($fileDto instanceof FileInfoDto) {
            return $fileDto;
        }

        $file = new File();
        $file->fill($fileDto->attributes());

        if (!$file->save()) {
            throw new \DomainException('File save error');
        }

        return new FileInfoDto(
            $file->id,
            $file->original_name
        );
    }

    /**
     * @param string $hash
     * @return File|Builder|null
     */
    final protected function check(string $hash): File|null|Builder
    {
        return File::query()
            ->where('hash', '=', $hash)
            ->first();
    }

    public function download(int $file_id): StreamedResponse
    {
        /** @var File $file */
        $file = File::query()->find($file_id);

        $disk = Storage::disk(config('app.file.uploads'));
        $filePath = config('app.file.path') . '/' . $file->name;

        return $disk->download($filePath, $file->name);
    }
}
