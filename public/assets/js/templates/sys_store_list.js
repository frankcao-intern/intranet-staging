
require(['jquery','lib/jquery.dataTables'],function($){"use strict";var Engine={dataTable:function(){$('#stores').dataTable({"bJQueryUI":true,"sPaginationType":"full_numbers","iDisplayLength":10});}};$(document).ready(function(){Engine.dataTable();});});