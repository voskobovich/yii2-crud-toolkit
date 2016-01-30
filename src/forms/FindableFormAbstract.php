<?php

namespace voskobovich\crud\forms;

use voskobovich\base\traits\ModelTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;


/**
 * Class FindableFormAbstract
 * @package voskobovich\crud\forms
 *
 * @property ActiveRecord $source
 */
abstract class FindableFormAbstract extends Model
{
    use ModelTrait;

    /**
     * Editable model class name
     * @var string
     */
    public static $modelClass;

    /**
     * Default attribute for print error
     * @var string
     */
    public $modelDefaultAttribute;

    /**
     * Default scenario for editable model
     * @var string
     */
    public $modelScenario = ActiveRecord::SCENARIO_DEFAULT;

    /**
     * @var ActiveRecord
     */
    protected $_sourceModel;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!static::$modelClass) {
            throw new InvalidConfigException('Property "modelClass" can not be empty.');
        }

        if (!is_subclass_of(static::$modelClass, ActiveRecord::className())) {
            throw new InvalidConfigException('Property "modelClass" must be implemented ' . ActiveRecord::className());
        }

        if (!$this->modelDefaultAttribute) {
            throw new InvalidConfigException('Property "modelDefaultAttribute" can not be empty.');
        }

        $this->_sourceModel = new static::$modelClass();

        parent::init();
    }

    /**
     * @param $id
     * @return null|ActiveRecord
     */
    public static function findOne($id)
    {
        /** @var ActiveRecord $sourceModel */
        $sourceModel = static::$modelClass;
        $sourceModel = $sourceModel::findOne($id);

        if ($sourceModel == null) {
            return null;
        }

        $model = new static();
        $model->_sourceModel = $sourceModel;
        $model->setAttributes($sourceModel->getAttributes());

        return $model;
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            Yii::info('Model not saved due to validation error.', __METHOD__);
            return false;
        }

        $this->_sourceModel->scenario = $this->modelScenario;
        $this->_sourceModel->setAttributes($this->getAttributes());

        if (!$this->_sourceModel->save()) {
            $this->populateErrors($this->_sourceModel, $this->modelDefaultAttribute);
            return false;
        }

        return true;
    }

    /**
     * @param ActiveRecord $model
     * @param string $defaultAttribute
     * @param array $attributesMap
     */
    public function populateErrors(ActiveRecord $model, $defaultAttribute, $attributesMap = [])
    {
        /** @var Model $this */
        $errors = $model->getErrors();

        foreach ($errors as $attribute => $messages) {
            $attribute = isset($attributesMap[$attribute])
                ? $attributesMap[$attribute]
                : $attribute;
            if (false === $this->hasProperty($attribute)) {
                if (!method_exists($this, 'hasAttribute')) {
                    $attribute = $defaultAttribute;
                } elseif (false === $this->hasAttribute($attribute)) {
                    $attribute = $defaultAttribute;
                }
            }
            foreach ($messages as $mes) {
                $this->addError($attribute, $mes);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->_sourceModel->getPrimaryKey();
    }

    /**
     * @return ActiveRecord
     */
    public function getSource()
    {
        return $this->_sourceModel;
    }
}