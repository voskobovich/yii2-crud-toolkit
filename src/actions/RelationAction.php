<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\controllers\BackendController;
use voskobovich\crud\forms\RelationFormAbstract;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;


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
     * View file
     * @var string
     */
    public $viewFile;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->formClass == null) {
            throw new InvalidConfigException('Param "formClass" must be contain form name with namespace.');
        }
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
        if (!$form instanceof RelationFormAbstract) {
            throw new InvalidConfigException('Property "formClass" must be implemented "voskobovich\crud\forms\RelationFormAbstract"');
        }

        $params = Yii::$app->request->get();
        $dataProvider = $form->search($model, $params);

        /** @var BackendController $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $model,
            'form' => $form,
            'dataProvider' => $dataProvider
        ]);
    }
}