<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php //echo $form->label($model,'newspaper_id'); ?>
		<?php //echo $form->textField($model,'newspaper_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'newspaper_name'); ?>
		<?php echo $form->textField($model,'newspaper_name',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php //echo $form->label($model,'branch_id'); ?>
		<?php //echo $form->textField($model,'branch_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->