<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class CmsQuery extends ActiveQuery {

    use \common\models\scopes\traits\Text;

    const TEXT_RELATION = 'cmsText';
    const TEXT_RELATION_TABLE = 'cms_text';

    public function searchUrlByLanguage($cmsUrl){
        return
            $this->joinWith(['cmsText' => function(\yii\db\ActiveQuery $query){
                $query->andWhere(['cms_text.languages_id' => \Yii::$app->location->language->id]);
            }])
                ->andWhere(['cms_text.url' => $cmsUrl]);
    }

    public function withText($languages_id = null){

        return $this->with(['cmsText' => function($query) use ($languages_id){
            $tableName = \common\models\CmsText::tableName();

            if ($languages_id){
                return $query->andWhere(["$tableName.languages_id" => $languages_id]);
            }
        }]);
    }
}