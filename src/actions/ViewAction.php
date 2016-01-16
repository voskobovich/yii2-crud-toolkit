<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\controllers\BackendController;
use Yii;
use yii\db\ActiveRecord;


/**
 * Class ViewAction
 * @package voskobovich\crud\actions
 */
class ViewAction extends BaseAction
{
    /**
     * View file
     * @var string
     */
    public $viewFile = 'view';

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $pk = $this->getModelPk();

        /** @var ActiveRecord $model */
        $model = $this->findModel($pk);

        /** @var BackendController $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model
        ]);
    }
}