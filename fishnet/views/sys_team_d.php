<?php
/**
 * Created By: emuentes
 * Date: May 5, 2011
 * Time: 10:00 AM
 */
?>

<link rel="stylesheet" href="<?php echo STATIC_URL; ?>css/templates/includes/sys_team_all.css" />
<section class="primary">
	<?php $this->load->view('page_parts/team_header'); ?>

	<div id="tweet-cols">
		<ul>
			<div>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_lincoln.jpg">
			<span><strong>Abraham Lincoln</strong>4score+7 mins ago</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_dotNet.jpg">
			<span><strong>Dot Net Mag</strong>5 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_edgar.jpg">
			<span><strong>Edgar Muentes</strong>6 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_thomas_jefferson.jpg">
			<span><strong>Thomas Jefferson</strong>6 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_fashion.gif">
			<span><strong>The Latest Fashion</strong>5 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_culture.jpg">
			<span><strong>Cutting Edge Culture</strong>5 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_notes.jpg">
			<span><strong>Other Notes</strong>5 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/lightbulb.png">
			<span><strong>Don't Forget</strong>4 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			</div>
			<div>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/lightbulb.png">
			<span><strong>Don't Forget</strong>4 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_notes.jpg">
			<span><strong>Other Notes</strong>5 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_culture.jpg">
			<span><strong>Cutting Edge Culture</strong>5 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_fashion.gif">
			<span><strong>The Latest Fashion</strong>5 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_thomas_jefferson.jpg">
			<span><strong>Thomas Jefferson</strong>6 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_edgar.jpg">
			<span><strong>Edgar Muentes</strong>6 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_dotNet.jpg">
			<span><strong>Dot Net Mag</strong>5 May</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			<li><img style="width:72px;" src="http://10.120.32.49/intranet/static/images/twitlogo_lincoln.jpg">
			<span><strong>Abraham Lincoln</strong>4score+7 mins ago</span>
			This is some plain old text in the left column ul li. The same line wraps to the 2nd line</li>
			</div>
		</ul>
		<div class="pagination"><a class="prev">Prev</a><span class="pager"></span><a class="next">Next</a></div>
	</div>

	<h2 class="b">About us</h2>
	<div class="edit-wysiwygadv team-desc clearfix" data-key="desc">
		<?php echo (isset($revision['revision_text']['desc']) and (!empty($revision['revision_text']['desc']))) ?
			$revision['revision_text']['desc'] : "Enter your team description here."; ?>
	</div>

	<?php $this->load->view('page_parts/team_tabs', array('tabs' => val($tabs, array()))); ?>

</section> <!--/ #primary -->

<?php $this->load->view('page_parts/team_rightcol');
