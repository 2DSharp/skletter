<?php

namespace Skletter\Component\Core;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface Controller
 * A controller is responsible for handling an incoming request and updating the model layer.
 * Delegate the specific functionality to the private methods.
 */
interface Controller
{
    public function handleRequest(Request $request, string $method);
}