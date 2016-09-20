<?php
/**
 * Created by: cravelo
 * Date: 4/6/12
 * Time: 2:54 PM
 *
 */

/**
 * Modified by: Mosrur
 * Date: 9/7/2016
 * Time: 4:51 PM
 */

?>

<?php if (isset($canProp) and $canProp): ?>
	 <!-- form for updating the general page settings -->
        <div class="section-a">
            <?php echo validation_errors(); ?>
            <?php
                if(has_alert()):
                    show_alert();
                endif;
            ?>
            <h2 class="c">General Settings</h2>
            <div class="field-group-a">
                <ul class="settings-a">
                    <li>
                        <?php echo form_checkbox(array('name' => "allow_comments", 'class' => 'js-gen-settings', 'value' => $p_allow_comments, 'checked' => (boolean)($p_allow_comments)));?>
                        <label for="allow_comments">
                            <strong>Allow New Comments</strong> This allows other users to comment on this page.
                        </label>
                    </li>
                    <li>
                        <?php
                        echo form_checkbox(array('name' => 'published','class' => 'js-gen-settings', 'checked' => (boolean)($published)), $published);
                        ?>
                        <label for="published">
                            <strong>Published:</strong> Unpublished pages are saved as "My Drafts" on your "Who's Who" profile.
                        </label>
                    </li>
                </ul>



                <div class="field page-property">
                    <p class="select-c templates">
                        <label for="template_id"
                               title="Use this dropdown to change the page's template, please be careful when changing templates, some templates are not compatible with others.">
                            Template:
                        </label>

                        <?php
                            /* dropdown options */
                            $option = array ();

                            foreach($templates as $template):
                                $option[] = array (
                                    $template['template_id'] => $template['template_title'],
                                );
                            endforeach;

                            echo form_dropdown('template_id', $option, $template_id, 'class="js-gen-settings"');
                        ?>

                    </p>
                </div>
				<div class="field">
					<p class="one">
						<label for="date_published" title='"Date published" designates the date on which pages appear in dated lists (e.g. monthly, seasonal). It also determines the order in which pages will appear.'>
							Date Published:
						</label>
                        <?php
                            $date_publish_data = array (
                              'id' => 'date_published',
                              'name' => 'date_published',
                              'class' => 'js-gen-settings',
                            );
                            echo form_input($date_publish_data, date("Y-m-d", strtotime($date_published)));
                        ?>
					</p>
					<p class="two">
						<label for="show_until"
							   title='"Display until" selects the last date by which the page will be shown in dated lists (e.g. monthly, seasonal).'>
							Display until:
						</label>
                        <?php
                            $show_until_data = array (
                                'id' => 'show_until',
                                'name' => 'show_until',
                                'class' => 'js-gen-settings',
                            );

                            echo form_input($show_until_data, $show_until) ;
                        ?>
					</p>
				</div>
            </div>

		</div>
		<p><button class="btn-save-prop">Save Settings</button></p>

<?php endif; ?>


	<div class="section-a">
		<h2 class="c">Publishing</h2>
		<div class="field-group-a" >
			<div id="id="section_publishing">


			<?php $i=0; ?>

			<div class="field settings-a">
				<h3>Select the section(s) to publish your page to:</h3>
				<ul id="sections" class="sortable-list">

					<?php
                        $other = array(); //pr($sections);
					    foreach($sections as $section):

						    if ($section['permPublish'] == 0):
							    if ($section['selected'] == 1):
                                    $other[] = array('title' => $section['title'], 'id' => $section['page_id']);
							    endif;
                            else:

								$pubdate = get_page_section_details($pageID, $section['page_id'], 'date_published');
								$expdate = get_page_section_details($pageID, $section['page_id'], 'show_until');

                                $uri = $_SERVER['REQUEST_URI'];
								$id = substr($uri, strrpos($uri, '/') + 1);
                    ?>

							    <li id="s<?=$section['page_id']; ?>" onclick="setValues(<?=($i)?>, <?=($section['selected'])?>,<?=($id)?>)" class="<?=($section['selected'] == 1) ? 'ui-selected' : ''?>" style='width: 98%; padding-bottom: 0px; margin-bottom: 0px;'>
                                <?php
                                    // display the section title
                                    echo $section['title'];

                                    // definging page_id and section_page_id var
                                    $uri = $_SERVER['REQUEST_URI'];
                                    $id = substr($uri, strrpos($uri, '/') + 1);
                                    $sec_page_id = $section['page_id'];

                                    $i++;


                                ?>
						        </li>
						        <div class="field" id="pubpageslist<?php /*echo $i; */?>" style="border: 1px solid #D; border-top: none; padding: 10px;">

                                    <p class="one">
                                        <label for="publish[<?php echo $sec_page_id; ?>][date_published]" title='"Date published" designates the date on which pages appear in dated lists (e.g. monthly, seasonal). It also determines the order in which pages will appear.'>
                                            Date Published:
                                        </label>

                                        <?php
                                        $sec_date_published_data = array (
                                            'id' => 'date_published',
                                            'name' => $sec_page_id,
                                            'class' => 'js-date-published',
                                        );

                                        echo form_input($sec_date_published_data, $pubdate) ;
                                        ?>

                                    </p>


                                    <p class="one">
                                        <label for="publish[<?php echo $section['page_id']; ?>][show_until]"
                                               title='"Display until" selects the last date by which the page will be shown in dated lists (e.g. monthly, seasonal).'>
                                            Display until:
                                        </label>

                                        <?php
                                        $sec_show_until_data = array (
                                            'id' => 'show_until',
                                            'name' => $sec_page_id,
                                            'class' => 'js-show-until',
                                        );

                                        echo form_input($sec_show_until_data, $expdate) ;
                                        ?>
                                    </p>
                                </div>
                                <div class="clearfix">&nbsp;</div>

						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<?php if (count($other) > 0): ?>
                    <p class="sections-other">This page is also published to the following sections: </p>
					<ul class="sections-other">
						<?php foreach($other as $section): ?>
							<li id="s<?php echo $section['id']; ?>"><?php echo $section['title']?>,&nbsp;</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
		</div>
	</div>


	<p><button class="btn-save-sec" id="save">Publish page</button></p>

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
					<input id="tags" type="text" name="tags" value="<?=$tags?>" />
				</p>
			</div>
		</div>

		<p><button class="btn-save-tags">Save Tags</button></p>
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



