<?php

namespace voskobovich\crud\actions;

use voskobovich\crud\forms\IndexFormAbstract;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class IndexAction.
 */
class IndexAction extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    public $viewFile = 'index';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (false === is_subclass_of($this->modelClass, IndexFormAbstract::class)) {
            throw new InvalidConfigException('Property "modelClass" must be implemented ' . IndexFormAbstract::class);
        }

        parent::init();
    }

    /**
     * @throws InvalidConfigException
     * @throws \yii\base\InvalidParamException
     *
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    public function run()
    {
        /** @var IndexFormAbstract $model */
        $model = Yii::createObject($this->modelClass);
        $model->scenario = $this->scenario;

        $params = Yii::$app->request->get();
        $dataProvider = $model->search($params);

        return $this->render([
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
}
