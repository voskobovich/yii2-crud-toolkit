<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\forms\RelationFormAbstract;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Class RelationAction.
 */
class RelationAction extends BaseAction
{
    /**
     * Class to use search relation records.
     *
     * @var string
     */
    public $formClass;

    /**
     * {@inheritdoc}
     */
    public $viewFile;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (null === $this->formClass) {
            throw new InvalidConfigException('Property "formClass" must be contain form name with namespace.');
        }

        if (!is_subclass_of($this->formClass, RelationFormAbstract::class)) {
            throw new InvalidConfigException('Property "formClass" must be implemented ' . RelationFormAbstract::class);
        }

        parent::init();
    }

    /**
     * @throws InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\base\InvalidParamException
     * @throws \yii\web\BadRequestHttpException
     *
     * @return string
     * @throws \yii\base\InvalidParamException
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        $model = $this->getLoadedModel();

        if (null === $model) {
            $pk = $this->getPrimaryKey();

            /** @var ActiveRecord $model */
            $model = $this->findModel($pk);
        }

        /** @var RelationFormAbstract $form */
        $form = Yii::createObject($this->formClass);
        $form->scenario = $this->scenario;

        $params = Yii::$app->request->get();
        $dataProvider = $form->search($model, $params);

        return $this->render([
            'model' => $model,
            'form' => $form,
            'dataProvider' => $dataProvider,
        ]);
    }
}
