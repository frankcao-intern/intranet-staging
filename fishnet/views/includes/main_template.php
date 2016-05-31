<!doctype html public "☀☀☀ ☃ ♨♨♨">
<!--[if lt IE 7]> <html class="ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<!-- Always force latest IE rendering engine & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$title?></title>
	
	<meta charset="utf-8" />
	<meta name="keywords" content="fishnet, eileen fisher, intranet, communication, information, documents, videos,
		people" />
	<meta name="description"
	      content="FISHNET is our common entry point for sharing information internally throughout EILEEN FISHER.
	        FISHNET is our Intranet at EILEEN FISHER, and it provides news and features, access to helpful resources,
	        and areas of collaboration. As you'll see, the site is highly visual and interactive."/>

	<link rel="Shortcut Icon" href="<?=STATIC_URL?>images/icon11.ico" type="image/x-icon" />

	<!--Fonts-->
	<link rel='stylesheet' href="<?=STATIC_URL?>fonts/bergamostd/stylesheet.css" />
	<link rel='stylesheet' href="<?=STATIC_URL?>fonts/texgyreheros/stylesheet.css" />
	<!--Fonts-->

	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery-ui-1.8.16.custom.min.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/screen.css?<?=FISHNET_VERSION?>" media="screen" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/print.css?<?=FISHNET_VERSION?>" media="print" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/<?php echo $template_name; ?>.css?<?=FISHNET_VERSION?>" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.fancybox.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.fancybox-thumbs.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.jScrollPane.css" />

	<script src="<?=STATIC_URL?>js/lib/jquery-1.6.4.js"></script>
	<!--[if lt IE 9]>
		<script type="text/javascript" src="<?=STATIC_URL?>js/lib/html5.js"></script>
	<![endif]-->

	<script type="text/javascript">
		var	siteRoot	= "<?=base_url()  ?>",
			pageID		= '<?=$page_id	  ?>',
			environment	= '<?=ENVIRONMENT ?>';

		/*
		* All CSS is written in a way that with JS disabled the pages should look reasonably well
		* This js class in the document element ensures that the css that applies to a JS enabled page
		*  doesn't get applied when JS is disabled
		* */
		document.documentElement.className += " js";
	</script>
</head>
<body>

<!--New page dialog -->
<div id="newPageDialog" style="display: none" title="Create a new page">
	<div class="field">
		<label for="npdCat">Select a category of templates:</label>
		<p class="select-c">
			<select id="npdCat"></select>
		</p>
	</div>

	<?=form_open('articles/newpage', 'id="newPageForm"')?>
		<input type="hidden" id="tname" name="tname" />

		<div class="field">
			<label>Select a template for the new page:</label>
		</div>
		<div id="npdTemplDiv" class="scroll-pane">
			<ul id="npdTempl"></ul>
		</div>

		<div id="npdOptions">
			<div class="field">
				<input type="checkbox" id="npdPrivate" name="private" />
				<label for="npdPrivate">Will this page be private?</label>
			</div>
			<!--div class="field">
				<label for="npdShowUntil">Show Until: </label>
				<input type="text" id="npdShowUntil" name="show_until" value="<?php echo date('Y-m-d'); ?>" />
			</div-->
		</div>
	<?=form_close()?>
</div>
<!--New page dialog -->
<!-- status update -->
<div id="WRYDoingDiag" title="What are you up to?" style="display: none">
	<form action="#" method="post" class="status">
		<p>
			<label for="status" class="offset">Status:</label>
			<textarea rows="5" cols="30" id="status" name="status" placeholder="Type your status here."></textarea>
		</p>
	</form>
</div>
<!-- status update -->
<!-- generic message -->
<p id="msg" style="display: none"><?php
	echo $this->session->userdata('message');
	$this->session->unset_userdata('message');
?></p>
<!-- generic message -->

<?php flush(); //send the above to the browser to start the download of CSS, etc while we process the rest ?>

<div id="root">
	<header role="banner">
		<h1 id="logo" title="Home Page">
			<?php
				$logo = $this->session->userdata('logo');
				if ($logo){
					$logo = STATIC_URL.'images/'.$logo;
				}else{
					$logo = STATIC_URL.'images/logo-gradient.png';
				}
			?>
			<a href="<?php echo site_url("/home"); ?>" accesskey="h">
				<img src="<?php echo $logo; ?>" height="64" width="122" alt="fishNET" />
			</a>
		</h1>
		<ul id="skip-links">
			<li><a href="#nav" accesskey="n">Skip to navigation [n]</a></li>
			<li><a href="#content" accesskey="c">Skip to content [c]</a></li>
			<li><a href="#footer" accesskey="f">Skip to footer [f]</a></li>
		</ul>

		<?php echo form_open('search', 'class="search search-a"'); ?>
			<p>
				<?php
					if (!isset($section_id)){
						$breadcrumbs = json_decode($this->session->userdata('breadcrumbs'), true);
						$section_id = $breadcrumbs[0]['section_id'];
					}

					echo form_hidden('section_id', $section_id);
					echo form_input('query', '', 'id="query" placeholder="Type to search"');
					$data = array(
						'value' => 'Go',
						'type' => 'submit',
						'content' => 'Go'
					);

					echo form_button($data);
				?>
			</p>
		<?php echo form_close(); ?>

		<h2 class="offset">Navigation</h2>
		<ul id="nav" class="nav" role="navigation">
			<li>
				<a accesskey="1" href="#">Teams</a><em> [1]</em>
				<div class="dropdown dropdown-a">
					<ul class="list-b">
						<li><?=anchor("departments/109", "Accounting")?></li>
						<li>
							Architecture &amp; Facilities
							<ul>
								<!--li><?=anchor("departments/103", "Architecture, Planning & Design")?></li-->
								<li><?=anchor("departments/107", "Facilities")?></li>
							</ul>
						</li>
						<li><?=anchor("departments/117", "Information Technology")?></li>
						<li>
							People &amp; Culture
							<ul>
								<li><?=anchor("departments/126", "Human Resources")?></li>
								<li><?=anchor("departments/100", "Internal Communications")?></li>
								<li><?=anchor("departments/112", "Leadership, Learning & Development")?></li>
								<!--li><?=anchor("departments/145", "Social Consciousness")?></li>
								<li><?=anchor("departments/140", "Wellness")?></li-->
							</ul>
						</li>
						<li>
							Retail Management
							<ul>
								<li><?=anchor("departments/119", "Visuals")?></li>
							</ul>
						</li>

					</ul>
				</div>
			</li>
			<li>
				<a accesskey="2" href="#">News</a><em> [2]</em>
				<div class="dropdown">
					<ul class="list-b">
						<li><?php echo anchor("news", "Company News"); ?></li>
						<li><?php echo anchor("btl", "Behind the Label"); ?></li>
						<li><?php echo anchor("ads", "Advertising and Editorial Placement"); ?></li>
						<li><?php echo anchor("video", "Channel EF"); ?></li>
						<li><?php echo anchor("journal", "EF Journal"); ?></li>
					</ul>
				</div>
			</li>
			<?php $who_link = $this->session->userdata('who_link');	?>
			<li><?php echo anchor($who_link, "Who's Who", "accesskey='3'"); ?><em> [3]</em></li>
			<li><?php echo anchor('efpublic', "The Doc Spot", "accesskey='4'"); ?><em> [4]</em></li>
			<!--li>
				<a href="#" accesskey="4">The Doc Spot</a><em> [4]</em>
				<div class="dropdown">
					<ul class="list-b">
						<li><?php echo anchor("efpublic", "Documents"); ?></li>
						<li><?php echo anchor("travel", "Travel"); ?></li>
					</ul>
				</div>
			</li-->
			<li><?php echo anchor("bulletinboard", "Bulletin Board", "accesskey='5'"); ?><em> [5]</em></li>
		</ul> <!--/ #nav -->

		<ul id="top-nav">
			<li class="user">
				<a>Hi, <em><?=$this->session->userdata("firstname")?></em></a>
				<ul id="userMenu">
					<li><?php echo anchor("profiles/".$this->session->userdata("user_id"), "View My Profile"); ?></li>
					<li><a href="javascript:coreEngine.newPage()">Create New Page</a></li>
					<li><a href="javascript:coreEngine.newEvent()">Create New Event</a></li>
					<?php if (isset ($canWrite) and $canWrite): ?>
						<?php if ($template_name == 'sys_event'): //special case for the calendar ?>
							<li><?php echo anchor("edit/$page_id", 'Edit this page'); ?></li>
						<?php else: ?>
							<li><?php echo anchor("edit/$page_id", 'Edit this page'); ?></li>
						<?php endif; ?>
					<?php endif; ?>
					<?php if (isset($canProp) and $canProp): ?>
						<?php if ($template_name == 'sys_event'): //special case for the calendar ?>
							<li><a href="javascript:coreEngine.eventProperties()">Event Properties</a></li>
						<?php else: ?>
							<li><a href="javascript:coreEngine.pageProperties()">Page Properties</a></li>
						<?php endif; ?>
					<?php endif; ?>

					<?php if (isset($canDelete) and $canDelete): //you cant delete system pages. ?>
						<?php if ($template_name == 'sys_event'): //special case for the calendar ?>
							<li><a href="javascript:coreEngine.deleteEvent()">Delete this event</a></li>
						<?php elseif($page_type == 'page'): ?>
							<li><a href="javascript:coreEngine.deletePage()">Delete this page</a></li>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ($template_name == 'sys_profile'): //special case action for people's profiles ?>
						<li><a href="javascript:coreEngine.addContact()">Send Contact to Outlook</a></li>
					<?php endif; ?>
					<?php if ($template_name == 'sys_event'): //special case action for events ?>
						<!--li><a href="javascript:coreEngine.addContact()">Send Event to Outlook</a></li-->
					<?php endif; ?>
					<li><?php echo anchor('login/changepassword', 'Change My Password'); ?></li>
					<li><?php echo anchor('logout', 'Logout'); ?></li>
				</ul>
			</li>
			<li><a href="#" id="WRYDoing">What are you up to?</a></li>
			<li class="links">
				<a>My Links</a>
				<ul id="myLinksUL">
					<li><a href="javascript:coreEngine.addFavorite()">Bookmark current page</a></li>
					<li><?php echo anchor("/calendar", "Company Calendar"); ?></li>
					<?php $my_links = $this->favorites->get($this->session->userdata('user_id')); ?>
					<?php foreach($my_links as $link): ?>
						<li><a href="<?php echo $link['url']; ?>"><?php echo $link['title'] ?></a></li>
					<?php endforeach; ?>
				</ul>
			</li>
		</ul>
	</header>
	<article id="content" role="main">
		<h2 class="offset">Main Template Content</h2>
		<!-- Main Template -->
		<?php if (($edit == 'edit') and isset($canWrite) and $canWrite): ?>
			<section id="edit" class="primary">
				<?php if (file_exists(APPPATH."/views/$template_name.edit.php")): ?>
					<?php $this->load->view("$template_name.edit.php"); ?>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<?php $this->load->view($template_name); ?>
		<!-- Main Template END -->

		<?php if ($edit != 'edit'): ?>
			<!-- Comments -->
			<?php if (isset($comments)) { $this->load->view('includes/comments'); } ?>
			<!-- Comments END -->
		<?php endif; ?>
	</article><!-- #content -->

	<footer id="footer" role="navigation">
		<p>
			<?php echo anchor("about", "About fishNET") ?>
			&nbsp;|&nbsp;
			<a href="http://www.eileenfisher.com/EileenFisherCompany/CompanyGeneralContentPages/WhoWeAre/Our_Mission.jsp" rel="external">Our Mission</a>
			&nbsp;|&nbsp;
			<?php echo anchor("feedback", "Feedback") ?>
			&nbsp;|&nbsp;
			<?php echo anchor("sitemap", "Site Map") ?>
			&nbsp;|&nbsp;
			<a href="http://www.eileenfisher.com/EileenFisherCompany/CompanyGeneralContentPages/Careers.jsp" rel="external">Careers</a>
			&nbsp;|&nbsp;
			<?php echo anchor("privacypolicy", "Privacy Policy") ?>
			&nbsp;|&nbsp;
			<?php echo anchor("tos", "Terms of Use") ?>
			&nbsp;|&nbsp;
			<a href="http://www.eileenfisher.com" rel="external">EILEENFISHER.COM</a>
		</p>
		<p class="copyright">Copyright &copy; <?php echo date("Y")?> EILEEN FISHER Inc.</p>
	</footer>
</div><!-- #root -->

<div class="footer-panels footer-tabs">
	<div class="header">
		<ul class="footer-panels-nav">
			<li class="tutorials">
				<a href="#tutorials">Tutorials <span class="ui-icon ui-icon-whiteclose">close</span></a>
			</li>
			<!--li class="about"><a href="#about">About FISHNET</a></li>
			<li class="faq"><a href="#faq">FAQ</a></li-->
		</ul>
	</div>
	<div class="container">
		<div class="section" id="about">
			<div class="content">
				<h3 class="b">About FISHNET</h3>
				<p>lorem ipsum</p>

				<p>Text goes here</p>
			</div>
		</div><!--/ .section -->

		<div class="section" id="faq">
			<div class="content">
				<h3 class="b">FAQ</h3>
				<p>Frequently Asked Questions</p>

				<p>Text goes here</p>
			</div>
		</div><!--/ .section -->

		<div class="section" id="tutorials">
			<div class="video">
				<div id="tutVideo"></div>
			</div>

			<div class="tutorials-index">
				<h3 class="b">
					Tutorials<br/>
					(Click the double arrows on the bottom right corner of the video to make it fullscreen)
				</h3>
				<ul class="first">
					<li>
						<h3 class="d">Home Page Series</h3>
						<ul class="list-c">
							<li><a href="
								<?=STATIC_URL?>videos/c/c4/c49/c497/c4971969eaeaa92d68b16acee31dde2d69981dfb.mp4">
									Top-navigation Features (3:46)
								</a></li>
							<li><a href="
								<?=STATIC_URL?>videos/4/4f/4f1/4f1c/4f1cef6996eb6e0ffdb293b79231d5ef68792668.mp4">
									Right Hand Column (2:23)
								</a></li>
							<li><a href="
								<?=STATIC_URL?>videos/3/3d/3d0/3d00/3d0002e74e949297417015eb9123cafefffb84eb.mp4">
									Four Primary Buckets (4:30)
								</a></li>
							<li><a href="
								<?=STATIC_URL?>videos/7/7b/7b7/7b78/7b7892e07db1a7ce6a8e3235e6d38e769f0cfc10.mp4">
									Bottom-navigation Features (2:26)
								</a></li>
						</ul>
					</li>
				</ul>

				<ul>
					<li>
						<h3 class="d">Top-navigation Series</h3>
						<ul class="list-c">
							<li><a href="
								<?=STATIC_URL?>videos/9/92/92d/92d1/92d1df98fb237df27c4a9e1c7b46d402822dfcb7.mp4">
									Who’s Who Search Features: Part A (4:07)
								</a></li>
							<li><a href="
								<?=STATIC_URL?>videos/0/06/06a/06ae/06ae5c36f60e8820a74aeb6e232b09e045866f3f.mp4">
									Who’s Who Search Features: Part B (3:45)
								</a></li>
							<li><a href="
								<?=STATIC_URL?>videos/4/4d/4d0/4d07/4d07b47891fa4129df78b9543e73246ba61ca986.mp4">
									Who’s Who Profile (5:57)
								</a></li>
							<li><a href="
								<?=STATIC_URL?>videos/9/97/97c/97ce/97ced8502048a1b85ceb0d94d99073a1f07231aa.mp4">
									Bulletin Board: Part A (2:22)
								</a></li>
							<li><a href="
								<?=STATIC_URL?>videos/d/df/df4/df48/df483736d6c2a8ec054ea450b60551c22e764373.mp4">
									Bulletin Board: Part B (4:00)
								</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div> <!--/ .footer-panels -->

<script	src="<?=STATIC_URL?>js/cufon.js"></script>
<script>Cufon.now();</script>

<script	defer src="<?=STATIC_URL?>js/scripts.php?<?=FISHNET_VERSION?>"></script>
<script defer src="<?=STATIC_URL?>js/templates/<?php echo $template_name; ?>.js?<?=FISHNET_VERSION?>"></script>

<?php if (($edit == 'edit') and isset($canWrite) and $canWrite): ?>
	<?php $this->load->view('includes/edit_mode'); ?>
<?php endif; ?>

<?php if (isset($comments) and ($edit != 'edit')): ?>
	<script defer src="<?=STATIC_URL?>js/lib/tinymce/tiny_mce.js"></script>
	<script defer src="<?=STATIC_URL?>js/comments.js?<?=FISHNET_VERSION?>"></script>
<?php endif; ?>

<?php if(ENVIRONMENT == 'production'): ?>
	<!-- Piwik -->
		<script type="text/javascript">
			var pkBaseURL = (("https:" == document.location.protocol) ? "https://fishnet.eileenfisher.net/piwik/" : "http://fishnet.eileenfisher.net/piwik/");
			document.write(unescape("%3Cscript defer src='" + pkBaseURL + "piwik.js' " +
					"type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script defer type="text/javascript">
			try {
				var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
				piwikTracker.trackPageView();
				piwikTracker.enableLinkTracking();
			} catch( err ) {}
		</script>
		<noscript><p><img src="http://fishnet.eileenfisher.net/piwik/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
	<!-- End Piwik Tracking Code -->
<?php endif; ?>

<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
   chromium.org/developers/how-tos/chrome-frame-getting-started -->
<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
	<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
<![endif]-->
</body>
</html>
