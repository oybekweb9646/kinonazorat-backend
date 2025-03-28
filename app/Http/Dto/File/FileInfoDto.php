<?php

namespace App\Http\Dto\File;

class FileInfoDto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly bool   $isOldFile = false
    )
    {
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'isOldFile' => $this->isOldFile
        ];
    }
}
