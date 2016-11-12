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

    /**
     * Имена связей
     */
    public $relations = [];

    /**
     * Массив данных связанных моделей
     */
    protected $relationalData = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    /**
     * Загрузить связи данными
     * @param type $relations
     */

    public function loadRelation($relationName, $data) {
        $modelParent = $this->owner;

        $getter = 'get' . $relationName;
        $modelClassRelation = $modelParent->$getter()->modelClass;

        $modelRelation = $modelParent->$getter()->one();

        if (! $modelRelation){
            $modelRelation = Yii::createObject($modelClassRelation);
        }

        $modelRelation->load($data);

        $this->relationalData[$relationName] = $modelRelation;

        $this->owner->populateRelation($relationName, $modelRelation);
    }

    /**
     * Загрузить данные в объект и связи
     * @param array $relations
     * @param array $data
     */
    public function loadWithRelation($relations = [], $data){
        $this->owner->load($data);

        foreach  ($relations as $relation){
            $this->loadRelation($relation, $data);
        }
    }

    public function getRelationData($relationName)
    {
        return isset($this->relationalData[$relationName]) ? $this->relationalData[$relationName] : null;
    }

    public function afterSave(){
        $model = $this->owner;

        foreach ($this->relations as $relation){

            $getter = 'get' . $relation;

            $relationModel = $this->getRelationData($relation);

            if (!$relationModel) throw new Exception ("Unknown relation name {$relation} for {$model::className()}");

            foreach ($model->$getter()->link as $childFK => $parentPK){
                $relationModel->$childFK = $model->$parentPK;
            }

            if (!$relationModel->save()) {
                throw new RelationException('Model ' . $relationModel::className() . ' not saved due to unknown error');
            }

//            var_dump($relationModel->getErrors());die;
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
}