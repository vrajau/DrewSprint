<div id="nav">

	<div class="logo">
		<a href='/'><img src="assets/images/drew-logo.jpg" width="100%" alt="" /></a>
	</div><!-- /.log -->

	<div class="site-credit">
		DrewSprint v1.0.0
	</div><!-- /.site-credit -->

	<ul>
		<li>
		
				<i class="fa fa-fw fa-trello"></i>
				Boards	
			<ul class="boards"><?php displayBoards($database)?></ul>

		</li>
		<li>
				<i class="fa fa-fw fa-archive"></i>
				Archive
				<ul class="boards"><?php displayBoards($database,'archive'); ?></ul>
		</li>
		
		
	</ul>
</div><!-- /.nav -->