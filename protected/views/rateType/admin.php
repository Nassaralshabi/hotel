<?php

$this->breadcrumbs=array(

	'Rate Types'=>array('index'),

	'Manage',

);



$this->menu=array(

	//array('label'=>'List RateType', 'url'=>array('index')),

	array('label'=>'Create RateType', 'url'=>array('create')),

);



Yii::app()->clientScript->registerScript('search', "

$('.search-button').click(function(){

	$('.search-form').toggle();

	return false;

});

$('.search-form form').submit(function(){

	$.fn.yiiGridView.update('rate-type-grid', {

		data: $(this).serialize()

	});

	return false;

});

");

?>



<h1> <?php echo Yii::t('views','Manage Rate Types') ?> </h1>



<!--<p>

You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>

or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.

</p>-->



<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>

<div class="search-form" style="display:none">

<?php $this->renderPartial('_search',array(

	'model'=>$model,

)); ?>

</div><!-- search-form -->



<?php $this->widget('zii.widgets.grid.CGridView', array(

	'id'=>'rate-type-grid',

	'dataProvider'=>$model->search(),

	'filter'=>$model,

	'columns'=>array(

		'rate_type_id',

		'rate_name',

		'days',

		'branch_id',

		array(

			'class'=>'CButtonColumn',

		),

	),

)); ?>

