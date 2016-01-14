<?php

namespace voskobovich\crud\controllers;

use Yii;


/**
 * Class Backend
 * @package voskobovich\crud\controllers
 */
class BackendController extends AccessController
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var string
     */
    public $modelSearchClass;

    /**
     * Action routs
     */
    public $routeAfterCreate = 'update:id';
    public $routeAfterUpdate = false;
    public $routeAfterDelete = 'index';

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'voskobovich\crud\actions\IndexAction',
                'modelClass' => $this->modelSearchClass,
            ],
            'create' => [
                'class' => 'voskobovich\crud\actions\CreateAction',
                'modelClass' => $this->modelClass,
                'redirectRoute' => $this->routeAfterCreate
            ],
            'update' => [
                'class' => 'voskobovich\crud\actions\UpdateAction',
                'modelClass' => $this->modelClass,
                'redirectRoute' => $this->routeAfterUpdate
            ],
            'delete' => [
                'class' => 'voskobovich\crud\actions\DeleteAction',
                'modelClass' => $this->modelClass,
                'redirectRoute' => $this->routeAfterDelete
            ],
        ];
    }

}