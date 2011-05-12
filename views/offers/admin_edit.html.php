<?php 
$starts = ($offer->_id)? $offer->starts->sec : time();
$ends = ($offer->_id)? $offer->ends->sec : time() + 60 * 60 * 24 * 30;
$availability = array();
foreach(range(10, 100, 10) as $value){
	$availability[$value] = $value;
}
?>
<div id="ribbon">
	<span><?=($offer->exists())? "Modifying Offer {$offer->name}": "New Offer";?></span>
</div>
<div id="content-wrapper">
	<div style="margin-top: 40px; margin-left: auto; margin-right: auto; width: 620px;">
		<?php if($offer->errors()):?>
			<h4>There is <?=count($offer->errors());?> validation errors.</h4>
		<?php endif;?>
		<?=$this->form->create($offer, array('type' => 'file', 'id'=>'form_template')); ?>
		<h3>Step <span id="offer-create-step-number"></span></h3>
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
				<div style="height: 200px; width: 500px;">
					<div style="width: 500px; height: 30px;">
						<span style="float: left;">Assigned Limitations</span>
						<span style="float: right;">Unassigned Limitations</span>
					</div>
					<select id="offer-limitations" multiple="multiple" name="limitations" style="float: left; height: 200px; width: 200px;background-color: #ffffff;">
					<?php foreach($offer->limitations as $key => $name):?>
						<option value="<?=$key;?>"><?=$name;?></option>
					<?php endforeach;?>
					</select>
					<select id="offer-limitations-selection" multiple="multiple" style="float: right; height: 200px; width: 200px;background-color: #ffffff;">
						<?php foreach ($limitations as $key => $name):?>
							<option value="<?=$name;?>"><?=$name;?></option>
						<?php endforeach;?>
					</select>
				</div>
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
					<?=$this->form->field('availability', array('tyle'=>'select', 'list'=> $availability,'id'=>'offer_availability','label'=>'How many coupons?', 'style'=>'width: 100px;'));?>
					<?=$this->form->hidden('template_id', array('value'=> $offer->template_id));?>
				<?php endif;?>
			</div>
			<div>
				<button id="form_template_save" onclick="return false;">Save</button>
				<button id="form_template_cancel" onclick="return false;">Cancel</button>
			</div>
			</div>
		<?=$this->form->end(); ?>
		<div style="width: 200px; height: 50px; margin-left: auto; margin-right: auto;">
			<button id="offer-create-previous" style="float: left;">Previous</button>
			<button id="offer-create-next" style="float: right;">Next</button>
		</div>
	</div>
</div>

<script type="text/javascript">
var OfferWizard = {
	init: function(container){
		this._container = container;
		this._steps = $(container).children();
		this._stepCount = this._steps.length;
		this._currentStep = 1;
		this._updateStep();
		$(container).children().hide();
		$('#offer-create-previous').hide();

		this._current = $(container).children().first();

		$('#offer-create-previous').bind('click', this.onPreviousClicked);
		$('#offer-create-next').bind('click', this.onNextClicked);

		this._limitation = {selected: $('#offer-limitations'), available: $('#offer-limitations-selection')};
		this._limitation['selected'].bind('click', this.onLimitationSelectedClicked);
		this._limitation['available'].bind('click', this.onLimitationAvailableClicked);
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

		OfferWizard._currentStep++;

		if(OfferWizard._currentStep == OfferWizard._stepCount){
			$('#offer-create-next').hide();
		}

		$(this._current).fadeOut(200, function(){
			$(this).next().fadeIn(200, function(){
				OfferWizard._updateStep();
				OfferWizard._current = this;
				OfferWizard._enableButtons();
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

		OfferWizard._currentStep--;

		if(OfferWizard._currentStep == 1){
			$('#offer-create-previous').hide();
		}

		$(this._current).fadeOut(200, function(){
			OfferWizard._updateStep();
			$(this).prev().fadeIn(200, function(){
				OfferWizard._current = this;
				OfferWizard._enableButtons();
			});
		});
	},
	_updateStep: function(){
		$('#offer-create-step-number').text(this._currentStep + ' of ' + this._stepCount);
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
		OfferWizard._disableButtons();
		OfferWizard.previous();
	},
	onNextClicked: function(e){
		e.preventDefault();
		OfferWizard._disableButtons();
		OfferWizard.next();
	},
	onLimitationSelectedClicked: function(e){
		if(!$(e.target).is('option')){
			return false;
		}
		$(e.target).removeAttr("selected");
		$(e.target).detach();
		$(e.target).appendTo(OfferWizard._limitation['available']);		
	},
	onLimitationAvailableClicked : function(e){
		if(!$(e.target).is('option')){
			return false;
		}
		$(e.target).removeAttr("selected");
		$(e.target).detach();
		$(e.target).appendTo(OfferWizard._limitation['selected']);
	}
};

$(function() {
	OfferWizard.init('#offer-create-steps');
	OfferWizard.start();
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