<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\base\db\ActiveRecord;
use Yii;
use yii\base\InvalidConfigException;


/**
 * Class CreateAction
 * @package voskobovich\admin\actions
 */
class CreateAction extends BaseAction
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
    public $redirectRoute = 'update:id';

    /**
     * View file
     * @var string
     */
    public $viewFile = 'create';

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
     * @return string
     */
    public function run()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $params = Yii::$app->request->post();

        /** @var BackendController $controller */
        $controller = $this->controller;

        if ($model->load($params)) {
            if ($model->save()) {
                if ($this->successCallback) {
                    call_user_func($this->successCallback, $model);
                } else {
                    Yii::$app->session->setFlash('create:success');
                }
                $this->redirect($model);
            } else {
                if ($this->errorCallback) {
                    call_user_func($this->errorCallback, $model);
                } else {
                    Yii::$app->session->setFlash('create:error');
                }
            }
        }

        return $controller->render($this->viewFile, [
            'model' => $model
        ]);
    }
}