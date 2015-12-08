<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\alert\helpers\AlertHelper;
use voskobovich\base\db\ActiveRecord;
use voskobovich\base\helpers\HttpError;
use Yii;
use yii\base\InvalidConfigException;


/**
 * Class UpdateAction
 * @package voskobovich\admin\actions
 */
class UpdateAction extends BaseAction
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
    public $redirectRoute = 'update:id';

    /**
     * View file
     * @var string
     */
    public $viewFile = 'update';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->modelClass == null) {
            throw new InvalidConfigException('Param "modelClass" must be contain model name with namespace.');
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass;
        $model = $model::findOne($id);

        if (empty($model)) {
            HttpError::the404();
        }

        $params = Yii::$app->request->post();

        /** @var BackendController $controller */
        $controller = $this->controller;

        if ($model->load($params)) {
            if ($model->save()) {
                AlertHelper::success(Yii::t('backend', 'Saved successfully!'));
                $this->redirect($model);
            } else {
                AlertHelper::error(Yii::t('backend', 'Error saving!'));
            }
        }

        return $controller->render($this->viewFile, [
            'model' => $model
        ]);
    }
}