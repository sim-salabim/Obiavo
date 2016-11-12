<?php
/**
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace backend\behaviors;

use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Поведение для сохранения связанных моделей
 * ```
 *
 * @property ActiveRecord $owner
 */
class SaveRelation extends Behavior
{
    public $relations = [];

    /**
     * @var array
     */
    public $relationalFields = [];

    /**
     * @var bool
     */
    protected $relationalFinished = false;

    /**
     * @var array
     */
    protected $relationalData = [];

    public function getRelationData($attribute)
    {
        return isset($this->relationalData[$attribute]) ? $this->relationalData[$attribute]['data'] : null;
    }

    /**
     * Permission for this behavior to set relational attributes.
     *
     * {@inheritdoc}
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return in_array($name, $this->relationalFields) || parent::canSetProperty($name, $checkVars);
    }

    /**
     *
     * {@inheritdoc}
     */
    public function __set($name, $value)
    {
        if (in_array($name, $this->relationalFields)) {
            if (!is_array($value) && !empty($value)) {
                $this->owner->addError($name,
                    Yii::$app->getI18n()->format(
                        Yii::t('yii', '{attribute} is invalid.'),
                        ['attribute' => $this->owner->getAttributeLabel($name)],
                        Yii::$app->language
                    )
                );
            } else {
                $this->relationalData[$name] = ['data' => $value];
            }
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * Загружаем связанные данные
     * $this->relationalData[$attribute] = [
     *      'newModels' => ActiveRecord[],
     *      'oldModels' => ActiveRecord[],
     *      'activeQuery' => ActiveQuery,
     * ];
     * ```
     *
     * @throws RelationException
     */
    protected function loadData()
    {
        /** @var ActiveQuery $activeQuery */
        var_dump($this->relations);die;
        foreach ($this->relations as $attribute => &$data) {

            $getter = 'get' . ucfirst($attribute);
            $data['activeQuery'] = $activeQuery = $this->owner->$getter();
            $data['newModels'] = [];
            $class = $activeQuery->modelClass;

            $notAssociativeArrayOn = !ArrayHelper::isAssociative($activeQuery->on) &&
                !empty($activeQuery->on);

            $notAssociativeArrayViaOn = $activeQuery->multiple &&
                !empty($activeQuery->via) &&
                is_object($activeQuery->via[1]) &&
                !ArrayHelper::isAssociative($activeQuery->via[1]->on) &&
                !empty($activeQuery->via[1]->on);

            if ($notAssociativeArrayOn || $notAssociativeArrayViaOn) {
                Yii::$app->getDb()->getTransaction()->rollBack();
                throw new RelationException('ON condition for attribute ' . $attribute . ' must be associative array');
            }

            $params = !ArrayHelper::isAssociative($activeQuery->on) ? [] : $activeQuery->on;

            if ($activeQuery->multiple) {
                if (empty($activeQuery->via)) {
                    // one-to-many
                    foreach ($activeQuery->link as $childAttribute => $parentAttribute) {
                        $params[$childAttribute] = $this->owner->$parentAttribute;
                    }

                    if (!empty($data['data'])) {
                        foreach ($data['data'] as $attributes) {
                            $data['newModels'][] = new $class(array_merge($params,
                                ArrayHelper::isAssociative($attributes) ? $attributes : []));
                        }
                    }
                } else {
                    // many-to-many
                    if (!is_object($activeQuery->via[1])) {
                        throw new RelationException('via condition for attribute ' . $attribute . ' cannot must be object');
                    }

                    $via = $activeQuery->via[1];
                    $junctionGetter = 'get' . ucfirst($activeQuery->via[0]);
                    $data['junctionModelClass'] = $junctionModelClass = $via->modelClass;
                    $data['junctionTable'] = $junctionModelClass::tableName();

                    list($data['junctionColumn']) = array_keys($via->link);
                    list($data['relatedColumn']) = array_values($activeQuery->link);
                    $junctionColumn = $data['junctionColumn'];
                    $relatedColumn = $data['relatedColumn'];

                    if (!empty($data['data'])) {
                        // make sure what all model's ids from POST exists in database
                        $countManyToManyModels = $class::find()->where([$class::primaryKey()[0] => $data['data']])->count();
                        if ($countManyToManyModels != count($data['data'])) {
                            throw new RelationException('Related records for attribute ' . $attribute . ' not found');
                        }
                        // create new junction models
                        foreach ($data['data'] as $relatedModelId) {
                            $junctionModel = new $junctionModelClass(array_merge(!ArrayHelper::isAssociative($via->on) ? [] : $via->on,
                                [$junctionColumn => $this->owner->getPrimaryKey()]));
                            $junctionModel->$relatedColumn = $relatedModelId;
                            $data['newModels'][] = $junctionModel;
                        }
                    }

                    $data['oldModels'] = $this->owner->$junctionGetter()->all();
                }

            } elseif (!empty($data['data'])) {
                // one-to-one
                $data['newModels'][] = new $class($data['data']);
            }

            if (empty($activeQuery->via)) {
                $data['oldModels'] = $activeQuery->all();
            }
            unset($data['data']);

            foreach ($data['newModels'] as $i => $model) {
                $data['newModels'][$i] = $this->replaceExistingModel($model, $attribute);
            }
        }
    }

    public function saveData()
    {
        $needSaveOwner = false;
        var_dump($this->relationalData);die;
        foreach ($this->relationalData as $attribute => $data) {
            /** @var ActiveRecord $model */
            foreach ($data['newModels'] as $model) {
                if ($model->isNewRecord) {
                    if (!empty($data['activeQuery']->via)) {
                        // only for many-to-many
                        $junctionColumn = $data['junctionColumn'];
                        $model->$junctionColumn = $this->owner->getPrimaryKey();
                    } elseif ($data['activeQuery']->multiple) {
                        // only one-to-many
                        foreach ($data['activeQuery']->link as $childAttribute => $parentAttribute) {
                            $model->$childAttribute = $this->owner->$parentAttribute;
                        }
                    }
                    if (!$model->save()) {
                        Yii::$app->getDb()->getTransaction()->rollBack();
                        throw new RelationException('Model ' . $model::className() . ' not saved due to unknown error');
                    }
                }
            }

            foreach ($data['oldModels'] as $model) {
                if ($this->isDeletedModel($model, $attribute)) {
                    if (!$model->delete()) {
                        Yii::$app->getDb()->getTransaction()->rollBack();
                        throw new RelationException('Model ' . $model::className() . ' not deleted due to unknown error');
                    }
                }
            }

            if (!$data['activeQuery']->multiple && (count($data['newModels']) == 0 || !$data['newModels'][0]->isNewRecord)) {
                $needSaveOwner = true;
                foreach ($data['activeQuery']->link as $childAttribute => $parentAttribute) {
                    $this->owner->$parentAttribute = count($data['newModels']) ? $data['newModels'][0]->$childAttribute : null;
                }
            }
        }

        $this->relationalFinished = true;

        if ($needSaveOwner) {
            $model = $this->owner;
            $this->detach();
            if (!$model->save()) {
                Yii::$app->getDb()->getTransaction()->rollBack();
                throw new RelationException('Owner-model ' . $model::className() . ' not saved due to unknown error');
            }
        }
    }

    /**
     * Заполняет модель данными + заполняет данными связанных моделей
     * @example $data = [
     *                      'Model1[field1] => value','Model1[field2] => value',
     *                      'Model2[field1] => value','Model2[field2] => value',
     *                  ];
     * @param type $data
     */
    public function loadWithRelated($data){

        $this->owner->load($data);
        foreach ($this->relationalFields as $x){
            var_dump($this->owner);
            die;
             $this->populateRelation('relationName', $relateModel);
        }
    }

    /**
     * !!! Перенести в отдельный класс
     *
     * Смотри сюда \yii\base\Model::loadMultiple()
     *
     * FIX: Обрабатывает стандартный массив index[formName]
     *      + поддерживает несколько
     */
    public static function loadMultiple($models, $data)
    {
        $success = false;
        foreach ($models as $indexModel => $model) {

            if (!is_string($indexModel)) {
                $indexModel = (new \ReflectionClass($model))->getShortName();
            }

            if (!empty($data[$indexModel])){

                $model->load($data[$indexModel], '');
                $success = true;
            }
        }
        return $success;
    }

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'loadData',
            ActiveRecord::EVENT_AFTER_UPDATE => 'loadData',
        ];
    }
}