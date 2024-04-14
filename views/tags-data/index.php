<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TagsDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tags Datas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tags-data-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tags Data'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tanggal',
            'N110_TEMP',
            'N110_RH',
            'N110___DP_CE_RE',
            'N110_DP2',
            //'N111_TEMP',
            //'N111_RH',
            //'N111___DP_CE_RE',
            //'N111_DP2',
            //'N122_TEMP',
            //'N122_CE_RE_DP',
            //'N120_TEMP',
            //'N120_RH',
            //'N120___DP_RE_RE',
            //'N178_TEMP',
            //'N178_DP',
            //'N179_TEMP',
            //'N180_TEMP',
            //'N180_RH',
            //'N180_DP1',
            //'N180_DP2',
            //'N181_TEMP',
            //'N181_RH',
            //'N181_DP1',
            //'N181_DP2',
            //'N182_TEMP',
            //'N182_RH',
            //'N182_DP1',
            //'N182_DP2',
            //'N183_TEMP',
            //'N183_RH',
            //'N183_DP1',
            //'N183_DP2',
            //'N185_TEMP',
            //'N185_DP',
            //'CLASS_F_TEMP',
            //'CLASS_F_DP',
            //'W113_TEMP',
            //'W171A_TEMP',
            //'N176A_TEMP',
            //'N176A_RH',
            //'N176A_DP',
            //'N176B_TEMP',
            //'N140A_TEMP',
            //'N140A_RH',
            //'N140A_DP',
            //'N140B_TEMP',
            //'N140B_RH',
            //'N140B_DP',
            //'N142_DP',
            //'N166_TEMP',
            //'N166_RH',
            //'N166_DP1',
            //'N166_DP',
            //'N167_TEMP',
            //'N167_RH',
            //'N167_DP1',
            //'N167_DP2',
            //'N168_TEMP',
            //'N168_RH',
            //'N168_DP1',
            //'N168_DP2',
            //'N169_TEMP',
            //'N169_RH',
            //'N169_DP1',
            //'N169_DP2',
            //'N170_TEMP',
            //'N170_RH',
            //'N170_DP1',
            //'N170_DP2',
            //'N171_TEMP',
            //'N171_RH',
            //'N171_DP',
            //'N145_TEMP',
            //'N145_RH',
            //'N145_DP',
            //'N147_TEMP',
            //'N147_RH',
            //'N147_DP',
            //'N148_TEMP',
            //'N148_RH',
            //'N148_DP',
            //'N153_TEMP',
            //'N153_RH',
            //'N153_DP',
            //'N154_TEMP',
            //'N154_RH',
            //'N154_DP',
            //'N155_TEMP',
            //'N155_RH',
            //'N155_DP',
            //'N156A_TEMP',
            //'N156A_RH',
            //'N156A_DP',
            //'N156B_TEMP',
            //'N156B_RH',
            //'N156B_DP',
            //'N157_TEMP',
            //'N157_RH',
            //'N157_DP',
            //'N158A_TEMP',
            //'N158A_RH',
            //'N158A_DP',
            //'N161_TEMP',
            //'N161_RH',
            //'N161_DP',
            //'N162_TEMP',
            //'N162_RH',
            //'N162_DP',
            //'N163_TEMP',
            //'CORRIDOR_TEMP',
            //'CORRIDOR_CE_RE_DP',
            //'CORRIDOR_C_E_TEMP',
            //'CORRIDOR_C_E_DP',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
