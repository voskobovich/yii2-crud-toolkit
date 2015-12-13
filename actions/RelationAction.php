<?php

namespace voskobovich\admin\actions;

use voskobovich\admin\controllers\BackendController;
use voskobovich\admin\forms\RelationFormAbstract;
use Yii;
use yii\base\InvalidConfigException;


/**
 * Class RelationAction
 * @package voskobovich\admin\actions
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
     * @param $id
     * @return string
     * @throws InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        /** @var RelationFormAbstract $form */
        $form = new $this->formClass;
        if (!$form instanceof RelationFormAbstract) {
            throw new InvalidConfigException('Property "formClass" must be implemented "voskobovich\admin\forms\RelationFormAbstract"');
        }

        $params = Yii::$app->request->get();
        $dataProvider = $form->search($model, $params);

        /** @var BackendController $controller */
        $controller = $this->controller;

        return $controller->render($this->viewFile, [
            'model' => $form,
            'dataProvider' => $dataProvider
        ]);
    }
}