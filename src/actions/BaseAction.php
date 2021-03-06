<?php

namespace voskobovich\crud\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class BaseAction.
 */
abstract class BaseAction extends Action
{
    /**
     * Class to use to locate the supplied data ids.
     *
     * @var string
     */
    public $modelClass;

    /**
     * The name of view file.
     *
     * @var string
     */
    public $viewFile = 'default';

    /**
     * The view additional params.
     *
     * @var array
     */
    public $viewParams = [];

    /**
     * The route which will be redirected after the user action.
     *
     * @var string|array|callable
     */
    public $redirectUrl;

    /**
     * The scenario to be assigned to the model before it is validated and updated.
     *
     * @var string
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * The name of the GET parameter that stores the primary key of the model.
     *
     * @var string
     */
    public $primaryKeyParam = 'id';

    /**
     * Is called when a successful result.
     *
     * @var callable|null;
     */
    public $successCallback;

    /**
     * The flash key for success flash message.
     *
     * @var string
     */
    public $flashSuccessKey = 'success';

    /**
     * Is called when a failed result.
     *
     * @var callable|null;
     */
    public $errorCallback;

    /**
     * The flash key for error flash message.
     *
     * @var string
     */
    public $flashErrorKey = 'error';

    /**
     * This method is called right before `run()` is executed.
     * You may override this method to do preparation work for the action run.
     * If the method returns false, it will cancel the action.
     *
     * @var callable|null
     */
    public $beforeRun;

    /**
     * This method is called right after `run()` is executed.
     * You may override this method to do post-processing work for the action run.
     *
     * @var callable|null
     */
    public $afterRun;

    /**
     * The primary key value of current model.
     *
     * @var int|string|callable|bool
     */
    private $_primaryKey = false;

    /**
     * Previously loaded object of modelClass.
     *
     * @var ActiveRecord|null
     */
    private $_loadedModel = null;

    /**
     * {@inheritdoc}
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (null === $this->modelClass) {
            throw new InvalidConfigException('Property "modelClass" must be contain model class name.');
        }
    }

    /**
     * Set model primary key.
     *
     * @param $value
     */
    public function setPrimaryKey($value)
    {
        $this->_primaryKey = $value;
    }

    /**
     * Get primary key of current handling model.
     *
     * @param bool $throwException
     *
     * @throws \yii\web\BadRequestHttpException
     *
     * @return string
     */
    public function getPrimaryKey($throwException = true)
    {
        if ($this->_primaryKey && is_callable($this->_primaryKey)) {
            $this->_primaryKey = call_user_func($this->_primaryKey, $this);
        }

        if (false === $this->_primaryKey) {
            $this->_primaryKey = Yii::$app->request->get($this->primaryKeyParam, null);
        }

        if (null === $this->_primaryKey && $throwException) {
            throw new BadRequestHttpException('Bad Request');
        }

        return $this->_primaryKey;
    }

    /**
     * Set previously loaded object of modelClass.
     *
     * @param $value
     *
     * @throws \yii\base\InvalidParamException
     */
    public function setLoadedModel($value)
    {
        if ($this->_loadedModel && false === is_a($this->_loadedModel, $this->modelClass)) {
            throw new InvalidParamException(
                'Previously loaded object must be of the same type that is specified "modelClass".'
            );
        }

        $this->_loadedModel = $value;
    }

    /**
     * Get previously loaded object of modelClass.
     *
     * @return null|ActiveRecord
     */
    public function getLoadedModel()
    {
        return $this->_loadedModel;
    }

    /**
     * Finding model by primary key.
     *
     * @param $condition
     * @param bool $throwException
     *
     * @throws \yii\web\NotFoundHttpException
     *
     * @return ActiveRecord|null
     */
    public function findModel($condition, $throwException = true)
    {
        $model = call_user_func(
            [$this->modelClass, 'findOne'],
            $condition
        );

        if (null === $model && $throwException) {
            throw new NotFoundHttpException('Page Not Found');
        }

        return $model;
    }

    /**
     * @var Model $model
     *
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

    /**
     * {@inheritdoc}
     */
    public function beforeRun()
    {
        if (is_callable($this->beforeRun)) {
            return call_user_func($this->beforeRun, $this);
        }

        return parent::beforeRun();
    }

    /**
     * {@inheritdoc}
     */
    public function afterRun()
    {
        if (is_callable($this->afterRun)) {
            call_user_func($this->afterRun, $this);
        }

        parent::afterRun();
    }

    /**
     * Render a view file.
     *
     * @param array $params
     *
     * @throws \yii\base\InvalidParamException
     *
     * @return string|null
     */
    public function render($params = [])
    {
        if (false === $this->viewFile) {
            return null;
        }

        $viewParams = array_merge(
            $this->viewParams,
            $params
        );

        return $this->controller->render(
            $this->viewFile,
            $viewParams
        );
    }

    /**
     * Run success handler by model.
     *
     * @param ActiveRecord $model
     */
    protected function runSuccessHandler($model)
    {
        if (is_callable($this->successCallback)) {
            call_user_func($this->successCallback, $model, $this);
        } elseif (false === empty($this->flashSuccessKey)) {
            Yii::$app->session->setFlash($this->flashSuccessKey);
        }
    }

    /**
     * Run error handler by model.
     *
     * @param ActiveRecord $model
     */
    protected function runErrorHandler($model)
    {
        if (is_callable($this->errorCallback)) {
            call_user_func($this->errorCallback, $model, $this);
        } elseif (false === empty($this->flashErrorKey)) {
            Yii::$app->session->setFlash($this->flashErrorKey);
        }
    }
}
