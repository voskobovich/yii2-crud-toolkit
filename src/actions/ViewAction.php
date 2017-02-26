<?php

namespace voskobovich\crud\actions;

use yii\db\ActiveRecord;
use yii\web\Controller;

/**
 * Class ViewAction.
 */
class ViewAction extends BaseAction
{
    /**
     * View name.
     *
     * @var string
     */
    public $viewFile = 'view';

    /**
     * @throws \yii\web\NotFoundHttpException
     *
     * @return string
     */
    public function run()
    {
        $pk = $this->getPrimaryKey();

        /** @var ActiveRecord $model */
        $model = $this->loadModel($pk);

        /** @var Controller $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model,
        ]);
    }
}
