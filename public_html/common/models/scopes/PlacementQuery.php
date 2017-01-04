<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class PlacementQuery extends ActiveQuery {

    public function withText(){
        return $this->with('placementsText');
    }

}