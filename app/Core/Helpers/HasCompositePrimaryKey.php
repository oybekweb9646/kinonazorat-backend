<?php

namespace App\Core\Helpers;

use Illuminate\Database\Eloquent\Builder;

trait HasCompositePrimaryKey
{
    /**
     * @param $query
     * @return Builder
     */
    protected function setKeysForSaveQuery($query): Builder
    {
        $keys = $this->getKeyName();

        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $key) {
            $query->where($key, '=', $this->getKeyForSaveQuery($key));
        }

        return $query;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    protected function getKeyForSaveQuery(?string $key = null): mixed
    {
        if (is_null($key)) {
            return parent::getKeyForSaveQuery();
        }

        if (isset($this->original[$key])) {
            return $this->original[$key];
        }

        return $this->getAttribute($key);
    }


    /**
     * @param $key
     * @return mixed
     */
    public function getAttribute($key): mixed
    {
        if (is_array($key)) {
            foreach ($key as $item) {
                return parent::getAttribute($item);
            }
        }

        return parent::getAttribute($key);
    }
}
