<?php
$template_name = val($template_name);
$page_id = val($page_id, 0);
?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="ie9 oldie" lang="en"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" xmlns="http://www.w3.org/1999/html"> <!--<![endif]-->
<!--meta http-equiv="X-UA-Compatible" content="IE=11; IE=10; IE=9; IE=8; IE=7; IE=EDGE; CHROME=1;" /-->

<meta http-equiv="X-UA-Compatible" content="IE=11; IE=10; IE=9; IE=8; IE=7; IE=EDGE; CHROME=1;"/>
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='-1'>
<meta http-equiv='pragma' content='no-cache'>

<head>

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

	<!--link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery-ui-1.9.2.custom.css" /-->
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery-ui-1.10.4.custom.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/screen.css?<?=FISHNET_VERSION?>" media="all" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/print.css?<?=FISHNET_VERSION?>" media="print" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/color_mods.css?<?=FISHNET_VERSION?>" media="all" />
	<?php if (file_exists(APPPATH."../public/assets/css/templates/$template_name.css")): ?>
		<link rel="stylesheet" href="<?=STATIC_URL?>css/templates/<?=$template_name?>.css?<?=FISHNET_VERSION?>" />
	<?php endif; ?>
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.fancybox.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.jScrollPane.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/guidely.css" />
	<link rel="stylesheet" href="<?=STATIC_URL?>css/lib/jquery.qtip.css" />

	<script type="text/javascript" src="<?=STATIC_URL?>js/shared/hp_news_withpic.js"></script>
	<!--script type="text/javascript" src="<?=STATIC_URL?>js/templates/sys_portal.js"></script-->
	<script type="text/javascript" src="<?=STATIC_URL?>js/templates/sys_portal.js?12.10.11"></script>
    <!--[if lt IE 9]>
		<script src="<?=STATIC_URL?>js/lib/html5shiv.js"></script>
    <![endif]-->
    
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga(
  'create', 'UA-6440773-8', 'auto');
  ga('send', 'pageview');

</script>
<style>

marquee{
	width: 626px;
	color: #9a1b1f;
	font-size: medium;
	position: absolute;
	right: 217px;	
}

marquee a{
	color: #1d5bb9;
    text-decoration: underline;
}

marquee a:hover{
	color: #1d5bb9;
	text-decoration: none;
	
}

</style>


    
</head>

<body>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KFR4PT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KFR4PT');</script>
<!-- End Google Tag Manager -->
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

		<marquee behavior="scroll" direction="left" scrollamount="5" loop="-1" onmouseout="this.start();" onmouseover="this.stop();"></marquee>
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
						<li role="menuitem"><?=anchor("departments/3471", "30th Anniversary")?></li>
						<li role="menuitem"><?=anchor("departments/109", "Accounting")?></li>
						<li role="menuitem" aria-labeledby="archlabel">
							<span id="archlabel">Architecture &amp; Facilities</span>
							<ul role="menu">
								<!--li role="menuitem"><?=anchor("departments/103", "Architecture, Planning & Design")?></li-->
								<li role="menuitem"><?=anchor("departments/107", "Facilities")?></li>
							</ul>
						</li>
						<li role="menuitem"><?=anchor("departments/5197", "Becoming")?></li>

                        <li role="menuitem" aria-labeledby="archlabel">
                            <span id="archlabel">Communications</span>
                            <ul role="menu">
                                <li role="menuitem"><?=anchor("departments/177", "Advertising")?></li>
                            </ul>
                        </li>

						<li role="menuitem"><?=anchor("departments/2919", "Design")?></li>
						<li role="menuitem"><?=anchor("departments/136", "Distribution Center")?></li>
						<li role="menuitem"><?=anchor("departments/117", "Information Technology")?></li>
						<li role="menuitem"><?=anchor("departments/4136", "Manufacturing")?></li>
						<li role="menuitem"><?=anchor("departments/4915", "Mentorship Hub")?></li>
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
								<li role="menuitem"><?=anchor("departments/5819", "Client Engagement")?></li>
								<li role="menuitem"><?=anchor("departments/186", "Customer Service")?></li>
                                <li role="menuitem"><?=anchor("departments/2881", "Global")?></li>
								<li role="menuitem"><?=anchor("departments/3419", "Retail LL&D + Product Education")
									?></li>
                                <li role="menuitem"><?=anchor("departments/119", "Visuals")?></li>
							</ul>
						</li>
						<li role="menuitem"><?=anchor("departments/4711", "Vision2020")?></li>
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
					 <!--li role="menuitem"--><!--?=anchor('login/changepassword', 'Change My Password')?--><!--/li-->
					 <li role="menuitem"><?=anchor('Https://password.eileenfisher.com', 'Change My Password')?></li>
					 <li role="menuitem"><?=anchor('logout', 'Logout')?></li>
				</ul>
			</li>
	                <li class="help" style="text-align: right; width: 100px;">
				   <a accesskey="l" style="background: #9a1b1f; width: 32px; text-align: right; margin-left: 50px; padding-bottom: 2px;"><font color="#ffffff">Help</font></a>
				    <ul id="helpUL" role="menu">
						<li role="menuitem"><?=anchor("about", "About fishNET")?></li>
						<li role="menuitem"><?=anchor("/article/tag/fishNET%20Tutorials/section/13","Tutorials")?></li>
						<!--li role="menuitem"><?=anchor("help", "Help")?></li-->
						<li role="menuitem"><?=anchor("feedback", "Feedback")?></li>
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
	</article><!-    - #content -->

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
	<!--h2 class="offset">Help bar</h2-->
	<div class="header">
		<ul class="footer-panels-nav">
			<!--li class="tutorials">
				<a href="#tutorials">Tutorials <span class="ui-icon ui-icon-whiteclose">close</span></a>
			</li-->
		</ul>
	</div>
	<div class="container">
	

		<div class="section" id="tutorials">
			<div class="video">
				<div id="tutVideo"></div>
			</div>

			<div class="tutorials-index">
				<!--h3 class="b">
					Tutorials<br/>
					(Click the double arrows on the bottom right corner of the video to make it fullscreen)
				</h3-->
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
		    'http://code.jquery.com/jquery-migrate-1.0.0.js',
		    'lib/jquery-ui-1.10.4.custom',
		   //'lib/jquery-ui-1.9.2.custom',
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

</body>

<!-- Enable this script when there is are active marquee items-->
<!--script type="text/javascript">
 
if (location.href.indexOf('reload')==-1)
{
   location.href=location.href+'#';
 
}
 
</script-->

</html>
