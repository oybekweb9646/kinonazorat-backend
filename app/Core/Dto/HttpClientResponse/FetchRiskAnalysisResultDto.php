<?php

namespace App\Core\Dto\HttpClientResponse;

class FetchRiskAnalysisResultDto
{
    public bool $success;
    public string $message;
    public int $id;

    public function __construct(array $data)
    {
        $this->success = $data['success'] ?? false;
        $this->message = $data['message'] ?? '';
        $this->id = $data['id'] ?? 0;
    }

}
