<?php

namespace voskobovich\crud\actions;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;


/**
 * Class DeleteAction
 * @package voskobovich\crud\actions
 */
class DeleteAction extends BaseAction
{
    /**
     * The route which will be transferred after the user action
     * @var string
     */
    public $redirectUrl = ['index'];

    /**
     * @var callable|null;
     */
    public $exceptionCallback;

    /**
     * @return null
     */
    public function run()
    {
        $pk = $this->getModelPk();

        /** @var ActiveRecord $model */
        $model = $this->findModel($pk, false);
        $model->scenario = $this->scenario;

        if ($model) {
            try {
                if ($model->delete()) {
                    if ($this->successCallback) {
                        call_user_func($this->successCallback, $model);
                    } else {
                        Yii::$app->session->setFlash('delete:success');
                    }
                } else {
                    if ($this->errorCallback) {
                        call_user_func($this->errorCallback, $model);
                    } else {
                        Yii::$app->session->setFlash('delete:error');
                    }
                }
            } catch (Exception $ex) {
                if ($this->exceptionCallback) {
                    call_user_func($this->exceptionCallback, $model, $ex);
                } else {
                    Yii::$app->session->setFlash('delete:exception');
                }
            }
        }

        return $this->redirect($model);
    }
}