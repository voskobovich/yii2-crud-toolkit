<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\base\db\ActiveRecord;
use voskobovich\base\helpers\HttpError;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;


/**
 * Class ViewAction
 * @package voskobovich\admin\actions
 */
class ViewAction extends Action
{
    /**
     * Class to use to locate the supplied data ids
     * @var string
     */
    public $modelClass;

    /**
     * View file
     * @var string
     */
    public $viewFile = 'view';

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
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $model = $model::findOne($id);

        if (empty($model)) {
            HttpError::the404();
        }

        /** @var BackendController $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model
        ]);
    }
}