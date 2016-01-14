<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\controllers\BackendController;
use voskobovich\base\db\ActiveRecord;
use voskobovich\base\helpers\HttpError;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;


/**
 * Class BaseAction
 * @package voskobovich\crud\actions
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
    public $redirectUrl;

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
        if ($this->redirectUrl) {
            if (is_array($this->redirectUrl) && $model) {
                array_walk($this->redirectUrl, function (&$value) use ($model) {
                    if (($pos = strpos($value, ':')) !== false) {
                        $attributeName = substr($value, $pos + 1);
                        $value = $model->getAttribute($attributeName);
                    }
                });
            }

            /** @var BackendController $controller */
            $controller = $this->controller;
            $controller->redirect($this->redirectUrl);
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