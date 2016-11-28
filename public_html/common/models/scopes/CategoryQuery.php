<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class CategoryQuery extends ActiveQuery {

    /**
     * Получить потомков
     */
    public function withChildrens(){
        return $this->with('childrens');
    }

    /**
     * Категория с текущим переводом
     */
    public function withText(){
        return $this->with('categoriesText');
    }

    /**
     * Категория без родителей
     */
    public function orphan(){

        return $this->andWhere(['parent_id' => NULL]);
    }

}