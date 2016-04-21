<?php

namespace voskobovich\crud\forms;

use voskobovich\base\interfaces\ModelInterface;
use voskobovich\base\traits\ModelTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;


/**
 * Class FindableFormAbstract
 * @package voskobovich\crud\forms
 *
 * @property string $sourceScenario
 * @property string $defaultAttribute
 *
 * @property ActiveRecord $_source
 * @property ActiveRecord $source
 */
abstract class FindableFormAbstract extends Model implements ModelInterface
{
    use ModelTrait;

    /**
     * Editable model class name
     * @var string
     */
    public static $sourceClass;

    /**
     * Default scenario for editable model
     * @var string
     */
    public $sourceScenario = ActiveRecord::SCENARIO_DEFAULT;

    /**
     * Default attribute for print error
     * @var string
     */
    public $defaultAttribute;

    /**
     * @var ActiveRecord
     */
    private $_source;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!static::$sourceClass) {
            throw new InvalidConfigException('Static property "sourceClass" must be contain source model class name.');
        }

        if (!is_subclass_of(static::$sourceClass, ActiveRecord::className())) {
            throw new InvalidConfigException('Model in "sourceClass" must to be inherited from "' . ActiveRecord::className() . '"');
        }

        $this->_source = new static::$sourceClass();

        parent::init();
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->getSource()->getPrimaryKey();
    }

    /**
     * Populate attributes value of source model
     */
    public function populateSourceAttributes()
    {
        $attributes = array_intersect_key(
            $this->getAttributes(),
            array_flip($this->source->safeAttributes())
        );
        $this->source->setAttributes($attributes);
    }

    /**
     * Populate attributes value
     */
    public function populateAttributes()
    {
        $attributes = array_intersect_key(
            $this->_source->getAttributes(),
            array_flip($this->safeAttributes())
        );
        $this->setAttributes($attributes, false);
    }

    /**
     * @param ActiveRecord $value
     */
    public function setSource(ActiveRecord $value)
    {
        $this->_source = $value;
        $this->_source->scenario = $this->sourceScenario;

        $this->populateAttributes();
    }

    /**
     * @return ActiveRecord
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * @param $id
     * @return null|ActiveRecord
     */
    public static function findOne($id)
    {
        $model = new static();

        /** @var ActiveRecord $source */
        $source = static::$sourceClass;
        $source = $source::findOne($id);

        if ($source == null) {
            return null;
        }

        $model->setSource($source);

        return $model;
    }

    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        if (parent::validate($attributeNames, $clearErrors)) {
            $this->populateSourceAttributes();

            $source = $this->source;

            if (!$source->validate()) {
                if ($this->defaultAttribute && $source->hasErrors()) {
                    $this->populateErrors($source, $this->defaultAttribute);
                }
                return false;
            }

            return true;
        }

        return false;
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

        $this->populateSourceAttributes();

        $source = $this->source;
        $result = $source->save();

        if (!$result) {
            if ($this->defaultAttribute && $source->hasErrors()) {
                $this->populateErrors($source, $this->defaultAttribute);
            }
            return false;
        }

        return true;
    }
}