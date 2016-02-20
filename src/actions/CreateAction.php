<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\controllers\BackendController;
use yii\db\ActiveRecord;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;


/**
 * Class CreateAction
 * @package voskobovich\crud\actions
 */
class CreateAction extends BaseAction
{
    /**
     * The route which will be transferred after the user action
     * @var string
     */
    public $redirectUrl = ['update', 'id' => ':primaryKey'];

    /**
     * View file
     * @var string
     */
    public $viewFile = 'create';

    /**
     * @var bool
     */
    public $enableAjaxValidation = true;

    /**
     * @return string
     */
    public function run()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $model->scenario = $this->scenario;

        $params = Yii::$app->getRequest()->getBodyParams();
        if ($model->load($params)) {
            if ($this->enableAjaxValidation && Yii::$app->request->isAjax && !empty($params['ajax'])) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->save()) {
                if (is_callable($this->successCallback)) {
                    call_user_func($this->successCallback, $model);
                } elseif ($this->successCallback !== false) {
                    Yii::$app->session->setFlash('create:success');
                }

                if ($this->redirectUrl) {
                    return $this->redirect($model);
                }
            } else {
                if (is_callable($this->errorCallback)) {
                    call_user_func($this->errorCallback, $model);
                } elseif ($this->errorCallback !== false) {
                    Yii::$app->session->setFlash('create:error');
                }
            }
        }

        if (!$this->viewFile) {
            return null;
        }

        /** @var BackendController $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model
        ]);
    }
}