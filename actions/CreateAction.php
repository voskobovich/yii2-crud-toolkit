<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\base\db\ActiveRecord;
use voskobovich\alert\helpers\AlertHelper;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;


/**
 * Class CreateAction
 * @package voskobovich\admin\actions
 */
class CreateAction extends Action
{
    /**
     * Class to use to locate the supplied data ids
     * @var string
     */
    public $modelClass;

    /**
     * The route which will be transferred after the user action
     * @var string
     */
    public $redirectRoute = 'index';

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
     * @return string
     */
    public function run()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $params = Yii::$app->request->post();

        /** @var BackendController $controller */
        $controller = $this->controller;

        if ($model->load($params)) {
            if ($model->save()) {
                AlertHelper::success(Yii::t('backend', 'Saved successfully!'));
                if ($this->redirectRoute) {
                    $controller->redirect([$this->redirectRoute]);
                }
            } else {
                AlertHelper::error(Yii::t('backend', 'Error saving!'));
            }
        }

        return $controller->render('create', [
            'model' => $model
        ]);
    }
}