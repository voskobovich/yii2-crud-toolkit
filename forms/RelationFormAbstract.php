<?php

namespace voskobovich\admin\forms;

use yii\base\Model;


/**
 * Class RelationFormAbstract
 * @package voskobovich\admin\forms
 */
abstract class RelationFormAbstract extends Model
{
    /**
     * Model object
     * @var Model
     */
    public $model;

    /**
     * Query building
     * @param $params
     * @return \yii\data\ActiveDataProvider
     */
    abstract public function search($params);
}