<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\controllers\BackendController;
use voskobovich\base\db\ActiveRecord;
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
    public $redirectUrl = ['update', 'id' => ':id'];

    /**
     * View file
     * @var string
     */
    public $viewFile = 'create';

    /**
     * @return string
     */
    public function run()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $params = Yii::$app->request->post();

        if ($model->load($params)) {

            if (Yii::$app->request->isAjax && !empty($params['ajax'])) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

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