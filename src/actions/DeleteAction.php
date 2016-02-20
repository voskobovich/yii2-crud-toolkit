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
     * @var string|array|callable
     */
    public $redirectUrl = ['index'];

    /**
     * @var callable|bool;
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
                    if (is_callable($this->successCallback)) {
                        call_user_func($this->successCallback, $model);
                    } elseif ($this->successCallback !== false) {
                        Yii::$app->session->setFlash('delete:success');
                    }
                } else {
                    if (is_callable($this->errorCallback)) {
                        call_user_func($this->errorCallback, $model);
                    } elseif ($this->errorCallback !== false) {
                        Yii::$app->session->setFlash('delete:error');
                    }
                }
            } catch (Exception $ex) {
                if (is_callable($this->exceptionCallback)) {
                    call_user_func($this->exceptionCallback, $model, $ex);
                } elseif ($this->exceptionCallback !== false) {
                    Yii::$app->session->setFlash('delete:exception');
                }
            }
        }

        return $this->redirect($model);
    }
}