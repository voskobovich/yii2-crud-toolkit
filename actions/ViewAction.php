<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use Yii;


/**
 * Class ViewAction
 * @package voskobovich\admin\actions
 */
class ViewAction extends BaseAction
{
    /**
     * View file
     * @var string
     */
    public $viewFile = 'view';

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        /** @var BackendController $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model
        ]);
    }
}