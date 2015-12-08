<?php

namespace voskobovich\admin\actions;

use voskobovich\base\db\ActiveRecord;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;


/**
 * Class DeleteAction
 * @package voskobovich\admin\actions
 */
class DeleteAction extends BaseAction
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
    public $redirectRoute = 'index';

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