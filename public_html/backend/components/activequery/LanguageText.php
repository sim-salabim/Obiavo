<?php
namespace backend\components\activequery;

/*
 * Класс для работы с языковыми текстами
 */

class LanguageText extends \yii\db\ActiveQuery{


    /**
     * Вовзращает тексты со связанной таблицы для текущего языка
     *
     * @param $relationName Имя связи с таблицей текстов
     */
    public function withText($relationName, $foreignKey = 'languages_id'){

        $this->with([$relationName => function ($query) use ($foreignKey) {

                    $query->andWhere([$foreignKey => \Yii::$app->user->getLanguage()->id]);
                }
        ]);

        return $this;
    }
}