<?php

namespace voskobovich\crud\controllers;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Class AccessController.
 */
class AccessController extends Controller
{
    /**
     * Allow access for roles.
     *
     * @var array
     */
    public $allowRoles = ['admin'];

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    'default' => [
                        'allow' => true,
                        'roles' => $this->allowRoles,
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
}
