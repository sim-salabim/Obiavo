<?php
namespace common\models\scopes;

use common\models\Language;
use yii\db\ActiveQuery;
use common\models\PlacementsText;

class PlacementQuery extends ActiveQuery {

    use \common\models\scopes\traits\Text;

    const TEXT_RELATION = 'placementsText';
    const TEXT_RELATION_TABLE = 'placements_text';

//    public function withText(){
//        return $this->with('placementsText');
//    }

    public function seoUrl($name, $operator = '='){
        $tbPlacementsText = PlacementsText::tableName();

        return $this->joinWith('placementsText')
                    ->andWhere(['languages_id' => Language::getId()])
                    ->onCondition(['=',"$tbPlacementsText.url",$name]);
    }
}