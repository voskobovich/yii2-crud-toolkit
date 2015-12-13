<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\admin\forms\IndexFormAbstract;
use Yii;
use yii\base\InvalidConfigException;


/**
 * Class IndexAction
 * @package voskobovich\admin\actions
 */
class IndexAction extends BaseAction
{
    /**
     * View file
     * @var string
     */
    public $viewFile = 'index';

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function run()
    {
        /* @var \yii\base\Model $formClass */
        $modelClass = $this->modelClass;
        if (!$modelClass instanceof IndexFormAbstract) {
            throw new InvalidConfigException('Property "modelClass" must be implemented "voskobovich\admin\forms\IndexFormAbstract"');
        }

        /** @var IndexFormAbstract $model */
        $model = new $modelClass;

        $params = Yii::$app->request->get();
        $dataProvider = $model->search($params);

        /** @var BackendController $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}