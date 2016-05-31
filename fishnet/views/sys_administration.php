<section class="primary">
	<div class="header-a">
		<p>&nbsp;</p>
		<h2><?=anchor("/admin", val($title, 'Untitled Page'))?></h2>
	</div>

	<h3 class="c section-a">Tools</h3>
	<ul>
		<li><?=anchor("/admin/rebuildindex", "Rebuild Search Index")?></li>
		<li><?=anchor("/admin/backup", "Backup DB")?></li>
		<li><?=anchor("/admin/importSpecialtyStores", "Import Specialty Stores")?></li>
	</ul>

    <h3 class="c section-a">HR Tools: Populate Import DB first</h3>
    <ul>
		<li><?=anchor("/admin/hrUsersUpdate", "HR Users Update")?></li>
		<li><?=anchor("/admin/hrUsersDepartmentUpdate", "HR Users Department Update")?></li>
        <li><?=anchor("/admin/hrHideTerminations", "HR Hide Terminated Employees")?></li>
	</ul>

	<h3 class="c section-a">Migration Fixes</h3>
	<ul>
		<li><?=anchor("/admin/migrate", "Migrate")?></li>
		<li><?=anchor("/admin/fixLinks", "Fix Links")?></li>
	</ul>

	<?php if(isset($results)): ?>
		<div id="results" class="section-a">
			<?php echo $results ?>
		</div>
	<?php endif; ?>

	<div id="hradImport" class="section-a">
		<h2 class="c">Import ADP file into Active Directory</h2>
		<p>Use this tool to import data from the ADP database into Active Directory. First export from ADP into a CSV file and then import that file using the box below.</p>
		<form enctype="multipart/form-data" action="https://fishnet.eileenfisher.com/hrimport/2ndstep.php" method="POST" id="formHRAD">
			Import CSV file: <input id="fileHRAD" name="file" type="file"><br><br>
			<button id="btnHRADImport">Import</button>
		</form>
	</div>
</section>
