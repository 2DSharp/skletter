<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Component;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Skletter\View\ErrorPages;
use Symfony\Component\HttpFoundation\Request;

class FallbackExceptionHandler
{
    /**
     * @var ErrorPages $errorPages
     */
    private $errorPages;
    /**
     * @var array $logConfig
     */
    private $logConfig = [];

    public function __construct(ErrorPages $errorPages, array $logConfig)
    {
        $this->errorPages = $errorPages;
        $this->logConfig = $logConfig;
    }

    /**
     * Store an exception into a log file and/or DB/ELK and/or Syslog
     *
     * @param  \Throwable $exception
     * @param  Request $request
     * @throws \Throwable
     */
    protected function storeToLog(\Throwable $exception, Request $request): void
    {
        $log = new Logger('Unhandled Exception');
        $log->pushHandler(new StreamHandler($this->logConfig['LOG_FILE'], Logger::CRITICAL));
        // TODO: Push a handler to DB logs
        $log->critical(
            $exception->getMessage(),
            array(
                'Stack Trace' => $exception->getTraceAsString(),
                'Request path' => $request->getPathInfo()
            )
        );
    }

    /**
     * The file/DB logger failed, alert the sysadmins!
     *
     * @param \Throwable $exception
     * @param Request $request
     */
    protected function sendToEmail(\Throwable $exception, Request $request)
    {
        // For debugging purposes
        echo $exception->getMessage();
        $log = new Logger('Logger Failure');
        //$log->pushHandler(new \Monolog\Handler\ErrorLogHandler('Error!', Logger::ALERT));

        $log->alert(
            $exception->getMessage(),
            array(
                'Stack Trace' => $exception->getTraceAsString(),
                'Request path' => $request->getPathInfo()
            )
        );
    }

    /**
     * Handle an exception
     *
     * @param \Throwable $exception the exception that'll be handled
     * @param Request $request
     */
    public function handle(\Throwable $exception, Request $request)
    {
        try {
            $this->storeToLog($exception, $request);
        } catch (\Throwable $exception) {
            // Massive failure, storing to log has file access problems
            // Send email to the sysadmin
            $this->sendToEmail($exception, $request);
        } finally {
            $this->errorPages->internalError($request)->send();
        }
    }
}

