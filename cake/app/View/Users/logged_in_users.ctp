<ul class="breadcrumbs margin20">
	<li><?php echo $this->Html->link('Home', '/'); ?></li>
	<li class="last"><a href="" onclick="return false">Users</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
	<div class="name_of_page"><h3>Users</h3></div>
	<div class="add_and_search">
	</div>
</div>
<div class='content_data'>
	<table>
	<thead>
		<tr>
		<th>Korisnik</th>
		<th>Ime</th>
		<th>Poslednja aktivnost</th>
		<th>Sesija ističe</th>
		<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($users as $user): ?>
		<tr>
			<td> <?php echo $this->Html->image(str_replace("\\", "/", $user['avatar_link']), array('style' => 'width:100px; height:100px;'));?> </td>
			<td> <?php echo $user['first_name']; ?> <?php echo $user['last_name']; ?> </td>
			<td> <?php echo $this->Time->format('j.m.Y. H:i:s', $user['expires']-600*60); ?> </td>
			<td> <?php echo $this->Time->format('j.m.Y. H:i:s', $user['expires']); ?> </td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>