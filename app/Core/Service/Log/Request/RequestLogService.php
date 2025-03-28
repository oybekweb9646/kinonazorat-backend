<?php

namespace App\Core\Service\Log\Request;

use App\Core\Enums\Action\Action;
use App\Core\Service\Log\LogService;
use App\Models\Log;
use App\Models\Request;

class RequestLogService extends LogService
{
    public function created(Request $request): Log
    {
        return $this->log(
            $request,
            Action::REQUEST_CREATED->value,
            $this->emptyValues($request),
        );
    }

    public function deleted(Request $request): Log
    {
        return $this->log(
            $request,
            Action::REQUEST_DELETED->value,
            $this->emptyValues($request),
        );
    }

    public function scored(Request $request, $attributes): Log
    {
        return $this->log(
            $request,
            Action::REQUEST_SCORED->value,
            $attributes
        );
    }


    public function confirmed(Request $request, array $attributes): void
    {
        $this->log(
            $request,
            Action::REQUEST_CONFIRMED->value,
            $attributes
        );
    }

    /**
     * @param Request $request
     * @param string $actionName
     * @param array $attributes
     * @return Log
     */
    private function log(
        Request $request,
        string  $actionName,
        array   $attributes = [],
    ): Log
    {
        $log = new Log();
        $log->action = $actionName;
        $log->user_ip = request()->ip();
        $log->user_agent = request()->userAgent();
        $log->request_id = $request->id;
        $log->authority_id = $request->authority_id;
        $log->user_id = auth()->user()->id;
        $log->data = json_encode($this->getChanges($request, $attributes));
        $log->save();
        return $log;
    }
}
