<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class CurrencyQuery extends ActiveQuery {

    use \common\models\scopes\traits\Text;

    const TEXT_RELATION = 'currencyText';
    const TEXT_RELATION_TABLE = 'currency_text';

    public function searchUrlByLanguage($name){
        return
            $this->joinWith(['currencyText' => function(\yii\db\ActiveQuery $query){
                $query->andWhere(['currency_text.languages_id' => \Yii::$app->location->language->id]);
            }])
                ->andWhere(['currency_text.name' => $name]);
    }

}