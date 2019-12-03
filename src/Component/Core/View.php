<?php

namespace Skletter\Component\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface View
 *
 * A View creates a response depending on the request and returns it.
 * Delegate the response creation to a private method.
 */
interface View
{
    /**
     * @param Request $request
     * @param string $method
     * @param null $event
     * @return Response
     */
    public function createResponse(Request $request, string $method, $event = null): Response;
}