/**
 * User: cravelo
 * Date: 9/23/13 1:27 PM
 */

/*jslint browser: true, white: true, plusplus: true */
/*global jQuery*/

jQuery(function($){
	'use strict';

	var Engine = {
		submit: function(){
			$("#submitCar").button();
			$("#resetCar").button();
		},
	/**	validate: function(){
			if(document.getElementById("name").value === ""){
				document.getElementById("name").innerHTML = "name cant be left blank";
				document.getElementById("name").style.color = "red";
				document.getElementById("name").style.display = "block";
			}

			else{
				document.getElementById("name").innerHTML = "";
				document.getElementById("name").style.display = "none";

			}

		}*/
		savedata: function(){
		var input = document.getElementById("name");
		sessionStorage.setItem("name", input.value);
		return true;
	}



};
	Engine.submit();
	Engine.savedata();
//	Engine.validate();

});


