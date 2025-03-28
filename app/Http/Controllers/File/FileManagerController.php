<?php

namespace App\Http\Controllers\File;

use App\Core\Helpers\Responses\Response;
use App\Core\Service\File\interfaces\FileManager;
use App\Http\Requests\File\FileUploadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class FileManagerController extends Controller
{
    private FileManager $fileManagerService;

    public function __construct(
        FileManager $fileManagerService
    )
    {
        $this->fileManagerService = $fileManagerService;
    }

    /**
     * @param FileUploadRequest $fileUploadRequest
     * @return JsonResponse
     */
    public function upload(FileUploadRequest $fileUploadRequest): JsonResponse
    {
        $response = $this->fileManagerService->save($fileUploadRequest);
        $message = $response->isOldFile ? 'old_file_yes' : 'success';
        return Response::success($message, $this->fileManagerService->save($fileUploadRequest));
    }

    public function download(int $id)
    {
        return $this->fileManagerService->download($id);
    }
}
