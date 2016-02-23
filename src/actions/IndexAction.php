<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\forms\IndexFormAbstract;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;


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
     * @inheritdoc
     */
    public function init()
    {
        if (!is_subclass_of($this->modelClass, IndexFormAbstract::className())) {
            throw new InvalidConfigException('Property "modelClass" must be implemented ' . IndexFormAbstract::className());
        }

        parent::init();
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function run()
    {
        /** @var IndexFormAbstract $model */
        $model = new $this->modelClass;
        $model->scenario = $this->scenario;

        $params = Yii::$app->request->get();
        $dataProvider = $model->search($params);

        /** @var Controller $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}