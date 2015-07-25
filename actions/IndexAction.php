<?php

namespace voskobovich\adminToolkit\actions;

use voskobovich\adminToolkit\Backend;
use voskobovich\baseToolkit\db\ActiveRecord;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;


/**
 * Class IndexAction
 * @package voskobovich\adminToolkit\actions
 */
class IndexAction extends Action
{
    /**
     * Class to use to locate the supplied data ids
     * @var string
     */
    public $modelClass;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->modelClass == null) {
            throw new InvalidConfigException('Param "modelClass" must be contain model name with namespace.');
        }
    }

    /**
     * @return null
     */
    public function run()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $params = Yii::$app->request->get();
        $dataProvider = $model->search($params);

        /** @var Backend $controller */
        $controller = $this->controller;

        return $controller->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}