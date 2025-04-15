<?php

namespace App\Core\Service\Log;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Repository\User\UserRepository;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;

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
                        'old_label' => $value ? File::query()->where(['id' => $value])->first()->{LanguageHelper::getFileName()} : null,
                        'old' => $value,
                        'new_label' => $current ? File::query()->where(['id' => $current])->first()->{LanguageHelper::getFileName()}  : null,
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
}
