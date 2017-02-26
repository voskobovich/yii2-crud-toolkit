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
     * The route which will be redirected after the user action
     * @var string|array|callable
     */
    public $redirectUrl = ['index'];

    /**
     * A callback which defines the logic of the removal of the object
     * @var callable;
     */
    public $handler;

    /**
     * Is called when a throw exception
     * @var callable|bool;
     */
    public $exceptionCallback;

    /**
     * @return null
     */
    public function run()
    {
        $pk = $this->getPrimaryKey();

        /** @var ActiveRecord $model */
        $model = $this->loadModel($pk, false);

        if (!empty($model)) {
            $model->scenario = $this->scenario;
            try {
                if (is_callable($this->handler)) {
                    $result = call_user_func($this->handler, $model, $this);
                } else {
                    $result = $model->delete();
                }

                if ($result) {
                    if (is_callable($this->successCallback)) {
                        call_user_func($this->successCallback, $model, $this);
                    } elseif ($this->successCallback !== false) {
                        Yii::$app->session->setFlash('delete:success');
                    }
                } else {
                    if (is_callable($this->errorCallback)) {
                        call_user_func($this->errorCallback, $model, $this);
                    } elseif ($this->errorCallback !== false) {
                        Yii::$app->session->setFlash('delete:error');
                    }
                }
            } catch (Exception $ex) {
                if (is_callable($this->exceptionCallback)) {
                    call_user_func($this->exceptionCallback, $model, $this, $ex);
                } elseif ($this->exceptionCallback !== false) {
                    Yii::$app->session->setFlash('delete:exception');
                }
            }
        }

        return $this->redirect($model);
    }
}