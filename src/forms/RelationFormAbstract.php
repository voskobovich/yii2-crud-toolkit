<?php

namespace voskobovich\crud\forms;

use voskobovich\base\interfaces\ModelInterface;
use voskobovich\base\traits\ModelTrait;
use yii\base\Model;
use yii\db\ActiveRecord;


/**
 * Class RelationFormAbstract
 * @package voskobovich\crud\forms
 */
abstract class RelationFormAbstract extends Model implements ModelInterface
{
    use ModelTrait;

    /**
     * Query building
     * @param ActiveRecord $model
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    abstract public function search($model, $params);
}