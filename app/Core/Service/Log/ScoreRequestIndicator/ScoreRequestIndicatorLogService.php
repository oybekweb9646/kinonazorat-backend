<?php

namespace App\Core\Service\Log\ScoreRequestIndicator;

use App\Core\Enums\Action\Action;
use App\Core\Service\Log\LogService;
use App\Models\LinkScoreRequestIndicatorFiles;
use App\Models\Log;
use App\Models\ScoreRequestIndicator;

class ScoreRequestIndicatorLogService extends LogService
{

    public function scoreChanged(ScoreRequestIndicator $request, array $attributes): void
    {
        $this->log(
            $request,
            Action::REQUEST_SCORE_CHANGED->value,
            $attributes
        );
    }

    public function setFile(ScoreRequestIndicator $request, array $attributes): void
    {
        $this->log(
            $request,
            Action::REQUEST_SET_FILE->value,
            $attributes
        );
    }

    public function setLinkFile(LinkScoreRequestIndicatorFiles $request, array $attributes): void
    {
        $this->logLinkFiles(
            $request,
            Action::REQUEST_SET_FILE->value,
            $attributes
        );
    }

    /**
     * @param ScoreRequestIndicator $request
     * @param string $actionName
     * @param array $attributes
     * @return void
     */
    private function log(
        ScoreRequestIndicator $request,
        string                $actionName,
        array                 $attributes = [],
    ): void
    {
        $log = new Log();
        $log->action = $actionName;
        $log->user_ip = request()->ip();
        $log->user_agent = request()->userAgent();
        $log->request_id = $request->request_id;
        $log->score_request_indicator_id = $request->id;
        $log->user_id = auth()->user()->id;
        $log->data = json_encode($this->getChanges($request, $attributes));
        $log->save();

    }

    private function logLinkFiles(
        LinkScoreRequestIndicatorFiles $request,
        string                         $actionName,
        array                          $attributes = [],
    ): void
    {
        $log = new Log();
        $log->action = $actionName;
        $log->user_ip = request()->ip();
        $log->user_agent = request()->userAgent();
        $log->request_id = $request->scoreRequestIndicator->request_id;
        $log->score_request_indicator_id = $request->score_request_indicator_id;
        $log->user_id = auth()->user()->id;
        $log->data = json_encode($this->getLinkChanges($request, $attributes));
        $log->save();

    }
}
