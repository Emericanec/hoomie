<?php

declare(strict_types=1);

namespace App\Widget\DataTable;

use Closure;

class ActionsColumn extends Column
{
    public const ACTION_VIEW = '__VIEW__';

    public const ACTION_EDIT = '__EDIT__';

    public const ACTION_DELETE = '__DELETE__';

    private ?Closure $viewAction = null;

    private ?Closure $editAction = null;

    private ?Closure $deleteAction = null;

    private array $actions = [
        self::ACTION_VIEW,
        self::ACTION_EDIT,
        self::ACTION_DELETE,
    ];

    private string $urlPrefix = '';

    public function getClosure(): Closure
    {
        $viewAction = $this->getViewAction();
        $editAction = $this->getEditAction();
        $deleteAction = $this->getDeleteAction();
        $template = $this->getTemplate();

        $search = [self::ACTION_VIEW, self::ACTION_EDIT, self::ACTION_DELETE];

        return function (object $model) use ($viewAction, $editAction, $deleteAction, $template, $search): string {
            $replace = [
                $viewAction->call($model, $model),
                $editAction->call($model, $model),
                $deleteAction->call($model, $model),
            ];

            return str_replace($search, $replace, $template);
        };
    }

    public function getTemplate()
    {
        $actions = $this->getActions();
        $format = implode(' ', $actions);
        $template = <<<HTML
            <div class="dropdown open">
              <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Actions
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                {$format}
              </div>
            </div>
        HTML;
        return $template;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function setActions(array $actions): void
    {
        $this->actions = $actions;
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
            return '<a class="dropdown-item" href="' . $prefix . 'view/' . $model->getId() . '">View</a>';
        };
    }

    public function getEditAction(): Closure
    {
        if (null !== $this->editAction) {
            return $this->editAction;
        }

        $prefix = $this->getUrlPrefix();

        return function (object $model) use ($prefix) : string {
            return '<a class="dropdown-item" href="' . $prefix . 'edit/' . $model->getId() . '">Edit</a>';
        };
    }

    public function getDeleteAction(): Closure
    {
        if (null !== $this->deleteAction) {
            return $this->deleteAction;
        }

        $prefix = $this->getUrlPrefix();

        return function (object $model) use ($prefix) : string {
            return '<a class="dropdown-item text-danger" href="' . $prefix . 'delete/' . $model->getId() . '">Delete</a>';
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