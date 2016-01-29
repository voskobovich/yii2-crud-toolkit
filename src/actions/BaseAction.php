<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\controllers\BackendController;
use voskobovich\base\helpers\HttpError;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;


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
     * Callable function to get for values primary key
     * @var string
     */
    public $modelPk;

    /**
     * The route which will be transferred after the user action
     * @var string
     */
    public $redirectUrl;

    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Name of Primary key
     * @var string
     */
    public $primaryKeyName = 'id';

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
            throw new InvalidConfigException('Property "modelClass" must be contain model name with namespace.');
        }
    }

    /**
     * @var ActiveRecord $model
     * @return bool
     */
    protected function redirect($model)
    {
        if ($this->redirectUrl) {
            if (is_array($this->redirectUrl) && $model) {
                array_walk($this->redirectUrl, function (&$value) use ($model) {
                    if (($pos = strpos($value, ':')) !== false) {
                        $attributeName = substr($value, $pos + 1);
                        $value = ArrayHelper::getValue($model, $attributeName);
                    }
                });
            }

            /** @var BackendController $controller */
            $controller = $this->controller;
            return $controller->redirect($this->redirectUrl);
        }

        return null;
    }

    /**
     * @param bool $throwException
     * @return string
     * @throws \yii\web\BadRequestHttpException
     */
    public function getModelPk($throwException = true)
    {
        if ($this->modelPk == null) {
            $this->modelPk = Yii::$app->request->get($this->primaryKeyName);
        }

        if ($this->modelPk == null && $throwException) {
            HttpError::the400();
        }

        return $this->modelPk;
    }

    /**
     * Find model by Primary key
     * @param $pk
     * @param bool $enableException
     * @return ActiveRecord
     * @throws \yii\web\NotFoundHttpException
     */
    public function findModel($pk, $enableException = true)
    {
        /** @var ActiveRecord $model */
        $model = $this->modelClass;
        $model = $model::findOne($pk);

        if (empty($model) && $enableException) {
            HttpError::the404();
        }

        return $model;
    }
}