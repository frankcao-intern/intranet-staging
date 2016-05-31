<?php
$template_name = val($template_name);
$page_id = val($page_id, 0);
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="ie9 oldie" lang="en"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<meta charset="utf-8" />

	<title><?=val($title, 'Untitled page')?></title>

	<meta name="keywords" content="fishnet, eileen fisher, intranet, communication, information, documents, videos,
		people, collaboration, news, product information, social consciousness, sustainability, human resources,
		retail" />
	<meta name="description"
	      content="FishNET is our common entry point for sharing information internally throughout EILEEN FISHER.
	        fishNET is our Intranet at EILEEN FISHER, and it provides news and features, access to helpful resources,
	        and areas of collaboration. As you'll see, the site is highly visual and interactive."/>

	<link rel="Shortcut Icon" href="<?=STATIC_URL?>images/icon11.ico" type="image/x-icon" />

	<?php if (val($page_type, 'page') == 'section'): ?>
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?=site_url("/article/rss/$page_id")?>" />
	<?php endif; ?>

	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery-ui-1.8.23.custom.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/screen.css?<?=FISHNET_VERSION?>" media="all" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/print.css?<?=FISHNET_VERSION?>" media="print" />
	<?php if (file_exists(APPPATH."../public/assets/css/templates/$template_name.css")): ?>
		<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/<?=$template_name?>.css?<?=FISHNET_VERSION?>" />
	<?php endif; ?>
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.fancybox.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.jScrollPane.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/guidely.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.qtip.css" />
    <!--[if lt IE 9]>
		<script src="<?=STATIC_URL?>js/lib/html5shiv.js"></script>
    <![endif]-->
</head>
<body>

<!-- generic message -->
<p id="msg" style="display: none"><?=$this->session->userdata('message');?></p>
<?php $this->session->unset_userdata('message'); ?>
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
			<a href="<?=site_url("/home")?>" accesskey="h">
				<img src="<?=$logo?>" height="64" width="122" alt="FishNET Logo" />
				fishNET
			</a>
		</h1>
		<ul id="skip-links">
			<li><a href="#nav" accesskey="n">Skip to navigation [n]</a></li>
			<li><a href="#content" accesskey="c">Skip to content [c]</a></li>
			<li><a href="#footer" accesskey="f">Skip to footer [f]</a></li>
		</ul>

		<?=form_open('search', 'class="search search-a" role="search"')?>
			<p>
				<?php
					$breadcrumbs = json_decode($this->session->userdata('breadcrumbs'), true);
					$section_id = val($breadcrumbs['section_id'], 1);
					echo form_hidden('section_id', $section_id);
				?>
				<label class="offset" for="query">Search:</label>
				<input type="search" name="query" id="query" placeholder="Search fishNET" />
				<?=form_button(array('value' => 'Go', 'type' => 'submit', 'content' => 'Go'))?>
			</p>
		<?=form_close()?>

		<h2 class="offset">Navigation</h2>
		<ul id="nav" class="nav" role="navigation">
			<li>
				<a accesskey="1" href="#">Teams</a><em> [1]</em>
				<div class="dropdown dropdown-a">
					<ul class="list-b" role="menu">
						<li role="menuitem"><?=anchor("departments/109", "Accounting")?></li>
						<li role="menuitem" aria-labeledby="archlabel">
							<span id="archlabel">Architecture &amp; Facilities</span>
							<ul role="menu">
								<!--li role="menuitem"><?=anchor("departments/103", "Architecture, Planning & Design")?></li-->
								<li role="menuitem"><?=anchor("departments/107", "Facilities")?></li>
							</ul>
						</li>
						<li role="menuitem"><?=anchor("departments/136", "Distribution Center")?></li>
						<li role="menuitem"><?=anchor("departments/117", "Information Technology")?></li>
						<li role="menuitem" aria-labeledby="peopleLabel">
							<span id="peopleLabel">People &amp; Culture</span>
							<ul role="menu">
								<li role="menuitem"><?=anchor("departments/126", "Human Resources")?></li>
								<li role="menuitem"><?=anchor("departments/100", "Internal Communications")?></li>
								<li role="menuitem"><?=anchor("departments/112", "Leadership, Learning & Development")?></li>
								<li role="menuitem"><?=anchor("departments/145", "Social Consciousness")?></li>
								<li role="menuitem"><?=anchor("departments/140", "Wellness")?></li>
							</ul>
						</li>
						<li role="menuitem"><?=anchor("departments/189", "Product Development")?></li>
					</ul>
					<ul class="list-b" role="menu">
						<li role="menuitem" aria-labeledby="retailLabel">
							<span id="retailLabel">Retail</span>
							<ul role="menu">
								<!--li role="menuitem"--><?//=anchor("departments/186", "Customer Service")?><!--/li-->
								<li role="menuitem"><?=anchor("departments/119", "Visuals")?></li>
							</ul>
						</li>
						<li role="menuitem" aria-labeledby="saleslabel">
							<span id="saleslabel"><?=anchor("departments/154", "Wholesale Sales")?></span>
							<ul role="menu">
								<li role="menuitem"><?=anchor("departments/158", "Department Store Sales")?></li>
                                <li role="menuitem"><?=anchor("departments/160", "Operational Sales Support")?></li>
                                <li role="menuitem"><?=anchor("departments/162", "Specialty Store Sales")?></li>
                                <li role="menuitem"><?=anchor("departments/164", "Wholesale Merchandising")?></li>
                                <li role="menuitem"><?=anchor("departments/156", "Wholesale Planning & Operations")?></li>
							</ul>
						</li>
					</ul>
				</div>
			</li>
			<li><?=anchor('retail', "Retail", "accesskey='2'")?><em> [2]</em></li>
			<li>
				<a accesskey="3" href="#">News</a><em> [3]</em>
				<div class="dropdown">
					<ul class="list-b" role="menu">
						<li role="menuitem"><?=anchor("news", "Company News")?></li>
						<li role="menuitem"><?=anchor("btl", "Behind the Label")?></li>
						<li role="menuitem"><?=anchor("ads", "Advertising and Editorial Placement")?></li>
						<li role="menuitem"><?=anchor("video", "EF Videos")?></li>
						<li role="menuitem"><?=anchor("efjournal", "EF Journal")?></li>
					</ul>
				</div>
			</li>
			<?php $who_link = $this->session->userdata('who_link');	?>
			<li><?=anchor($who_link, "Who's Who", "accesskey='4'")?><em> [4]</em></li>
			<li><?=anchor('efpublic', "The Doc Spot", "accesskey='5'")?><em> [5]</em></li>
			<li><?=anchor("bulletinboard", "Bulletin Board", "accesskey='6'")?><em> [6]</em></li>
		</ul> <!--/ #nav -->

		<ul id="top-nav" role="navigation">
			<li class="user">
				<a accesskey="a">Hi,&nbsp;<em><?=$this->session->userdata("first_name")?></em></a>
				<ul id="userMenu" role="menu">
					<li role="menuitem"><?=anchor("profiles/".$this->session->userdata("user_id"), "View My Profile")?></li>
					<li role="menuitem"><a href="javascript:coreEngine.newPage()">Create New Page</a></li>
					<li role="menuitem"><?=anchor('calendar/neweventform', 'Create New Event')?></a></li>

					<?php if (isset ($canWrite) and $canWrite): ?>
						<li role="menuitem"><?=anchor("edit/$page_id", "Edit this $page_type")?></li>
					<?php endif; ?>

					<?php if ($page_type == 'event'): ?>
						 <li role="menuitem"><a href="javascript:coreEngine.eventProperties()">Publish Event</a></li>
					<?php elseif (($page_type == 'page') or ($page_type == 'section') or ($page_type == 'calendar')): ?>
						 <li role="menuitem"><?=anchor("properties/$page_id", ucfirst($page_type).' Properties')?></li>
					<?php endif; ?>

					<?php if (isset($canDelete) and $canDelete and isset($category) and ($category !== 'system')): ?>
						<?php if ($page_type == 'event'): ?>
							 <li role="menuitem"><a href="javascript:coreEngine.deleteEvent()">Delete this event</a></li>
						<?php else: ?>
							 <li role="menuitem">
								 <a href="javascript:coreEngine.deletePage()">Delete this <?=$page_type?></a>
							 </li>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ($template_name == 'sys_profile'): //special case action for people's profiles ?>
						 <li role="menuitem"><a href="javascript:coreEngine.addContact()">Send Contact to Outlook</a></li>
					<?php endif; ?>
					<?php if ($page_type == 'event'): //special case action for events ?>
						<!--li><a href="javascript:coreEngine.addContact()">Send Event to Outlook</a></li-->
					<?php endif; ?>
					 <li role="menuitem"><?=anchor('login/changepassword', 'Change My Password')?></li>
					 <li role="menuitem"><?=anchor('logout', 'Logout')?></li>
				</ul>
			</li>
			<li><a href="#" id="WRYDoing">What are you up to?</a></li>
			<li class="links">
				<a accesskey="l">&#x2605; My Links</a>
				<ul id="myLinksUL" role="menu">
					<li role="menuitem"><a href="javascript:coreEngine.addFavorite()">Bookmark current page</a></li>
					<li role="menuitem"><?=anchor("/calendar", "Company Calendar")?></li>
					<li role="menuitem"><?=anchor("/priorities", "Company Priorities")?></li>
					<?php $my_links = $this->favorites->get($this->session->userdata('user_id')); ?>
					<?php foreach($my_links as $link): ?>
						<li role="menuitem"><a href="<?=$link['url']?>"><?php echo $link['title'] ?></a></li>
					<?php endforeach; ?>
				</ul>
			</li>
		</ul>
	</header>

	<article id="content" role="main">
		<!--h2 class="offset">Main Template Content</h2-->
		<!-- Main Template -->
		<?php if (isset($edit) and isset($canWrite) and $canWrite): ?>
			<section id="edit" class="primary">
			<?php
				if (file_exists(APPPATH."views/$template_name.edit.php")){
					$this->load->view("$template_name.edit.php");
				}

				$this->load->view('page_parts/edit_mode');
			?>
			</section>
		<?php endif; ?>

		<?php if (file_exists(APPPATH."views/$template_name.php")){ $this->load->view($template_name); } ?>
		<!-- Main Template END -->
	</article><!-- #content -->

	<!-- Comments -->
	<?php
		$comments_loaded = false;
		if ((isset($comments) or val($allow_comments, false)) and !isset($edit)){
			$comments_loaded = true;
			$this->load->view('page_parts/comments');
		}
	?>
	<!-- Comments END -->

	<footer id="footer" role="contentinfo"><!--the id is for the skip anchors-->
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
		<p class="copyright">Copyright &copy; <?php echo date("Y")?> EILEEN FISHER Inc.</p>
	</footer>
</div><!-- #root -->

<section class="footer-panels footer-tabs">
	<h2 class="offset">Help bar</h2>
	<div class="header">
		<ul class="footer-panels-nav">
			<li class="about">
				<a href="#about">About fishNET <span class="ui-icon ui-icon-whiteclose">close</span></a>
			</li>
			<li class="tutorials">
				<a href="#tutorials">Tutorials <span class="ui-icon ui-icon-whiteclose">close</span></a>
			</li>
			<li class="help">
				<a href="#help">Help <span class="ui-icon ui-icon-whiteclose">close</span></a>
			</li>
		</ul>
	</div>
	<div class="container">
		<div class="section" id="about">
			<div class="content">
				<p>
					Welcome to fishNET, our common entry point for sharing information internally throughout EILEEN FISHER.
					fishNET is our Intranet at EILEEN FISHER, and it provides news and features, access to helpful resources,
					and areas of collaboration. For our SeCe Apparel-Canada stores and management team, we have developed a
					version of fishNET that allows them to view relevant content for these stores, as well as enhance their
					communication and connection with EILEEN FISHER.  As you'll see, the site is highly visual and interactive.
				</p>
				<p>The objectives of the site are:</p>
				<ul>
					<li>
						to enhance our company's culture of creativity, connection, inclusion and collaboration for all employees;
					</li>
					<li>
						provide a streamlined repository of information that will create new opportunities for sharing and
						transparency;
					</li>
					<li>
						simplify people's ability to access timely, relevant news and information that will empower our employees
						through knowledge;
					</li>
					<li>
						simplify certain processes to improve efficiencies (e.g., benefits enrollment, travel expense, employee
						clothing orders); and,
					</li>
					<li>deliver information in a visually exciting and entertaining way.</li>
				</ul>
				<p>
					fishNET is for you, and we encourage you to comment on any article published on fishNET, share photographs and
					tidbits with colleagues via the Bulletin Board, and tell our community more about yourself on your
					Who's Who page.
				</p>
				<p>
					Consider this a fun, easy way to read the latest happenings in the company, as well as learn more about each
					one of us.
				</p>
			</div>
		</div><!--/ .section -->

		<div class="section" id="help">
			<div class="content">
				<p>If you need help with fishNET we have several tools you can use</p>
				<ul class="styled-bulletlist">
					<li>Watch our video tutorials on this panel</li>
					<li>See the <a href="/about">Frequently Asked Questions</a> list</li>
					<li>Read the <a href="/documents/userguide.pdf">User Guide</a></li>
					<li>If you want to provide the fishNET team with feedback or report any issues you can do so
						using our <a href="/feedback">Feedback Form</a></li>
					<li>If you are having technical dificulties with fishNET please call the Helpdesk</li>
				</ul>
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
                                                                <?=STATIC_URL?>videos/4/46/461/4617/46171ab8cd97e4c21e1f2b17bf79e95ff5de26ff.mp4">
								Top-navigation Features (3:46)
							</a></li>
							<li><a href="
                                                                <?=STATIC_URL?>videos/4/47/474/4745/4745844002baa74b5497e2206195d3d9a5f265d4.mp4">
								Right Hand Column (2:23)
							</a></li>
							<li><a href="
                                                                <?=STATIC_URL?>videos/a/a0/a02/a02e/a02e3df7dc611897ecd22c51259a4be601a617fd.mp4">
								Four Primary Buckets (4:30)
							</a></li>
							<li><a href="
                                                                <?=STATIC_URL?>videos/a/a2/a2b/a2b4/a2b4c5ae484e8006a529d8f65a15a4239f303cf2.mp4">
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
                                                                <?=STATIC_URL?>videos/5/52/527/5273/527389c2624b8f7c4258fd3a4339becde62c9d05.mp4">
								Who's Who Search Features: Part A (4:07)
							</a></li>
							<li><a href="
                                                                <?=STATIC_URL?>videos/9/99/994/9942/99423d2fc56cc152d4bad317b477b9ab39a426b6.mp4">
								Who's Who Search Features: Part B (3:45)
							</a></li>
							<li><a href="
                                                                <?=STATIC_URL?>videos/f/f0/f09/f094/f09436a4a5c48775cf4c7a8d2bd86f4b7adc9b55.mp4">
								Who's Who Profile (5:57)
							</a></li>
							<li><a href="
                                                                <?=STATIC_URL?>videos/b/b2/b20/b206/b20620726e15e8f161d0b1970344c99373d77632.mp4">
								Bulletin Board: Part A (2:22)
							</a></li>
							<li><a href="
                                                                <?=STATIC_URL?>videos/1/10/106/1069/10694adc6dfbc4310924a256cbc8fdf0376a7265.mp4">
								Bulletin Board: Part B (4:00)
							</a></li>
						</ul>
					</li>
				</ul>
				<ul>
					<li>
						<h3 class="d">Creating Pages</h3>
						<ul class="list-c">
							<li><a href="
                                                                <?=STATIC_URL?>videos/9/92/92c/92c6/92c673b8c6f6455ca41c1503b5495e88f33783e2.mp4">
								Creating a new page (6:19)
							</a></li>
							<li><a href="
                                                                <?=STATIC_URL?>videos/7/7d/7db/7db4/7db487212201b648c52c8961c00c8e216610bd78.mp4">
								Adding related links and information (6:03)
							</a></li>
							<li><a href="
                                                                <?=STATIC_URL?>videos/1/18/180/180f/180fe25047cf973ee4ab7791f928c2c6da3c0b5f.mp4">
								Publishing and Permissions (6:46)
							</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</section> <!--/ .footer-panels -->

<!--New page dialog -->
<div id="newPageDialog" style="display: none" title="Create a new page">
	<div class="field">
		<label for="npdCat">Select a category of templates:</label>
		<p class="select-c">
			<select id="npdCat"></select>
		</p>
	</div>

	<?=form_open('article/create', 'id="newPageForm"')?>
	<input type="hidden" id="tname" name="tname" />

	<div class="field">
		<label>Select a template for the new page:</label>
	</div>
	<div id="npdTemplDiv" class="scroll-pane"><ul id="npdTempl"></ul></div>
	<script type="text/template" id="npdTemplates">
		<li class="templates {{tclass}}">
			<label for="tid{{id}}"><img src="{{img}}" alt="{{title}}" /><p>{{title}}</p></label>
			<input type="radio" name="tid" id="tid{{id}}" value="{{id}}" data-tname={{name}}>
		</li>
	</script>

	<div id="npdOptions">
		<div class="field">
			<input type="checkbox" id="npdPrivate" name="private" />
			<label for="npdPrivate">Will this page be private?</label>
			<input type="hidden" name="publish_to" value="" data-privateid="<?=SID_PRIVATE?>" />
		</div>
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

<script src="<?=STATIC_URL?>js/require-jquery.php?<?=FISHNET_VERSION?>"></script>
<script>
    document.documentElement.className += " js";

    //configure requirejs
    requirejs.config({
	    baseUrl: '<?=STATIC_URL?>js',
        urlArgs: '<?=FISHNET_VERSION?>'
    });

    //setup coreEngine globals from PHP
    var coreEngine = coreEngine || {};

    require(['jquery'], function($){
        $.extend(coreEngine, {
            pageID:         '<?=$page_id?>',
            environment:    '<?=ENVIRONMENT?>',
            csrf_token:     '<?=$this->security->get_csrf_hash()?>',
            version:        '<?=FISHNET_VERSION?>',
            siteRoot:       '<?=$this->config->item('site_url')?>',
            edit_mode:       <?=isset($edit) ? 'true' : 'false'?>
        });
    });

    //load everything
	require(['jquery', 'lib/jwplayer', 'lib/json2'], function(){
        //all jquery dependencies
        require([
	        'lib/jquery.easing.1.3',
		    'lib/jquery.cookie',
		    'lib/jquery.bgiframe',
		    'lib/jquery-ui-1.8.23.custom',
		    'lib/jquery.qtip',
		    'lib/jquery.message',
		    'lib/jquery.lazyload',
		    'lib/jquery.placeholder-2.0.7',
		    'lib/jquery.checkbox',
		    'lib/jquery.mousewheel-3.0.6.pack',
		    'lib/jquery.scrollpane',
            'lib/jquery.fancybox',
            'lib/guidely'
        ], function(){
            //fancybox-thumbs is a plugin to fancybox
	        //load main
            require(['lib/jquery.fancybox-thumbs', 'main'
                <?=$comments_loaded ? ", 'comments'" : ''?>
				<?=defined('EDIT_MODE') ? ", 'edit_mode'" : '' ?>
            ], function(){
                //template specific code
	            <?php if (file_exists(APPPATH."../public/assets/js/templates/$template_name.js")): ?>
                    require(['templates/<?=$template_name?>']);
                <?php endif; ?>
            });
        });
	});
</script>

<?php if(ENVIRONMENT == 'production'): ?>
	<!-- Piwik -->
	<script type="text/javascript" src="<?=site_url('piwik')?>/piwik.js"></script>
	<script type="text/javascript">
		try {
			var piwikTracker = Piwik.getTracker("<?=site_url('piwik').'/piwik.php'?>", 1);
			piwikTracker.trackPageView();
			piwikTracker.enableLinkTracking();
		} catch( err ) {
			alert(err);
		}
	</script>
	<noscript>
		<p><img src="<?=site_url('piwik')?>/piwik.php?idsite=1&rec=1&action_name=<?=val($title, "Untitled Page")?>"
				style="border: none;" alt="Visitor Tracking" /></p>
	</noscript>
	<!-- End Piwik Tracking Code -->
<?php endif; ?>

<!--[if lt IE 8]>
	<script type="text/javascript">
		var IE6UPDATE_OPTIONS = { icons_path: "<?=STATIC_URL?>images/" };
	</script>
	<script type="text/javascript" src="<?=STATIC_URL?>js/ie6update.js"></script>
<![endif]-->
</body>
</html>
