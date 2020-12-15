<div id="alert-history-change"><?php echo $this->Session->flash(); ?></div>

<div id="history_changes_preview" style="margin-top: 10px;">
	<table class="tight">
		<thead>
			<tr>
				<th class="center" width="100px"><?php echo __('Korisnik'); ?></th>
				<th class="left"><?php echo __('Promena'); ?></th>
				<th class="right"><?php echo __('Datum promene'); ?></th>
			</tr>
		</thead>

		<tbody id="list_history_changes">
			<?php if ( !empty( $historyChanges ) ){ ?>
				<?php foreach ( $historyChanges as $historyChange ) { ?>
				<tr class="history_change_row">
					<!-- Korisnik -->
					<td class="center" width="120px">
						<?php echo $this->Html->image(str_replace("\\", "/", $historyChange['User']['avatar_link']), array('alt' => $historyChange['User']['username'], 'style' => 'width:50px; height:50px;'));?><br/><?php echo $historyChange['User']['first_name']; ?>
					</td>

					<!-- Promena -->
					<td class="left" valign="top">
						<?php echo $historyChange['HistoryChange']['change_description']; ?>
					</td>

					<!-- Datum promene -->
					<td class="right">
						<?php echo date('d.M.Y H:i', strtotime($historyChange['HistoryChange']['created'])); ?>
					</td>
				</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>

<script>

<?php if ( empty( $historyChanges ) ){ ?>
	$("#history_changes_preview").hide();
<?php } ?>

</script>