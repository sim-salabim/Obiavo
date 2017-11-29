<?php
namespace common\models\scopes;

use yii\db\ActiveQuery;

class CityOrderQuery extends ActiveQuery {

    use \common\models\scopes\traits\Text;

    const TEXT_RELATION = 'cityText';
    const TEXT_RELATION_TABLE = 'cities_text';

}