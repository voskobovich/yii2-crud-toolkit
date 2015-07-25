<?php

namespace voskobovich\adminToolkit;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * Class Backend
 * @package app\extensions
 */
class Backend extends Controller
{
    /**
     * @var string
     */
    public $layout;

    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var string
     */
    public $role;

    /**
     * Action routs
     */
    public $routeAfterCreate = 'index';
    public $routeAfterUpdate = 'index';
    public $routeAfterDelete = 'index';

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['moder'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'voskobovich\adminToolkit\actions\IndexAction',
                'modelClass' => $this->modelClass,
            ],
            'create' => [
                'class' => 'voskobovich\adminToolkit\actions\CreateAction',
                'modelClass' => $this->modelClass,
                'redirectRoute' => $this->routeAfterCreate
            ],
            'update' => [
                'class' => 'voskobovich\adminToolkit\actions\UpdateAction',
                'modelClass' => $this->modelClass,
                'redirectRoute' => $this->routeAfterUpdate
            ],
            'delete' => [
                'class' => 'voskobovich\adminToolkit\actions\DeleteAction',
                'modelClass' => $this->modelClass,
                'redirectRoute' => $this->routeAfterDelete
            ],
        ];
    }

}