<?php

namespace App\Core\Service\Log;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Repository\User\UserRepository;
use App\Models\File;
use App\Models\LinkScoreRequestIndicatorFiles;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;

abstract class LogService
{
    public static function excludes(): array
    {
        return [
            'created_at',
            'updated_at'
        ];
    }

    public function emptyValues(Model $record): array
    {
        $values = [];

        foreach ($record->getAttributes() as $attribute) {
            $values[$attribute] = null;
        }

        return $values;
    }

    public function getChanges(Model $record, array $attributes): array
    {
        $data = [];
        $userRepo = app(UserRepository::class);
        $excludes = self::excludes();
        foreach ($attributes as $name => $value) {
            if (in_array($name, $excludes)) continue;

            $current = $record->getAttribute($name);
            if ($current !== $value) {

                $data[$name] = match ($name) {
                    'file_id' => [
                        'old_label' => $value ? File::query()->where(['id' => $value])->first()->original_name : null,
                        'old' => $value,
                        'new_label' => $current ? File::query()->where(['id' => $current])->first()->original_name : null,
                        'new' => $current
                    ],
                    'created_by' => [
                        'old' => $value,
                        'new' => $current ? $userRepo->getReturnName($current) : null
                    ],
                    'updated_by' => [
                        'old' => $value ? $userRepo->getReturnName($value) : null,
                        'new' => $current ? $userRepo->getReturnName($current) : null
                    ],
                    default => [
                        'old' => $value,
                        'new' => $current
                    ],
                };
            }
        }

        return $data;
    }

    public function getLinkChanges(Model $record, array $attributes): array
    {
        $data = [];
        $excludes = self::excludes();
        foreach ($attributes as $name => $value) {
            if (in_array($name, $excludes)) continue;

            $current = $record->getAttribute($name);

            $oldFile = LinkScoreRequestIndicatorFiles::query()
                ->where('score_request_indicator_id', $record->getAttribute('score_request_indicator_id'))
                ->orderBy('id', 'desc')
                ->offset(1)
                ->first();

            if (!empty($oldFile)) {
                $oldLabel = $oldFile->file->original_name;
                $oldValue = $oldFile->file_id;
            } else {
                $oldLabel = null;
                $oldValue = null;
            }

            if ($name == 'file_id') {
                $data[$name] = match ($name) {
                    'file_id' => [
                        'old_label' => $oldLabel,
                        'old' => $oldValue,
                        'new_label' => $current ? File::query()->where(['id' => $current])->first()->original_name : null,
                        'new' => $current
                    ],
                };
            }

        }

        return $data;
    }
}
