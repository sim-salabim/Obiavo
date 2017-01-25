<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;
use common\models\PlacementsText;

class PlacementQuery extends ActiveQuery {
    
    public function withText(){
        return $this->with('placementsText');
    }
    
    public function seoUrl($name, $operator = '='){
        $tbPlacementsText = PlacementsText::tableName();
        
        return $this->joinWith('placementsText')
                    ->onCondition(['=',"$tbPlacementsText.url",$name]);;
    }   
}