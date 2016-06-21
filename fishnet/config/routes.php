<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "login";
$route['404_override'] = 'staticpages/show_404';

/* CMS Routes */

//Login, etc
$route['logout'] = 'login/logout';
$route['changepassword'] = 'login/changepassword';

//static pages
$route['staticpages/(:any)'] = "staticpages/index/$1";//About FISHNET

//Home page
$route['home'] = "article/index/1"; //home page

$route['home_test'] ="article/index/3518"; // dev redesigned home page
//$route['home_test'] ="article/index/5530"; // PROD redesigned home page

//Articles
$route['article/(:num)'] = "article/index/$1";//route to access pages
$route['article/(:num)/(:any)'] = "article/index/$1";//route to access pages and pass parameters
$route['video/(:num)'] = "article/index/$1"; //video pages
$route['video/(:num)/(:any)'] = "article/index/$1"; //video pages with params

//Properties
$route['properties/(:num)'] = "properties/load/$1";//page properties

//Monthly digest
$route['monthly/(:num)/(\d?\d)/(\d\d\d\d)/(comments_count|page_views)'] = "monthly/load/$1/$2/$3/$4";
$route['monthly/(:num)/(\d?\d)/(\d\d\d\d)'] = "monthly/load/$1/$2/$3";//monthly/id/month/year
$route['monthly/(:num)'] = "monthly/load/$1";//monthly/id

//Journals
$route['journal/(:num)/(:num)/(comments_count|page_views)'] = "journal/load/$1/$2/$3";
$route['journal/(:num)/(:num)'] = "journal/load/$1/$2";
$route['journal/(:num)'] = "journal/load/$1";

//Seasonal
$route['seasonal/(:num)/(Spring|Fall|Resort)/(\d\d\d\d)/(comments_count|page_views)'] = "seasonal/load/$1/$2/$3/$4";
$route['seasonal/(:num)/(Spring|Fall|Resort)/(\d\d\d\d)'] = "seasonal/load/$1/$2/$3";
$route['seasonal/(:num)'] = "seasonal/load/$1";

//Calendars
$route['calendar/json/(:num)'] = "calendar/json/$1"; //json for the calendar
$route['calendar/rss/(:num)']  = "calendar/rss/$1"; //rss feed for events
$route['calendar/(:num)'] = "article/index/$1";

//Events
$route['event/(:num)/layout/(:any)'] = "calendar/loadevent/$1/$2";//events
$route['event/(:num)'] = "calendar/loadevent/$1";//events
$route['eventprops/(:num)'] = "calendar/eventprops/$1";//event properties

//People directory
$route['who/department/(:num)'] = "who/department/$1";//who's who departments by id
$route['who/department/(:num)/(:any)'] = "who/department/$1";//who's who departments by id
$route['who/department/(:any)'] = "who/department_name/$1";//who's who departments by name
$route['profiles/(:num)'] = "who/profile_id/$1";
$route['profiles/(:any)'] = "who/profile_username/$1";
$route['my/profile'] = "who/profile_id";
$route['edit/my/profile'] = "who/profile_id/null/edit";
$route['images/profile/(:any)'] = 'images/preview';

//Edit Mode
$route['edit/(:num)'] = "article/edit/$1";//edit a page
$route['edit/event/(:num)'] = "calendar/editevent/$1";//edit an event
$route['edit/profile/(:num)'] = "who/profile_id/$1/edit";//edit a who's who profile

//Search Results'
$route['search/(:any)'] = "search/index";
$route['search'] = "search/index";

//CRUD
$route['crud/(:any)'] = "crud/load/$1";
$route['crud/save'] = "crud/save";
$route['crud/insert'] = "crud/insert";
$route['crud/delete'] = "crud/crud_delete";
$route['crud'] = "crud/load";

/* fishNET Routes */

//convenience routes for static pages
$route['about'] = "staticpages/index/about";//About FISHNET
$route['about/(:any)'] = "staticpages/index/about/$1";//About FISHNET
$route['tos'] = "staticpages/index/tos";//Terms of Service
$route['privacypolicy'] = "staticpages/index/privacypolicy";//Privacy policy
$route['sitemap'] = "staticpages/index/sitemap";//Sitemap
$route['feedback'] = "staticpages/index/feedback";//Feedback form
$route['pgreen'] = "staticpages/index/pgreen";//practically green bridge page.
$route['tutorials'] = "staticpages/index/tutorials"; //Tutorials Help Page
$route['help'] = "staticpages/index/help"; //Help Page
$route['test'] = "staticpages/index/test"; // test page for redirecting to machforms from doc spot
$route['eileenblog_about'] = "staticpages/index/eileenblog_about";


//plural articles for backwards compat
$route['articles/(:num)'] = "article/index/$1";//route to access pages
$route['articles/(:num)/(:any)'] = "article/index/$1";//route to access pages and pass parameters

//Channel EF
$route['video'] = "article/index/13";

//Documents
$route['efpublic'] = "article/index/12"; //The Doc Spot
$route['efpublic/(:num)'] = "article/index/12/$1"; //The Doc Spot

//News
$route['news/2/(\d?\d)/(\d\d\d\d)/(comments_count|page_views)'] = "monthly/load/2/$1/$2/$3";
$route['news/2/(\d?\d)/(\d\d\d\d)'] = "monthly/load/2/$1/$2"; //monthly/id/month/year
$route['news/2'] = "monthly/load/2";
$route['news'] = "monthly/load/2";

//Company Calendar
$route['calendar/rss']  = "calendar/rss/".SID_CALENDAR;
$route['calendar'] = "article/index/".SID_CALENDAR;

//Who's who
$route['who'] = "article/index/".SID_WHOS_WHO;//who's who page

//Retail News
$route['retail/'.SID_RETAIL.'/(\d?\d)/(\d\d\d\d)/(comments_count|page_views)'] = "monthly/load/".SID_RETAIL."/$1/$2/$3";
$route['retail/'.SID_RETAIL.'/(\d?\d)/(\d\d\d\d)'] = "monthly/load/".SID_RETAIL."/$1/$2";//monthly/id/month/year
$route['retail/'.SID_RETAIL] = "monthly/load/".SID_RETAIL;
$route['retail'] = "monthly/load/".SID_RETAIL;

//Placements/Ads
$route['ads/(Spring|Fall|Resort)/(\d\d\d\d)'] = "seasonal/adsandplacements/14/$1/$2";
$route['ads'] = "seasonal/adsandplacements/14";

//Behind the Label
$route['btl/6/(Spring|Fall|Resort)/(\d\d\d\d)/(comments_count|page_views)'] = "seasonal/load/6/$1/$2/$3";
$route['btl/6/(Spring|Fall|Resort)/(\d\d\d\d)'] = "seasonal/load/6/$1/$2";
$route['btl/6'] = "seasonal/load/6";
$route['btl'] = "seasonal/load/6";

//EF Journal
$route['efjournal/10/(:num)/(comments_count|page_views)'] = "journal/load/10/$1/$2";
$route['efjournal/10/(:num)'] = "journal/load/10/$1";
$route['efjournal/10'] = "journal/load/10";
$route['efjournal'] = "journal/load/10";

//Team pages
$route['departments/(:num)'] = "article/index/$1";
$route['department/(:num)'] = "article/index/$1";

//EF Photos
$route['photos'] = 'article/index/'.SID_PHOTOS;

//Travel Section
$route['travel'] = 'article/index/7';

//30 Anniversary page
$route['heartatthecenter'] = 'article/index/4621';

//company priorities
$route['priorities'] = 'article/index/'.SID_PRIORITIES;
$route['prioritiesfaq'] = 'article/index/'.SID_PRIORITIES_FAQ;

//attendance app
$route['daysout/approve/(:num)'] = 'addon/daysout/load/$1';
$route['attendance/month/(:num)'] = 'addon/attendance/month/$1';
$route['attendance/admin'] = 'addon/attendance/admin';
$route['attendance/(:any)'] = 'addon/attendance/index/$1';
$route['attendance'] = 'addon/attendance';
$route['test2'] = 'staticpages/index/test2';
$route['datePublish'] = 'staticpages/index/datePublish';
$route['getDates'] = 'staticpages/index/getDates';

$route['ajaxtest'] = 'staticpages/index/ajaxtest';


/* End of file routes.php */
/* Location: ./system/application/config/routes.php */

