<?php
/**
 * Created by: cravelo
 * Date: 4/6/12
 * Time: 2:54 PM
 *
 */
?>
 <?php if ($page_type == 'section'): ?>
 <?/** Is this page a section? If so, show old 'properties_page.php' code.
    *  Modified by MLanni 6/2/2016
    *
    *
    * */ ?>
 
 


	<?php if (isset($canProp) and $canProp): ?>
		<div class="section-a">
			<h2 class="c">General Settings</h2>
			<div class="field-group-a">
				<ul class="settings-a">
					<li>
						<?=form_checkbox(
							array('name' => "allow_comments", 'class' => 'js-gen-settings', 'checked' => $p_allow_comments))?>
						<label for="allow_comments">
							<strong>Allow New Comments</strong> This allows other users to comment on this page.
						</label>
					</li>
				</ul>
	
				<?php if ($page_type !== 'section'): ?>
					<div class="field page-property">
						<p class="select-c templates">
							<label for="template_id"
								   title="Use this dropdown to change the page's template, please be careful when changing templates, some templates are not compatible with others.">
								Template:
							</label>
							<select name="template_id" id="template_id" class="js-gen-settings">
								<?php foreach($templates as $template):
									$selected = ($template['template_id'] == $template_id) ? 'selected="selected"' : ''; ?>
									<option value="<?=$template['template_id']."\"$selected"?>">
										<?=$template['template_title']?>
									</option>
								<?php endforeach; ?>
							</select>
						</p>
					</div>
				<?php endif; ?>
				<div class="field">
					<p class="one">
						<label for="date_published" title='"Date published" designates the date on which pages appear in dated lists (e.g. monthly, seasonal). It also determines the order in which pages will appear.'>
							Date Published:
						</label>
						<input type="text" id="date_published" name="date_published" class="js-gen-settings"
							   value="<?=date("Y-m-d", strtotime($date_published))?>" />
					</p>
					<p class="two">
						<label for="show_until"
							   title='"Display until" selects the last date by which the page will be shown in dated lists (e.g. monthly, seasonal).'>
							Display until:
						</label>
						<input type="text" id="show_until" name="show_until" class="js-gen-settings" value="<?=$show_until?>" />
					</p>
				</div>
				<p class="field">
					<label for="expiration_date"
						   title="Setting an expiration date will delete this page at the end of that day. Deleted pages are purged after 30 days!">
						Expiration Date:
					</label>
					<input type="text" id="expiration_date" name="expiration_date" class="js-gen-settings" value="<?=$expiration_date?>" />
				</p>
	
				<?php if ($page_type !== 'section'): ?>
					<h3 class="settings-a">Featured Article</h3>
					<ul class="settings-a">
						<li>
							<?=form_checkbox(array('name' => "featured", 'class' => 'js-gen-settings', 'checked' => (boolean)($featured)))?>
							<label for="featured">
								<strong>Featured:</strong>
								Certain sections of the site organize information into featured and non-featured.
							</label>
						</li>
					</ul>
	
					<div class="one">
						<p class="field">
							<label for="featured_from"><abbr title='"Featured from" indicates the first date on which a page will be featured.'>Featured From: </abbr></label>
							<input type="text" id="featured_from" name="featured_from" class="js-gen-settings" value="<?=$featured_from?>" />
						</p>
					</div>
					<div class="two">
						<p class="field">
							<label for="featured_until"><abbr title='"Featured until" indicates the last date on which a page will be featured.'>Featured Until: </abbr></label>
							<input type="text" id="featured_until" name="featured_until" class="js-gen-settings" value="<?=$featured_until?>" />
						</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	
		<p><button class="btn-save-prop">Save Settings</button></p>
	<?php endif; ?>
	
	<div class="section-a">
		<h2 class="c">Publishing</h2>
		<div class="field-group-a">
			<ul class="settings-a">
				<li>
					<?=form_checkbox(array('name' => 'published','class' => 'js-gen-settings', 'checked' => (boolean)($published)))?>
					<label for="published">
						<strong>Published:</strong> Unpublished pages are saved as "My Drafts" on your "Who's Who" profile.
					</label>
				</li>
			</ul>
			<div class="field settings-a">
				<h3>Select the section(s) to publish your page to:</h3>
				<ul id="sections" class="sortable-list">
					<?php $other = array(); ?>
					<?php foreach($sections as $section): ?>
						<?php if ($section['permPublish'] == 0): ?>
							<?php if ($section['selected'] == 1): ?>
								<?php $other[] = array('title' => $section['title'], 'id' => $section['page_id']); ?>
							<?php endif; ?>
						<?php else: ?>
							<li id="s<?=$section['page_id']; ?>" class="<?=($section['selected'] == 1) ? 'ui-selected' : ''?>">
								<?=$section['title']?>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<?php if (count($other) > 0): ?>
					<p class="sections-other">This page is also published to the following sections: </p>
					<ul class="sections-other">
						<?php foreach($other as $section): ?>
							<li id="s<?=$section['id']; ?>"><?php echo $section['title']?>,&nbsp;</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<p><button class="btn-save-prop">Publish page</button></p>
	
	<?php if (isset($canProp) and $canProp): ?>
		<div class="section-a">
			<h2 class="c">Tags</h2>
			<div class="field-group-a">
				<div class="field">
					<ul class="desc styled-bulletlist">
						<li>Tags allow you to group pages together for search and discover-related pages.
						<li>Tags will auto complete from a list of previously used tags.
						<li>When you use new tags, those will appear on the autocomplete list the next time.
					</ul>
				</div>
				<p class="settings-a">
					<label for="tags">Add Tags: </label>
					<input id="tags" type="text" value="<?=$tags?>" />
				</p>
			</div>
		</div>
	
		<p><button class="btn-save-prop">Save Tags</button></p>
	<?php endif; ?>
	
	<?php $this->load->view('page_parts/permissions_'.val($page_type, 'page')); ?>
	
	<?php if (isset($canProp) and $canProp): ?>
		<div class="section-a">
			<h2 class="c">Revisions</h2>
			<table class="b">
				<thead>
					<tr>
						<th>Revision</th>
						<th>Date Created</th>
						<th>Created By</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($all_revs) and $all_revs): ?>
						<?php foreach($all_revs as $rev): ?>
							<tr>
								<td><?=$rev['revision_id']?></td>
								<td><?=$rev['date_created']?></td>
								<td><?=$rev['display_name']?></td>
								<td class="actions">
									<a class="revs-btn-view edit" title="View this version">View</a>
									<a class="revs-btn-revert move" title="Revert back to this revision">Revert</a>
									<a class="revs-btn-delete delete-b" title="Delete">Delete</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
<?php else: ?>

<?php /** if it IS a PAGE and not a section... */?>
<?php if (isset($canProp) and $canProp): ?>
		<div class="section-a">
			<h2 class="c">General Settings</h2>
			<div class="field-group-a">
				<ul class="settings-a">
					<li>
						<?=form_checkbox(
							array('name' => "allow_comments", 'class' => 'js-gen-settings', 'checked' => $p_allow_comments))?>
						<label for="allow_comments">
							<strong>Allow New Comments</strong> This allows other users to comment on this page.
						</label>
					</li>
				</ul>
	
				<?php if ($page_type !== 'section'): ?>
					<div class="field page-property">
						<p class="select-c templates">
							<label for="template_id"
								   title="Use this dropdown to change the page's template, please be careful when changing templates, some templates are not compatible with others.">
								Template:
							</label>
							<select name="template_id" id="template_id" class="js-gen-settings">
								<?php foreach($templates as $template):
									$selected = ($template['template_id'] == $template_id) ? 'selected="selected"' : ''; ?>
									<option value="<?=$template['template_id']."\"$selected"?>">
										<?=$template['template_title']?>
									</option>
								<?php endforeach; ?>
							</select>
						</p>
					</div>
				<?php endif; ?>
				<div class="field">
					<p class="one">
						<label for="date_published" title='"Date published" designates the date on which pages appear in dated lists (e.g. monthly, seasonal). It also determines the order in which pages will appear.'>
							Date Published:
						</label>
						<input type="text" id="date_published" name="date_published" class="js-gen-settings"
							   value="<?=date("Y-m-d", strtotime($date_published))?>" />
					</p>
					<p class="two">
						<label for="show_until"
							   title='"Display until" selects the last date by which the page will be shown in dated lists (e.g. monthly, seasonal).'>
							Display until:
						</label>
						<input type="text" id="show_until" name="show_until" class="js-gen-settings" value="<?=$show_until?>" />
					</p>
				</div>
				<p class="field">
					<label for="expiration_date"
						   title="Setting an expiration date will delete this page at the end of that day. Deleted pages are purged after 30 days!">
						Expiration Date:
					</label>
					<input type="text" id="expiration_date" name="expiration_date" class="js-gen-settings" value="<?=$expiration_date?>" />
				</p>
	
				<?php if ($page_type !== 'section'): ?>
					<h3 class="settings-a">Featured Article</h3>
					<ul class="settings-a">
						<li>
							<?=form_checkbox(array('name' => "featured", 'class' => 'js-gen-settings', 'checked' => (boolean)($featured)))?>
							<label for="featured">
								<strong>Featured:</strong>
								Certain sections of the site organize information into featured and non-featured.
							</label>
						</li>
					</ul>
	
					<div class="one">
						<p class="field">
							<label for="featured_from"><abbr title='"Featured from" indicates the first date on which a page will be featured.'>Featured From: </abbr></label>
							<input type="text" id="featured_from" name="featured_from" class="js-gen-settings" value="<?=$featured_from?>" />
						</p>
					</div>
					<div class="two">
						<p class="field">
							<label for="featured_until"><abbr title='"Featured until" indicates the last date on which a page will be featured.'>Featured Until: </abbr></label>
							<input type="text" id="featured_until" name="featured_until" class="js-gen-settings" value="<?=$featured_until?>" />
						</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	
		<p><button class="btn-save-prop">Save Settings</button></p>
	<?php endif; ?>
<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
	
//-->
</script>

	<div class="section-a">
		<h2 class="c">Publishing</h2>
		<div class="field-group-a">
			<ul class="settings-a">
				<li>
					<?=form_checkbox(array('name' => 'published','class' => 'js-gen-settings', 'checked' => (boolean)($published)))?>
					<label for="published">
						<strong>Published:</strong> Unpublished pages are saved as "My Drafts" on your "Who's Who" profile.
					</label>
				</li>
			</ul>
		
<script>
function setValues(one) {
alert(one);
  $("#loading-content").load("dataSearch.php?ID="+page+"&section="+section+"pubDate="+publishdate+"&expdate="+expdate, hideLoader);

}
</script>

			<?php $i=0?>

			<div class="field settings-a">
				<h3>Select the section(s) to publish your page to:</h3>
				<ul id="sections" class="sortable-list">
					<?php $other = array(); ?>
					<?php foreach($sections as $section): ?>
						<?php if ($section['permPublish'] == 0): ?>
							<?php if ($section['selected'] == 1): ?>
								<?php $other[] = array('title' => $section['title'], 'id' => $section['page_id']); ?>
											
								
									
							<?php endif; ?>
						<?php else: ?>
							<?php $uri = $_SERVER['REQUEST_URI'];?>
											<?php $id = substr($uri, strrpos($uri, '/') + 1);?>
											
							<li id="s<?=$section['page_id']; ?>" onclick="setValues(<?=($i)?>)" class="<?=($section['selected'] == 1) ? 'ui-selected' : ''?>">
								<?=$section['title']?>
								<?php echo 'Section#: '. $section['page_id']?>
											<?php $uri = $_SERVER['REQUEST_URI'];?>
											<?php $id = substr($uri, strrpos($uri, '/') + 1);?>
											<?php echo 'page ID: '. $id?>
											<?php echo "selected? : " . $section['selected']?>
										
											<?$i++?>
	
						</li>
									
											
									
									
									
						<a href="#" onclick="toggle_visibility('pubpageslist<?=($i)?>')"><h1>[ + / - ]</h1> Click to expand/collaspe</a></center>

						<div class="field" id="pubpageslist<?=($i)?>" style="display:none">
												<p class="one">
													<label for="date_published<?=($i)?>" title='"Date published" designates the date on which pages appear in dated lists (e.g. monthly, seasonal). It also determines the order in which pages will appear.'>
														Date Published:
													</label>
													<input type="date" id="date_published<?=($i)?>" name="date_published" class="js-gen-settings"
														   value="<?=date("Y-m-d", strtotime($date_published))?>" />
												</p>
						
						
												<p class="one">
													<label for="show_until<?=($i)?>"
														   title='"Display until" selects the last date by which the page will be shown in dated lists (e.g. monthly, seasonal).'>
														Display until:
													</label>
													<input type="date" id="show_until<?=($i)?>" name="show_until" class="js-gen-settings" value="<?=$show_until?>" />
												</p>
											
											<p class="one">
												<label for="expiration_date<?=($i)?>"
													   title="Setting an expiration date will delete this page at the end of that day. Deleted pages are purged after 30 days!">
													Expiration Date:
												</label>
												<input type="date" id="expiration_date<?=($i)?>" name="expiration_date" class="js-gen-settings" value="<?=$expiration_date?>" />
											</p>
						</DIV>				
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<?php if (count($other) > 0): ?>
					<p class="sections-other">This page is also published to the following sections: </p>
					<ul class="sections-other">
						<?php foreach($other as $section): ?>
							<li id="s<?=$section['id']; ?>"><?php echo $section['title']?>,&nbsp;</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<p><button class="btn-save-prop">Publish page</button></p>
	
	<?php if (isset($canProp) and $canProp): ?>
		<div class="section-a">
			<h2 class="c">Tags</h2>
			<div class="field-group-a">
				<div class="field">
					<ul class="desc styled-bulletlist">
						<li>Tags allow you to group pages together for search and discover-related pages.
						<li>Tags will auto complete from a list of previously used tags.
						<li>When you use new tags, those will appear on the autocomplete list the next time.
					</ul>
				</div>
				<p class="settings-a">
					<label for="tags">Add Tags: </label>
					<input id="tags" type="text" value="<?=$tags?>" />
				</p>
			</div>
		</div>
	
		<p><button class="btn-save-prop">Save Tags</button></p>
	<?php endif; ?>
	
	<?php $this->load->view('page_parts/permissions_'.val($page_type, 'page')); ?>
	
	<?php if (isset($canProp) and $canProp): ?>
		<div class="section-a">
			<h2 class="c">Revisions</h2>
			<table class="b">
				<thead>
					<tr>
						<th>Revision</th>
						<th>Date Created</th>
						<th>Created By</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($all_revs) and $all_revs): ?>
						<?php foreach($all_revs as $rev): ?>
							<tr>
								<td><?=$rev['revision_id']?></td>
								<td><?=$rev['date_created']?></td>
								<td><?=$rev['display_name']?></td>
								<td class="actions">
									<a class="revs-btn-view edit" title="View this version">View</a>
									<a class="revs-btn-revert move" title="Revert back to this revision">Revert</a>
									<a class="revs-btn-delete delete-b" title="Delete">Delete</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
<?php endif; ?> 