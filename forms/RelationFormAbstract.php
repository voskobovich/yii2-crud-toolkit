<?php

namespace voskobovich\admin\forms;

use yii\base\Model;
use yii\db\ActiveRecord;


/**
 * Class RelationFormAbstract
 * @package voskobovich\admin\forms
 */
abstract class RelationFormAbstract extends Model
{
    /**
     * Query building
     * @param ActiveRecord $model
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    abstract public function search($model, $params);
}