<?php

declare(strict_types=1);

namespace App\Response;

use LogicException;
use Symfony\Component\HttpFoundation\Response;

class EarlyResponse extends Response
{
    protected $callback = null;

    public function __construct($content = '', $status = 200, $headers = array(), $callback = null)
    {
        if (null !== $callback) {
            $this->setTerminateCallback($callback);
        }
        parent::__construct($content, $status, $headers);
    }

    /**
     * @param $callback
     */
    public function setTerminateCallback($callback): void
    {
        if (!is_callable($callback)) {
            throw new LogicException('The Response callback must be a valid PHP callable.');
        }
        $this->callback = $callback;
    }

    public function send(): Response
    {
        if (function_exists('fastcgi_finish_request') || 'cli' === PHP_SAPI) {
            return parent::send();
        }
        ignore_user_abort(true);
        if (!ob_get_level()) {
            ob_start();
        }
        $this->sendContent();
        static::closeOutputBuffers(1, true);

        $this->headers->set('Content-Length', ob_get_length());
        $this->headers->set('Connection', 'close');
        $this->headers->set('Content-Encoding', 'none');

        $this->sendHeaders();
        static::closeOutputBuffers(0, true);
        flush();
        session_write_close();
        return $this;
    }

    public function callTerminateCallback(): self
    {
        if ($this->callback) {
            call_user_func($this->callback);
        }
        return $this;
    }
}
