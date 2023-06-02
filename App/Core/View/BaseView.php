<?php

namespace App\Core\View;

abstract class BaseView
{
    protected ?string $layout = null;

    protected ?string $view = null;

    protected array $arguments = [];

    public function __get($name)
    {
        return $this->getArgument($name);
    }

    public function __set($name, $value)
    {
        $this->setArgument($name, $value);
    }

    public function getLayout(): string
    {
        return $this->layout;
    }

    public function setLayout(string $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

    public function setArgument(string $key, $value) : self
    {
        $this->arguments[$key] = $value;

        return $this;
    }

    public function getArgument(string $key, $default = null)
    {
        return $this->arguments[$key] ?? $default;
    }

    public function setView(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function renderContent()
    {
        return require_once ROOT_DIR . '/views/' . $this->view . '.php';
    }

    public function renderView(array $arguments = [])
    {
        foreach ($arguments as $key => $value) {
            $this->setArgument($key, $value);
        }

        return require_once ROOT_DIR . '/views/' . $this->layout . '.php';
    }
}