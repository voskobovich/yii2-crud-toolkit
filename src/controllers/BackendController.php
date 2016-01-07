<?php

namespace voskobovich\admin\controllers;

use Yii;


/**
 * Class Backend
 * @package voskobovich\admin\controllers
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
                'class' => 'voskobovich\admin\actions\IndexAction',
                'modelClass' => $this->modelSearchClass,
            ],
            'create' => [
                'class' => 'voskobovich\admin\actions\CreateAction',
                'modelClass' => $this->modelClass,
                'redirectRoute' => $this->routeAfterCreate
            ],
            'update' => [
                'class' => 'voskobovich\admin\actions\UpdateAction',
                'modelClass' => $this->modelClass,
                'redirectRoute' => $this->routeAfterUpdate
            ],
            'delete' => [
                'class' => 'voskobovich\admin\actions\DeleteAction',
                'modelClass' => $this->modelClass,
                'redirectRoute' => $this->routeAfterDelete
            ],
        ];
    }

}