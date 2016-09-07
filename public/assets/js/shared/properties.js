/**
 * User: cravelo
 * Date: Jul 28, 2010
 * Time: 3:28:53 PM
 *
 */

/*jslint browser: true, white: true */
/*global require */

require(['shared/page_properties'], function(page_properties){
	page_properties.setup();
	page_properties.saveProps();
	page_properties.tags();
	page_properties.permissions();
	page_properties.revisions();
});
