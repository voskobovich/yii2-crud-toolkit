<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\base\db\ActiveRecord;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;


/**
 * Class IndexAction
 * @package voskobovich\admin\actions
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

        /** @var BackendController $controller */
        $controller = $this->controller;

        return $controller->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}