<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\base\db\ActiveRecord;
use voskobovich\alert\helpers\AlertHelper;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\Exception;


/**
 * Class DeleteAction
 * @package voskobovich\admin\actions
 */
class DeleteAction extends Action
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

        /** @var BackendController $controller */
        $controller = $this->controller;

        if ($model) {
            try {
                if ($model->delete()) {
                    AlertHelper::success(Yii::t('backend', 'Successfully removed!'));
                } else {
                    AlertHelper::error(Yii::t('backend', 'Error removing!'));
                }
            } catch (Exception $ex) {
                AlertHelper::error(Yii::t('backend', 'Can\'t delete entity. I\'ts in use'));
            }
        }

        if ($this->redirectRoute) {
            $controller->redirect([$this->redirectRoute]);
        }
    }
}