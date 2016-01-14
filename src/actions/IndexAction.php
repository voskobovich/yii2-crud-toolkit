<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\controllers\BackendController;
use voskobovich\crud\forms\IndexFormAbstract;
use Yii;
use yii\base\InvalidConfigException;


/**
 * Class IndexAction
 * @package voskobovich\crud\actions
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
        /** @var IndexFormAbstract $model */
        $model = new $this->modelClass;

        if (!$model instanceof IndexFormAbstract) {
            throw new InvalidConfigException('Property "modelClass" must be implemented "voskobovich\crud\forms\IndexFormAbstract"');
        }

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