<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\forms\RelationFormAbstract;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\Controller;


/**
 * Class RelationAction
 * @package voskobovich\crud\actions
 */
class RelationAction extends BaseAction
{
    /**
     * Class to use search relation records
     * @var string
     */
    public $formClass;

    /**
     * View name
     * @var string
     */
    public $viewFile;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->formClass == null) {
            throw new InvalidConfigException('Property "formClass" must be contain form name with namespace.');
        }

        if (!is_subclass_of($this->formClass, RelationFormAbstract::className())) {
            throw new InvalidConfigException('Property "modelClass" must be implemented ' . RelationFormAbstract::className());
        }

        parent::init();
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $pk = $this->getModelPk();

        /** @var ActiveRecord $model */
        $model = $this->findModel($pk);

        /** @var RelationFormAbstract $form */
        $form = new $this->formClass;
        $form->scenario = $this->scenario;

        $params = Yii::$app->request->get();
        $dataProvider = $form->search($model, $params);

        /** @var Controller $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model,
            'form' => $form,
            'dataProvider' => $dataProvider
        ]);
    }
}