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
 
 
<?php
include '../../config/database.php';

echo 'Article: ' . htmlspecialchars($_GET["article"]);
echo 'Section: ' . htmlspecialchars($_GET["section"]);
//echo 'Publish Date: ' . htmlspecialchars($_GET["pubDate"]);
//echo 'Exp Date: ' . htmlspecialchars($_GET["expDate"]);
$this->db->select('pubDate');
$this->db->select('expDate');
$this->db->where('article', htmlspecialchars($_GET["article"]));
$this->db->where('section', htmlspecialchars($_GET["section"]));

$q = $this->db->get('pubdates');


//if id is unique we want just one row to be returned
$data = array_shift($q->result_array());

echo("The published date is: " . $data['pubDate'] . "\n");
echo("The exp date is: " . $data['expDate']);



?>

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
				
				</p>
	
				
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
function setValues(indexID, isPublished, articleID) {



if(isPublished===0) // it's not saved as published but was just clicked and should be
{
	//alert("the item you have selected wasn't selected previously.");
}
	//alert('indexID is: '.concat(indexID));
	//alert(document.getElementById('date_published'.concat(indexID)).value);
	//alert('Second indexID is: '.concat(indexID));
	//alert(document.getElementById('expiration_date'.concat(indexID)).value);
	//alert('Third indexID is: '.concat(indexID));

// 1.	
// .Load does not seem to execute 755 datePublish.php at all *************
//$("#updateDiv").Load("datePublish", showLoader); //?articleID=1&section=5000&pubDate=1/1/2016&expDate=1/1/2017", hideLoader);
// **********************************************************************

// 2.
// HREF method causes 'are you sure you want to close this page' popup
//window.location.href="http://fishnet.eileenfisher.net:8080/datePublish";
// **********************************************************************

//3.
// Write method causes a new page to load with the 0x0 image
//document.open();
//document.write('<img src="http://fishnet.eileenfisher.net:8080/datePublish">');
//document.close();
// **********************************************************************

//4.
// Standalone Img tag works with DB but leaves behind a small box on screen and I can't figure out how to generate on the fly properly.
//

// 5.
// window.location.replace also caused pop-up


}


</script>
<div class="field" id="updateDiv">

</DIV>
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
											
							<li id="s<?=$section['page_id']; ?>" onclick="setValues(<?=($i)?>, <?=($section['selected'])?>,<?=($id)?>)" class="<?=($section['selected'] == 1) ? 'ui-selected' : ''?>">
								<?=$section['title']?>
											
											
											<?php $uri = $_SERVER['REQUEST_URI'];?>
											<?php $id = substr($uri, strrpos($uri, '/') + 1);?>
											
											
											
											<?$i++?>
	
						</li>
									
											
									
									
									
						<a href="#" onclick="toggle_visibility('pubpageslist<?=($i)?>')"><h1>[ + / - ]</h1> Click to expand/collaspe</a></center>

						<div class="field" id="pubpageslist<?=($i)?>" style="display:none">
						
										<?php
											//include '../../config/database.php';

											//echo 'Article: ' . htmlspecialchars($_GET["article"]);
											//echo 'Section: ' . htmlspecialchars($_GET["section"]);
											//echo 'Publish Date: ' . htmlspecialchars($_GET["pubDate"]);
											//echo 'Exp Date: ' . htmlspecialchars($_GET["expDate"]);
											$this->db->select('pubDate');
											$this->db->select('expDate');
											$this->db->where('article', htmlspecialchars($id));
											$this->db->where('section', htmlspecialchars($section['page_id']));

											$q = $this->db->get('pubdates');


											//if id is unique we want just one row to be returned
											$data = array_shift($q->result_array());

											echo("The published date is: " . $data['pubDate'] . "\n");
											echo("The exp date is: " . $data['expDate']);



										?>
						
												<div id="dateContainer<?=($id)?>">
												<p class="one">
													<label for="date_published<?=($section['page_id'])?>" title='"Date published" designates the date on which pages appear in dated lists (e.g. monthly, seasonal). It also determines the order in which pages will appear.'>
														Date Published:
													</label>
													<input type="date" id="date_published<?=($section['page_id'])?>" name="date_published<?=($section['page_id'])?>" class="js-gen-settings"
														   value="<?=$data['pubDate']?>" />
												</p>
												</div>
						
												<p class="one">
													<label for="show_until<?=($section['page_id'])?>"
														   title='"Display until" selects the last date by which the page will be shown in dated lists (e.g. monthly, seasonal).'>
														Display until:
													</label>
													<input type="date" id="show_until<?=($section['page_id'])?>" name="show_until<?=($section['page_id'])?>" class="js-gen-settings" value="<?=$data['expDate']?>" />
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

	<p><button class="btn-save-prop" id="save">Publish page</button></p>
	
		
		
	
	<?php if(isset($_POST['show_until'])): ?>
	<script>
	alert("Show until has a POST value");
	</script>
	<?php endif; ?>
	
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
