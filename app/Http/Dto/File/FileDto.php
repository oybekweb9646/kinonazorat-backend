<?php

namespace App\Http\Dto\File;

class FileDto
{
    public function __construct(
        public readonly string      $name,
        public readonly string      $originalName,
        public readonly string      $mime,
        public readonly string      $path,
        public readonly string      $disk,
        public readonly string      $hash,
        public readonly int         $size,
        public readonly null|string $collection = null
    )
    {
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name'          => $this->name,
            'original_name' => $this->originalName,
            'mime_type'     => $this->mime,
            'path'          => $this->path,
            'disk'          => $this->disk,
            'hash'          => $this->hash,
            'size'          => $this->size,
            'collection'    => $this->collection,
        ];
    }
}
