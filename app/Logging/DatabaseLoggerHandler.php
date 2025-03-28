<?php

namespace App\Logging;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class DatabaseLoggerHandler extends AbstractProcessingHandler
{
    public function __construct(
        protected Request $request,
        int|string|Level  $level = Level::Debug,
        bool              $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        try {
            DB::table('app_error_logs')->insert([
                'level'        => $record->level,
                'message'      => $record->message,
                'ip'           => $this->request->ip(),
                'context'      => json_encode($record),
                'user_id'      => Auth::check() ? Auth::id() : null,
                'body_params'  => json_encode($this->request->post()),
                'query_params' => json_encode($this->request->query()),
                'url'          => $this->request->fullUrl(),
                'created_at'   => now(),
                'updated_at'   => now()
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
