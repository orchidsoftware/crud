<?php

namespace Orchid\Crud\Exceptions\BehaviourChangers;

class RedirectTo extends \Exception
{
    protected string $redirectUrl;

    protected ?string $toastMessage = null;

    protected string $toastLevel = 'info';

    public function __construct(string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public static function forRoute($routeName, $routeParams = [], $absolute = true)
    {
        return new static(
            route($routeName, $routeParams, $absolute)
        );
    }

    public static function forUrl($redirectUrl)
    {
        return new static($redirectUrl);
    }

    public function getToastMessage(): ?string
    {
        return $this->toastMessage;
    }

    public function withToastMessage(?string $toastMessage): self
    {
        $this->toastMessage = $toastMessage;

        return $this;
    }

    public function getToastLevel(): string
    {
        return $this->toastLevel;
    }

    public function withToastLevel(string $toastLevel): self
    {
        $allowedToastLevels = ['info', 'success', 'error', 'warning'];

        if (in_array($toastLevel, $allowedToastLevels)) {
            $this->toastLevel = $toastLevel;
        } else {
            throw new \Exception("{$toastLevel} not allowed");
        }

        return $this;
    }
}
