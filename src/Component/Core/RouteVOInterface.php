<?php

namespace Skletter\Component\Core;

/**
 * Interface RouteVOInterface
 * @package Greentea\Component
 *
 * A Route Value Object should be able to return the following:
 *
 * - Controller (To update the model layer using services)
 * - View (To read the model layer using services and render it for the user)
 * - Method (The method to be called based on the request type)
 *
 * Any implementation should use immutable value objects to extract only route information
 */
interface RouteVOInterface
{
    public function resolveController(): ?string;

    public function resolveView(): ?string;

    public function resolveControllerMethod(): string;

    public function resolveViewMethod(): string;
}