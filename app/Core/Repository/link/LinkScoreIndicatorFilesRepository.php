<?php

namespace App\Core\Repository\link;

use App\Models\LinkScoreRequestIndicatorFiles;

class LinkScoreIndicatorFilesRepository
{
    /**
     * @param LinkScoreRequestIndicatorFiles $link
     * @param array $params
     * @return void
     */
    public function save(LinkScoreRequestIndicatorFiles $link, array $params = []): void
    {
        if (!$link->save($params)) {
            throw new \RuntimeException(__('client.Link File save error'));
        }
    }
}
