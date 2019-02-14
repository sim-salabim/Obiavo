<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class AddApplicationQuery extends ActiveQuery {

    use \common\models\scopes\traits\Text;

    const TEXT_RELATION = 'addApplicationText';
    const TEXT_RELATION_TABLE = 'add_application_text';

    public function searchUrlByLanguage($add_application_url){
        return
            $this->joinWith(['addApplicationText' => function(\yii\db\ActiveQuery $query){
                $query->andWhere(['add_application_text.languages_id' => \Yii::$app->location->language->id]);
            }])
                ->andWhere(['add_application_text.url' => $add_application_url]);
    }

}