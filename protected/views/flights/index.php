<?php
$this->breadcrumbs=array(
	'Flights',
);

$this->menu=array(
	array('label'=>'Create Flights', 'url'=>array('create')),
	array('label'=>'Manage Flights', 'url'=>array('admin')),
);
?>

<h1> <?php echo Yii::t('views','Flights') ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
