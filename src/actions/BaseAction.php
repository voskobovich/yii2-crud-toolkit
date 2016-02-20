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
use yii\web\Response;


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
     * @var integer
     */
    public $modelPk;

    /**
     * The route which will be transferred after the user action
     * @var string|array|callable
     */
    public $redirectUrl;

    /**
     * The scenario to be assigned to the model before it is validated and updated.
     * @var string
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Name of Primary key
     * @var string
     */
    public $primaryKeyName = 'id';

    /**
     * @var callable|bool;
     */
    public $successCallback;

    /**
     * @var callable|bool;
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
     * @return Response
     */
    protected function redirect(ActiveRecord $model)
    {
        if (is_array($this->redirectUrl)) {
            array_walk($this->redirectUrl, function (&$value) use ($model) {
                if (($pos = strpos($value, ':')) !== false) {
                    $attributeName = substr($value, $pos + 1);
                    $value = ArrayHelper::getValue($model, $attributeName);
                }
            });
        } elseif (is_callable($this->redirectUrl)) {
            $this->redirectUrl = call_user_func($this->redirectUrl, $model);
        }

        /** @var BackendController $controller */
        $controller = $this->controller;
        return $controller->redirect($this->redirectUrl);
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