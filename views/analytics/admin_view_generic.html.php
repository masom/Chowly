<div id="ribbon">
	<span>Analytics: Latest</span>
</div>
<div id="content-wrapper">
	<div style="margin-left: 20px; margin-right: 20px; margin-top: 20px;">
		<h1>Latest Analytics</h1>
		<table cellspacing="0" cellpadding="0">
			<tr><th>Date</th><th>IP</th><th>Url</th></tr>
			<?php foreach($analytics as $analytic):?>
				<tr>
					<td><?=date('D, M, j, Y, H:i:s', $analytic->created->sec);?></td>
					<td><?=$analytic->ip_address;?></td>
					<td><?=$this->html->link($analytic->url, $analytic->params->to('array'));?></td>
				</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>