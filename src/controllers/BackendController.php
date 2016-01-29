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
    public $urlAfterCreate = ['update', 'id' => ':primaryKey'];
    public $urlAfterUpdate = ['update', 'id' => ':primaryKey'];
    public $urlAfterDelete = ['index'];

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
                'redirectUrl' => $this->urlAfterCreate
            ],
            'update' => [
                'class' => 'voskobovich\crud\actions\UpdateAction',
                'modelClass' => $this->modelClass,
                'redirectUrl' => $this->urlAfterUpdate
            ],
            'delete' => [
                'class' => 'voskobovich\crud\actions\DeleteAction',
                'modelClass' => $this->modelClass,
                'redirectUrl' => $this->urlAfterDelete
            ],
        ];
    }
}