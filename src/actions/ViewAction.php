<?php

namespace voskobovich\crud\actions;

use yii\db\ActiveRecord;
use yii\web\Controller;


/**
 * Class ViewAction
 * @package voskobovich\crud\actions
 */
class ViewAction extends BaseAction
{
    /**
     * View name
     * @var string
     */
    public $viewFile = 'view';

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $pk = $this->getPrimaryKey();

        /** @var ActiveRecord $model */
        $model = $this->loadModel($pk);

        /** @var Controller $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model
        ]);
    }
}