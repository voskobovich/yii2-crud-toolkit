<?php

namespace voskobovich\crud\actions;

use voskobovich\base\helpers\HttpError;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
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
     * The route which will be redirected after the user action
     * @var string|array|callable
     */
    public $redirectUrl;

    /**
     * The scenario to be assigned to the model before it is validated and updated.
     * @var string
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * The name of the GET parameter that stores the primary key of the model
     * @var string
     */
    public $pkName = 'id';

    /**
     * Is called when a successful result
     * @var callable|bool;
     */
    public $successCallback;

    /**
     * Is called when a failed result
     * @var callable|bool;
     */
    public $errorCallback;

    /**
     * The primary key value of current model
     * @var integer|string|callable|boolean
     */
    private $_modelPk = false;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->modelClass == null) {
            throw new InvalidConfigException('Property "modelClass" must be contain model class name.');
        }
    }

    /**
     * Set model primary key
     * @param $value
     */
    public function setModelPk($value)
    {
        $this->_modelPk = $value;
    }

    /**
     * Get primary key of current handling model
     * @param bool $throwException
     * @return string
     * @throws \yii\web\BadRequestHttpException
     */
    public function getModelPk($throwException = true)
    {
        if ($this->_modelPk !== false && is_callable($this->_modelPk)) {
            $this->_modelPk = call_user_func($this->_modelPk, $this);
        }

        if ($this->_modelPk === false) {
            $this->_modelPk = Yii::$app->request->get($this->pkName);
        }

        if ($this->_modelPk == null && $throwException) {
            HttpError::the400();
        }

        return $this->_modelPk;
    }

    /**
     * Finding model by primary key
     * @param $pk
     * @param bool $throwException
     * @return ActiveRecord
     * @throws \yii\web\NotFoundHttpException
     */
    public function findModel($pk, $throwException = true)
    {
        /** @var ActiveRecord $model */
        $model = $this->modelClass;
        $model = $model::findOne($pk);

        if (empty($model) && $throwException) {
            HttpError::the404();
        }

        return $model;
    }

    /**
     * @var Model $model
     * @return Response
     */
    protected function redirect($model)
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

        /** @var Controller $controller */
        $controller = $this->controller;
        return $controller->redirect($this->redirectUrl);
    }
}