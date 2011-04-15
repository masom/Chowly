<?php 

$months = array(
	'01' => '01',
	'02' => '02',
	'03' => '03',
	'04' => '04',
	'05' => '05',
	'06' => '06',
	'07' => '07',
	'08' => '08',
	'09' => '09',
	'10' => '10',
	'11' => '11',
	'12' => '12'
);
$year = date('Y');
$years = array();
for($i =0; $i < 10; $i++):
	$years[$year] = $year++;
endfor;

$province = ($purchase->province)? $purchase->province : 'Ontario';
?>
<div style="padding-left: 15px; height: 43px; line-height: 43px; color: #ffffff; font-size: 24px; font-weight: bold; background: url(/img/top-ribbon.png);">
	<span>Step 2 of 2: Secure Checkout</span>
</div>
<div id="content-wrapper">
	<div style="margin-left: 20px; margin-right: 20px;">
	
		<?php if(isset($purchase)):?>
			<p><?=$purchase->error;?></p>
		<?php endif;?>
		
		<?=$this->form->create($purchase, array('type' => 'file', 'id'=>'form_purchase')); ?>
			<h2 style="margin-top: 0;">Contact Information</h2>
			<div style="margin-bottom: 40px;">
				<p>Your email is required to send you the coupon.</p>
				<?=$this->form->field('email', array('label'=> 'Email Address','maxlength'=>255)); ?>
			</div>
			
			<h2>Billing Information</h2>
		
			<p>Enter the following information as it appears on the credit card.</p>
			<?=$this->form->field('name', array('label'=> 'Cardholder Name','maxlength'=>255)); ?>
			<?=$this->form->field('address', array('label' => 'Billing Address','maxlength'=>255));?>
			<?=$this->form->field('city', array('label' => 'City'));?>
			<div>
				<?=$this->form->field('province', array('template'=>'<div style="float: left;">{:label}{:input}{:error}</div>','type'=>'select', 'list' => $provinces, 'value'=> $province));?>
				<?=$this->form->field('postal', array('template'=>'<div style="float: left; margin-left: 10px;">{:label}{:input}{:error}</div>','maxlength'=>7));?>
				<?=$this->form->field('phone',array('template'=>'<div style="float: left; margin-left: 10px;">{:label}{:input}{:error}</div>','label' => 'Phone Number', 'maxlength'=>20));?>
			</div>
			<br style="clear: both;" />
			
			<?=$this->form->field('cc_number', array('label'=>'Credit Card Number', 'maxlength'=>'30'));?>
			<div style="width: 120px; float: left;">
				<?=$this->form->field('cc_sc', array('template'=>'{:label}<div style="width: 40px;">{:input}</div>{:error}','label'=>'Security Code','maxlength'=>4));?>
			</div>
			<div style="margin-left: 20px; float:left;">
				<label for="CcEMonth">Expiration Date</label>
				<br />
				<?=$this->form->field('cc_e_month', array('template'=>'<div style="float: left;">{:input}{:error}</div>','type'=>'select','list'=>$months));?>
				<?=$this->form->field('cc_e_year', array('template'=>'<div style="float: left;">{:input}{:error}</div>','type'=>'select','list'=>$years));?>
			</div>
			<br style="clear: both;" />
		
			<div style="margin-top: 20px; margin-bottom: 40px;">
				<?=$this->form->field('agreed_tos_privacy', array('template'=>'{:input}', 'type'=>'checkbox', 'value'=>true))?>
				I agree to the <?=$this->html->link('Terms of Use', array('Pages::view','args'=>'terms'),array('target'=>'_BLANK'));?> and <?=$this->html->link('Privacy Policy', array('Pages::view','args'=>'privacy'),array('target'=>'_BLANK'));?>.
				<?=$this->form->error('agreed_tos_privacy');?>
			</div>
			<button id="form_purchase_save" onclick="return false;">Complete Order</button>
			<button id="form_purchase_cancel" onclick="return false;">Cancel</button>
		<?=$this->form->end(); ?>
	</div>
	<br />
</div>
<script type="text/javascript">
$("#form_purchase_save").bind('click',function(){
	$(this).attr("disabled", "true");
	$(this).text("Processing ...");
	$("#form_purchase").submit();
});
$("#form_purchase_cancel").bind('click',function(){
	$('#form_purchase').hide();
	$('#form_purchase')[0].reset();
	window.location = "/";
});
</script>