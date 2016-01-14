<?php

namespace voskobovich\admin\actions;

use voskobovich\base\db\ActiveRecord;
use Yii;
use yii\db\Exception;


/**
 * Class DeleteAction
 * @package voskobovich\admin\actions
 */
class DeleteAction extends BaseAction
{
    /**
     * The route which will be transferred after the user action
     * @var string
     */
    public $redirectUrl = ['index'];

    /**
     * @param $id
     * @return null
     */
    public function run($id)
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $model = $model::findOne($id);

        if ($model) {
            try {
                if ($model->delete()) {
                    if ($this->successCallback) {
                        call_user_func($this->successCallback, $model);
                    } else {
                        Yii::$app->session->setFlash('delete:success');
                    }
                    $this->redirect($model);
                } else {
                    if ($this->errorCallback) {
                        call_user_func($this->errorCallback, $model);
                    } else {
                        Yii::$app->session->setFlash('delete:error');
                    }
                }
            } catch (Exception $ex) {
                if ($this->errorCallback) {
                    call_user_func($this->errorCallback, $model);
                } else {
                    Yii::$app->session->setFlash('delete:exception');
                }
            }
        }

        $this->redirect($model);
    }
}