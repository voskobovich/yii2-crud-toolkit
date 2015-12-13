<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\base\db\ActiveRecord;
use voskobovich\base\helpers\HttpError;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;


/**
 * Class BaseAction
 * @package voskobovich\admin\actions
 */
abstract class BaseAction extends Action
{
    /**
     * Class to use to locate the supplied data ids
     * @var string
     */
    public $modelClass;

    /**
     * The route which will be transferred after the user action
     * @var string
     */
    public $redirectRoute;

    /**
     * @var callable|null;
     */
    public $successCallback;

    /**
     * @var callable|null;
     */
    public $errorCallback;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->modelClass == null) {
            throw new InvalidConfigException('Param "modelClass" must be contain model name with namespace.');
        }
    }

    /**
     * @var ActiveRecord $model
     */
    protected function redirect($model)
    {
        if ($this->redirectRoute) {
            if ($model && ($pos = strpos($this->redirectRoute, ':')) !== false) {
                $route = substr($this->redirectRoute, 0, $pos);
                $params = [$route];

                $attributeName = substr($this->redirectRoute, $pos + 1);
                if ($attributeName && $model->hasAttribute($attributeName)) {
                    $params[$attributeName] = $model->getAttribute($attributeName);
                }
            } else {
                $params = [$this->redirectRoute];
            }

            /** @var BackendController $controller */
            $controller = $this->controller;
            $controller->redirect($params);
        }
    }

    /**
     * Find model by Primary key
     * @param $id
     * @return ActiveRecord
     * @throws \yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $model = $model::findOne($id);

        if (empty($model)) {
            HttpError::the404();
        }

        return $model;
    }
}