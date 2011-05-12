<?php 
$starts = ($offer->_id)? $offer->starts->sec : time();
$ends = ($offer->_id)? $offer->ends->sec : time() + 60 * 60 * 24 * 30;
?>
<div id="ribbon">
	<span><?=($offer->exists())? "Modifying Offer {$offer->name}": "New Offer";?></span>
</div>
<div id="content-wrapper">
	<div style="margin-top: 40px; margin-left: auto; margin-right: auto; width: 620px;">
		<?=$this->form->create($offer, array('type' => 'file', 'id'=>'form_template')); ?>
		<div id="offer-create-steps" style="margin: 20px;">
			<?php if(!$offer->exists()):?>
				<div>
					<?=$this->form->field('venue_id', array('type'=>'select', 'list' => $venues, 'label'=>'Select the venue')); ?>
				</div>
			<?php endif;?>
			
			<div>
				<?=$this->form->field('name'); ?>
				<?=$this->form->field('description', array('type'=>'textarea')); ?>
			</div>
			<div>
				<?=$this->form->field('limitations', array('type'=>'textarea', 'label'=>'Limitations')); ?>
			</div>
	
			<div>
				<?=$this->form->field('cost', array('label' => 'Price in C$', 'id'=>'offer_cost'));?>
				<ul class="time-picker">
					<?=$this->form->field('starts', array('value'=>date('Y-m-d H:i:s', $starts),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_template_start_date'));?>
					<?=$this->form->field('ends', array('value' => date('Y-m-d H:i:s', $ends ),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_template_end_date'));?>
					<?=$this->form->field('expires', array('value'=>date('Y-m-d H:i:s', $ends),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_offer_expires'));?>
				</ul>
				<br style="clear: both;" />
				<?php if(!$offer->exists()):?>
					<?=$this->form->field('availability', array('tyle'=>'select', 'list'=> range(10, 100, 10),'id'=>'offer_availability','label'=>'How many coupons?', 'style'=>'width: 100px;'));?>
					<?=$this->form->hidden('template_id', array('value'=> $offer->template_id));?>
				<?php endif;?>
			</div>
			<div>
				<button id="form_template_save" onclick="return false;">Save</button>
				<button id="form_template_cancel" onclick="return false;">Cancel</button>
			</div>
			</div>
		<?=$this->form->end(); ?>
		<div style="width: 200px; height: 50px;">
			<button id="offer-create-previous" style="float: left;">Previous</button>
			<button id="offer-create-next" style="float: right;">Next</button>
		</div>
	</div>
</div>

<script type="text/javascript">
var OfferSteps = {
	init: function(container){
		this._container = container;
		this._steps = $(container).children();
		this._stepCount = this._steps.length;
		this._currentStep = 1;

		$(container).children().hide();
		$('#offer-create-previous').hide();

		this._current = $(container).children().first();

		$('#offer-create-previous').bind('click', this.onPreviousClicked);
		$('#offer-create-next').bind('click', this.onNextClicked);
	},
	start: function(){
		this._current.fadeIn(200);
	},
	next: function(){
		if(this._currentStep >= this._stepCount || !$(this._current).next()){
			return false;
		}

		if(this._currentStep == 1){
			$('#offer-create-previous').show();
		}

		OfferSteps._currentStep++;

		if(OfferSteps._currentStep == OfferSteps._stepCount){
			$('#offer-create-next').hide();
		}

		$(this._current).fadeOut(200, function(){
			$(this).next().fadeIn(200, function(){
				OfferSteps._current = this;
				OfferSteps._enableButtons();
			});
		});
	},
	previous: function(){
		if(this._currentStep <= 1 || !$(this._current).prev()){
			return false;
		}
		
		if(this._currentStep == this._stepCount){
			$('#offer-create-next').show();
		}

		OfferSteps._currentStep--;

		if(OfferSteps._currentStep == 1){
			$('#offer-create-previous').hide();
		}

		$(this._current).fadeOut(200, function(){

			$(this).prev().fadeIn(200, function(){
				OfferSteps._current = this;
				OfferSteps._enableButtons();
			});
		});
	},
	_enableButtons: function(){
		$('#offer-create-previous').attr('disabled',false);
		$('#offer-create-next').attr('disabled', false);
	},
	_disableButtons: function(){
		$('#offer-create-previous').attr('disabled',true);
		$('#offer-create-next').attr('disabled', true);
	},
	restart: function(){
		$(this._current).fadeOut(200, function(){
			this.parent().childrend().first().fadeIn(200);
		});
		this._currenStep = 1;
		this._current = $(this._container).children().first();
	},
	onPreviousClicked: function(e){
		e.preventDefault();
		OfferSteps._disableButtons();
		OfferSteps.previous();
	},
	onNextClicked: function(e){
		e.preventDefault();
		OfferSteps._disableButtons();
		OfferSteps.next();
	}
};

$(function() {
	OfferSteps.init('#offer-create-steps');
	OfferSteps.start();
});


$("#template_availability").numeric();
$("#otemplate_cost").numeric();
$("#form_template_save").bind('click',function(){
	$("#form_template").submit();
});
$("#form_template_cancel").bind('click',function(){
	$('#form_template_save').hide();
	history.back();
});
</script>