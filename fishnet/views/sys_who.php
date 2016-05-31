<script>
	$('input').focus();
</script>


<section class="primary">
	<div class="header-a">
		<p>
			<?=isset($back_link) ? "&#x25c4; $back_link |" : "&nbsp;"?>
			Employee Directory
		</p>
		<h2><?=val($title, 'Untitled Page')?></h2>
	</div>

	<img src="<?=STATIC_URL?>images/whos_who.jpg" alt="Who's Who (Employee Directory)" width="759" height="138" />

	<div class="search-directory">
		<form action="" method="post" class="search">
			<p class="search-field">
				<input type="hidden" id="whoUserName" />
				<label for="whoSearchQuery">Search the Employee Directory</label>
				<input type="search" id="whoSearchQuery" name="whoSearchQuery" autofocus="autofocus"
					   placeholder="e.g. kathy or smith" />
                <button type="submit" id="whoSubmit">Go</button>
			</p>
		</form>
	</div>

	<?php if (isset($departments)): ?>
		<div class="section-a">
			<h2 class="c">Departments</h2>

			<ul class="list-b js-who-departments">
				<?php foreach ($departments as $department): ?>
					<li !--class='dontsplit'-->
						<?=anchor("who/department/".$department['id'], htmlentities($department['name'], ENT_COMPAT, 'UTF-8', false))?>
						<?php if (isset($department['children'])): ?>
						<ul>
							<?php foreach ($department['children'] as $child): ?>
								<li>
									<?=anchor("who/department/".$child['id'], htmlentities($child['name'], ENT_COMPAT, 'UTF-8', false))?>
								</li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div> <!--/ .section-a -->
	<?php endif; ?>
</section> <!--/ #primary -->

<?php $this->load->view('page_parts/who_rightcol'); ?>

<?php if(ENVIRONMENT == 'production'): ?>
	<!--CLICK HEAT-->
	<script type="text/javascript" src="<?=base_url()?>clickheat/js/clickheat.js"></script>
	<noscript></noscript>
	<script type="text/javascript">
		<!--
		clickHeatSite = 'intranet';
		clickHeatGroup = "Who's Who";
		clickHeatServer = '<?=base_url()?>clickheat/click.php';
		initClickHeat(); //-->
	</script>
	<!--CLICK HEAT END-->
<?php endif; ?>
