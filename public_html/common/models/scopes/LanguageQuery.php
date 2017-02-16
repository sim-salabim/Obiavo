<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class LanguageQuery extends ActiveQuery {

    public function isDefault($default = true){
        return $this->andWhere(['is_default' => $default]);
    }
}