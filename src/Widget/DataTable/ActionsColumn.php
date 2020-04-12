<?php

declare(strict_types=1);

namespace App\Widget\DataTable;

use Closure;

class ActionsColumn extends Column
{
    private ?Closure $viewAction = null;

    private ?Closure $editAction = null;

    private ?Closure $deleteAction = null;

    private string $format = 'view edit delete';

    private string $urlPrefix = '';

    public function getClosure(): Closure
    {
        $viewAction = $this->getViewAction();
        $editAction = $this->getEditAction();
        $deleteAction = $this->getDeleteAction();
        $format = $this->getFormat();

        return function (object $model) use ($viewAction, $editAction, $deleteAction, $format): string {
            $search = ['view', 'edit', 'delete'];
            $replace = [
                $viewAction->call($model, $model),
                $editAction->call($model, $model),
                $deleteAction->call($model, $model),
            ];

            return str_replace($search, $replace, $format);
        };
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function getUrlPrefix(): string
    {
        return $this->urlPrefix;
    }

    public function setUrlPrefix(string $prefix): void
    {
        $this->urlPrefix = $prefix;
    }

    public function getViewAction(): Closure
    {
        if (null !== $this->viewAction) {
            return $this->viewAction;
        }

        $prefix = $this->getUrlPrefix();

        return function (object $model) use ($prefix) : string {
            return '<a href="' . $prefix . 'view/' . $model->getId() . '"><i class="nav-icon fas fa-eye"></i></a>';
        };
    }

    public function getEditAction(): Closure
    {
        if (null !== $this->editAction) {
            return $this->editAction;
        }

        $prefix = $this->getUrlPrefix();

        return function (object $model) use ($prefix) : string {
            return '<a href="' . $prefix . 'edit/' . $model->getId() . '"><i class="nav-icon fas fa-edit"></i></a>';
        };
    }

    public function getDeleteAction(): Closure
    {
        if (null !== $this->deleteAction) {
            return $this->deleteAction;
        }

        $prefix = $this->getUrlPrefix();

        return function (object $model) use ($prefix) : string {
            return '<a href="' . $prefix . 'delete/' . $model->getId() . '"><i class="nav-icon fas fa-trash"></i></a>';
        };
    }

    public function setViewAction(Closure $closure): void
    {
        $this->viewAction = $closure;
    }

    public function setEditAction(Closure $closure): void
    {
        $this->editAction = $closure;
    }

    public function setDeleteAction(Closure $closure): void
    {
        $this->deleteAction = $closure;
    }
}