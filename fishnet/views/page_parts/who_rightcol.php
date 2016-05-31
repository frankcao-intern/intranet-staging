<?php
/**
 * Created by: cravelo
 * Date: 3/27/12
 * Time: 1:42 PM
 */

if ($this->uri->uri_string() === 'who'){
	$export_url = 'who/people/qkey/email/q/Kg..';
}else{
	$export_url = $this->uri->uri_string();
}
?>

<aside class="secondary">
	<div class="section-a">
		<h2 class="c">Documents</h2>
		<ul class="list-c">
			<li><?=anchor("$export_url/action/phonenumbers", "Export Phone List")?></li>
			<li><?=anchor("$export_url/action/export", "Export Extended Phone List")?></li>
			<li><a href="/documents/phone_list.pdf">Other Phone Numbers</a></li>
		</ul>
	</div>

    <div class="section-a">
		<h2 class="c collapsible collapsible-closed">How to search</h2>
		<ul class="list-a list-a-b">
			<li>
                Type in the search box the word, phrase or number you are looking for and press Go or the enter key.
			</li>
			<li>
                The search looks into the first and last names, departments, locations, phones/extensions, faxes,
				titles and job descriptions (About My Work).
			</li>
			<li>
                By typing the first two letters of a first or last name, a list of names that matches your search will
				appear. Selecting a name from this list will take you to their profile page.
			</li>
			<li>
                Searching for general keywords like "business planning" or "fishnet" will generate a photo list of
				profile pages of people who have those keywords in their "About My Work" section.
			</li>
			<BR/>
			<h2 class="c">Store Search</h2>
            <li>
                Type the name of a store in the search box. To search by store number, type "ef" followed by the
                store number in 3 digits with leading zeroes. e.g. ef005, ef038, etc.
            </li>
		</ul>
	</div>
</aside> <!--/ #secondary -->
