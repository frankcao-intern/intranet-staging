<?php
/**
 * User: cravelo
 * Date: Nov 17, 2010
 * Time: 10:37:49 AM
 */
?>
<section class="primary">
	<div class="header-a">
		<p><?=anchor("/home", "&#x25c4;&nbsp;Home Page")?></p>
		<h2>Sitemap</h2>
	</div>

	<p class="tip">Click on the links below to access the respective sections.</p>

	<div class="columns triple-b triple-b-b">
		<div class="col section-a">
			<h2 class="c">Main</h2>
			<ul class="list-a">
				<li><?=anchor("/", "Homepage")?></li>
				<li><?=anchor("retail", "Retail News")?></li>
				<li><?=anchor("news", "Company News")?></li>
				<li><?=anchor("btl", "Behind the Label")?></li>
				<li><?=anchor("ads", "Advertising and Editorial Placement")?></li>
				<li><?=anchor("video", "EF Videos")?></li>
				<li><?=anchor("journal", "EF Journal")?></li>
				<li><?=anchor("who", "Who's Who")?></li>
				<li><?=anchor("efpublic", "The Doc Spot")?></li>
				<li><?=anchor("bulletinboard", "Bulletin Board")?></li>
			</ul>
		</div>
		<div class="col section-a">
			<h2 class="c">Homepage</h2>
			<ul class="list-a">
			<li><?=anchor("news", "Company News")?></li>
			<li><?=anchor("article/5657", "Purpose + Strategy")?></li>
			<li><?=anchor("departments/4711","VISION2020")?></li>
			<li><?=anchor("article/3484","Opportunity Tank")?></li>
			<li><?=anchor("article/5901","B Corp")?></li>
			<li><?=anchor("http://eileenfisherlearninglab.com","The Learning Lab")?></li>
			<li><?=anchor("video","EF Videos")?></li>
			<li><?=anchor("btl","Behind the Label")?></li>
			<li><?=anchor("calendar", "Company Calendar")?></li>
			<li><?=anchor("photos", "EF Snapshots")?></li>
			<li><?=anchor("ads","Ads & Placements")?></li>
				<li><?=anchor("about", "About fishNET")?></li>
				<li><?=anchor("feedback", "Feedback")?></li>
				<li><?=anchor("sitemap", "Site Map")?></li>
				<li><?=anchor("http://www.eileenfisher.com/EileenFisherCompany/CompanyGeneralContentPages/Careers.jsp",
				              "Careers")?></li>
				<li><?=anchor("privacypolicy", "Privacy Policy")?></li>
				<li><?=anchor("tos", "Terms of Service")?></li>
				<li><?=anchor("http://www.eileenfisher.com", "EILEENFISHER.com")?></li>
			</ul>
		</div>
		<div class="col section-a">
			<h2 class="c">Help</h2>
			<ul class="list-a">
				<li><?=anchor("about","About fishNET")?></li>
				<li><?=anchor("article/tag/fishNET%20Tutorials/section/13","Tutorials")?></li>
				<li><?=anchor("feedback","Feedback")?></li>
			</ul>
		</div>
	</div>

	<div class="columns triple-b triple-b-b">
		<div class="col section-a">
			<h2 class="c">Who's who</h2>
			<ul class="list-a">
				<li><?=anchor("who", "Landing Page")?></li>
				<li><?=anchor('my/profile', "My Profile")?></li>
			</ul>
		</div>
		
		<div class="col section-a">
			<h2 class="c">The Doc Spot</h2>
			<ul class="list-a">
				<li><?=anchor("efpublic", "Forms & Documents by Category")?></li>
				<li><?=anchor("efpublic/1", "All Forms & Documents")?></li>
			</ul>
		</div>
		<div class="col section-a">
			<h2 class="c">Bulletin Boards</h2>
			<ul class="list-a">
				<li><?=anchor("bulletinboard/viewforum.php?f=28", "Fishlist")?></li>
				<li><?=anchor("bulletinboard/viewforum.php?f=23", "Eats")?></li>
				<li><?=anchor("bulletinboard/viewforum.php?f=27", "Geeks and Gadgets")?></li>
				<li><?=anchor("bulletinboard/viewforum.php?f=20", "Traveled there? Traveling there?")?></li>
				<li><?=anchor("bulletinboard/viewforum.php?f=25", "Hollistic Network")?></li>
				<li><?=anchor("bulletinboard/viewforum.php?f=13", "In the World")?></li>
				<li><?=anchor("bulletinboard/viewforum.php?f=21", "Movies, TV, Books, Music & more")?></li>
				<li><?=anchor("bulletinboard/viewforum.php?f=18", "The Sporting Life")?></li>
				<li><?=anchor("bulletinboard/viewforum.php?f=31", "Trends")?></li>
				<li><?=anchor("bulletinboard/viewforum.php?f=30", "I look cute in EF today")?></li>
				<li><?=anchor("bulletinboard/viewforum.php?f=6", "Your Photos Here")?></li>
			</ul>
		</div>
		
		
	</div>

	<div class="columns section-a triple-b triple-b-b">
		<h2 class="c">Teams</h2>

		<div class="columns triple-b">
            <ul class="col list-b js-who-departments">
		<li role="meunitem"><?=anchor("departments/3471","30th Anniversary")?></li>
                <li role="menuitem"><?=anchor("departments/109", "Accounting")?></li>
                <li role="menuitem" aria-labeledby="archlabel">
                    <span id="archlabel">Architecture &amp; Facilities</span>
                    <ul role="menu">
                        <!--li role="menuitem"><?=anchor("departments/103", "Architecture, Planning & Design")?></li-->
                        <li role="menuitem"><?=anchor("departments/107", "Facilities")?></li>
                    </ul>
                </li>
		<li role="menuitem"><?=anchor("departments/5197", "Becoming")?></li>
		<li role="menuitem" aria-labelby="commlabel">
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
            <ul class="col list-b js-who-departments">
                <li role="menuitem" aria-labeledby="retailLabel">
                    <span id="retailLabel">Retail</span>
                    <ul role="menu">
			<li role="menuitem"><?=anchor("departments/5819", "Client Engagement")?></li>
	                <li role="menuitem"><?=anchor("departments/186", "Customer Service")?></li>
	                <li role="menuitem"><?=anchor("departments/2881", "Global")?></li>
			<li role="menuitem"><?=anchor("departments/3419", "Retail LL&D + Product Education")?></li>
                        <li role="menuitem"><?=anchor("departments/119", "Visuals")?></li>
                    </ul>
                </li>
		<li role="menuitem"><?=anchor("departments/4711","VISION2020")?></li>
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
	</div>
</section> <!--/ #primary -->
