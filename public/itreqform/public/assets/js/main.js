/**
 * Created by: cravelo
 * Date: 11/29/11
 * Time: 3:26 PM
 */

(function($) {
	'use strict';

	var Engine = {
		errorDiag: function(){
			$("#errorDiag").dialog({
				bgiframe: true,
				modal: true,
				autoOpen: false,
				title: 'ERROR',
				buttons: {
					Ok: function() {
						$(this).dialog('close');
					}
				},
				open: function(event, ui){
					$("#errorDiagMsg").html($(this).dialog('option', 'strError'));
				}
			});
		},
		datepickers: function(){
			$("#needDate, #uDate").datepicker({
				showButtonPanel: true
			});
		},
		formReset: function(){
			$("#btnReset").click(function(){
				var bClear = confirm("Are you sure you want to clear all the data in the form? (It can't be recovered)"),
					objForm = $('#reqForm');

				if (bClear){
					//clean the list
					$("#forUsers").empty();
					$("#shareNamesList").empty();
					$("#actionList").empty();
					$(".userList").empty();

					//hide all the form parts
					$(".hidethis").hide();

					//then everything else
					$(':input', objForm).each(function(){
						var type = this.type,
							tag = this.tagName.toLowerCase(); //normalize case
						
						//reset the value attr of text inputs,password inputs, and textareas
						if (type === 'text' || type === 'password' || tag === 'textarea'){
							this.value = "";
						//checkboxes and radios need to have their checked state cleared but should *not* have their 'value' changed
						}else if (type === 'checkbox' || type === 'radio'){
							this.checked = false;
						//select elements need to have their 'selectedIndex' property set to -1 (this works for both single and multiple select elements)
						}else if (tag === 'select'){
							this.selectedIndex = 0;
						}
					});
				}
			});
		},
		autoComplete: function(){
			$("#userName").autocomplete({
				source: "public/getnames.php?p=displayname",
				minLength: 2,
				select: function( event, ui ) {
					$("#userName").val(ui.item.label);
					$("#reqEmail").val(ui.item.value);

					return false;
				}
			});

			$("#addText").autocomplete({
				source: "public/getnames.php?p=displayname",
				minLength: 2,
				select: function( event, ui ) {
					$("#addText").val(ui.item.label);

					return false;
				}
			});

			$("#mUserName").autocomplete({
				source: "public/getnames.php?&p=displayname",
				minLength: 2,
				select: function( event, ui ) {
					$("#mUserName").val(ui.item.label);
					$("#mUserEmail").val(ui.item.value);

					return false;
				}
			});

			$("#mUser").autocomplete({
				source: "public/getnames.php?p=samaccountname",
				minLength: 2,
				select: function( event, ui ) {
					$("#mUser").val(ui.item.label);
					$("#mUserEmail").val(ui.item.value);

					return false;
				}
			});

			$.get("public/listdistros.php", "selectID=.distroList", Engine.fillSelect, 'json');
		},
		buttons: function(){
			$('#btnAddUser').click(Engine.addUserToList);
			$('#btnDelUser').click(function(){
				var text = Engine.removeFromList.apply(this);

				if(text){
					$(".userList option").each(function(i, objOption){
						if (objOption.text === text){
							$(objOption).remove();
						}
					});
				}
			});
			$('#btnAddFolder').click(Engine.addAndClear);
			$('#btnDelFolder').click(Engine.removeFromList);
			$('#btnDelRequest').click(Engine.removeFromList);
		},
		distroLists: function(){
			$('#dlCreate').click(function(){//add request for creating a new distro list
				var strUsers = "",
					strAction;

				$('#userList1 :selected').each(function(i, objOption){
					strUsers += objOption.text + ",";
				});

				if (strUsers === ""){ alert('There are no employees selected.'); return false; }

				strAction = "Create a new distribution list with the following employees: " + strUsers +
						" and name it: " + $('#distroNewName').val();

				return Engine.addItemToSelect($('#actionList'), strAction, "", true);
			});

			$('#dlDelete').click(function(){//add request for deleting a distro list
				var strDistros = "",
					strAction;

				$('#distroList1 :selected').each(function(i, objOption){
					strDistros += objOption.text + ",";
				});

				if (strDistros === ""){ return false; }

				strAction = "Delete the distribution list(s) named: " + strDistros;

				return Engine.addItemToSelect($('#actionList'), strAction, "", true);
			});

			$('#dlAdd').click(function dlAddFunc(){
				var strDistros = "",
					strUsers = "",
					strAction;

				$('#distroList2 :selected').each(function(i, objOption){
					strDistros += objOption.text + ",";
				});

				$('#userList2 :selected').each(function(i, objOption){
					strUsers += objOption.text + ",";
				});

				if ((strDistros === "") || (strUsers === "")){
					alert('You have to select at least one employee and one distribution list');
					return false;
				}

				strAction = "Add " + strUsers + " to distro lists: " + strDistros;

				return Engine.addItemToSelect($('#actionList'), strAction, "", true);
			});

			$('#dlRemove').click(function(){
				var strDistros = "",
					strUsers = "",
					strAction;

				$('#distroList3 :selected').each(function(i, objOption){
					strDistros += objOption.text + ",";
				});

				$('#userList3 :selected').each(function(i, objOption){
					strUsers += objOption.text + ",";
				});

				if ((strDistros === "") || (strUsers === "")){
					alert('You have to select at least one employee and one distribution list');
					return false;
				}

				strAction = "Remove " + strUsers + " from the following distribution lists: " + strDistros;

				return Engine.addItemToSelect($('#actionList'), strAction, "", true);
			});
		},
		formValidation: function(){
			$('#reqForm').bind('submit', function(){ //TODO: make this more form independant somehow, revise new user type make it required
				var reqTextFields = $('#userName').add('#reqEmail').add("#needDate").add('#mUserEmail').add('#mUserName'),
					reqLists = $('#forUsers'),
					reqSelectFields = $('#account'),
					$this = $(this),
					bValid = true,
					forUsersArr = [],
					foldersArr = [],
					writeUsers = [],
					readUsers = [],
					actionsArr = [],
					webExUsersArr = [],
					AllFields, typeSelected, urlData, objSelect, index, r, $option;

				if (($('#forUsers option').length === 0) && ($('#userName').val() != "")){
					$('#forUsers').append("<option>"+ $('#userName').val() +"</option>");
				}

				if (formEngine.mgrReview){
					reqTextFields.add($('#mPassw'));
				}

				$('#reqTypes').addClass('ui-state-error');
				typeSelected = false;
				if ($("#t1").attr('checked')){ //new user
					typeSelected = true;
					reqTextFields = reqTextFields.add('#uName').add('#uTitle').add('#uDate');

					$('input[name=uHours]').closest('label').removeClass('ui-state-error');
					if ($('input[name=uHours]:checked').length === 0){
						$('input[name=uHours]').closest('label').addClass('ui-state-error');
						bValid = false;
					}
					
					$('#reqTypes').removeClass('ui-state-error');
				}
				if ($("#t2").attr('checked')){ //software
					typeSelected = true;
					reqTextFields = reqTextFields.add('#sReason');

					$('#newSoft input[type=checkbox]').closest('label').removeClass('ui-state-error');
					if ($('#newSoft input[type=checkbox]:checked').length === 0){
						$('#newSoft input[type=checkbox]').closest('label').addClass('ui-state-error');
						bValid = false;
					}

					$('#reqTypes').removeClass('ui-state-error');
				}
				if ($("#t3").attr('checked')){ //hardware
					typeSelected = true;
					reqTextFields = reqTextFields.add('#hReason');

					$('#newHardware input[type=checkbox], #newHardware input[type=radio]')
							.closest('label')
							.removeClass('ui-state-error');
					if (($('#newHardware input[type=checkbox]:checked').length === 0) ||
							($('#newHardware input[type=radio]:checked').length === 0)){
						$('#newHardware input[type=checkbox], #newHardware input[type=radio]').closest('label').addClass('ui-state-error');
						bValid = false;
					}

					$('#reqTypes').removeClass('ui-state-error');
				}
				if ($("#t4").attr('checked')){ //shared drives
					typeSelected = true;
					reqLists = reqLists.add('#shareNamesList');

					$('#readUsersList, #writeUsersList').removeClass('ui-state-error');
					if ($('#readUsersList option:selected').length !== 0){
						if ($('#writeUsersList option:selected').length === 0){
							r = confirm('There are no users selected to have write access to the shared drives listed. Is this what you want?');
							if (!r){
								$('#readUsersList, #writeUsersList').addClass('ui-state-error');
								bValid = false;
							}
						}
					}else{
						$('#readUsersList, #writeUsersList').addClass('ui-state-error');
						bValid = false;
					}

					$('#reqTypes').removeClass('ui-state-error');
				}
				if ($("#t5").attr('checked')){ //distro lists
					typeSelected = true;
					reqLists = reqLists.add('#actionList');
					$('#reqTypes').removeClass('ui-state-error');
				}
				if ($("#t6").attr('checked')){ //webex lists
					typeSelected = true;

					$('#webExUserList').removeClass('ui-state-error');
					if ($('#webExUserList option:selected').length === 0){
						$('#webExUserList').addClass('ui-state-error');
						bValid = false;
					}

					$('#reqTypes').removeClass('ui-state-error');
				}
				if (!typeSelected){
					alert("You must select at least one type of request.");
					return false;
				}

				AllFields = $([]).add(reqTextFields).add(reqLists).add(reqSelectFields);
				AllFields.removeClass('ui-state-error');

				reqTextFields.each(function(i, objInput){
					if ($(objInput).val() === ""){
						bValid = false;
						$(objInput).addClass("ui-state-error");
					}
				});
				reqSelectFields.each(function(i, objInput){
					if (objInput.selectedIndex === 0){
						bValid = false;
						$(objInput).addClass("ui-state-error");
					}
				});
				reqLists.each(function(i, objInput){
					if (objInput.options.length === 0){
						bValid = false;
						$(objInput).addClass("ui-state-error");
					}
				});
				if (!bValid){
					alert("Some required fields are not filled. Please recheck the form and fill out any fields marked with '*'.");
					return false;
				}

				//gather the lists
				$('#forUsers option').each(function(i, objOption){
					forUsersArr.push(objOption.text);
				});
				$('#forUsersStr').val(JSON.stringify(forUsersArr));
				//alert($('#forUsersStr').val());

				$('#shareNamesList option').each(function(i, objOption){
					foldersArr.push(objOption.text);
				});
				$('#folders').val(JSON.stringify(foldersArr));

				$('#writeUsersList option').each(function(i, objOption){
					if (objOption.selected){
						writeUsers.push(objOption.text);
					}
				});
				$('#writeUsers').val(JSON.stringify(writeUsers));

				$('#readUsersList option').each(function(i, objOption){
					if (objOption.selected){
						readUsers.push(objOption.text);
					}
				});
				$('#readUsers').val(JSON.stringify(readUsers));

				$('#actionList option').each(function(i, objOption){
					actionsArr.push(objOption.text);
				});
				$('#actions').val(JSON.stringify(actionsArr));

				$('#webExUserList option').each(function(i, objOption){
					if (objOption.selected){
						webExUsersArr.push(objOption.text);
					}
				});
				$('#webExUsers').val(JSON.stringify(webExUsersArr));

				if (formEngine.mgrReview){ //this function will check the mgr's password and then send the form.
					urlData = '&user=' + $('#mUser').val() +
							   '&passw=' + $('#mPassw').val() +
							   '&formID=' + $this.attr('id');
					//alert(urlData);
					$.post('public/checkpassw.php', urlData, Engine.checkPassword, 'json');
					
					return false;
				}else{
					$option = $('#account option:selected');
					$option.val($option.index());

					$option = $('#uLocation option:selected');
					$option.val($option.index());

					$option = $('#uType option:selected');
					$option.val($option.index());

					return true;
				}
			});
		},
		checkPassword: function(data){
			if (data.isError){
				formEngine.displayError(data.strResult);
			}else{
				$("#" + data.strResult).unbind('submit')[0].submit();
			}
		},
		removeFromList: function(){
			var $this = $(this),
				$select = $($this.data('select')),
				text;

			if ($select.attr('selectedIndex') != -1){
				text = $(":selected", $select).text();
				$(":selected", $select).remove();
				return text;
			}else{
				return false;
			}
		},
		addUserToList: function(){
			var $text = $($(this).data('text'));

			Engine.addItemToSelect($(".userList"), $text.val(), "", true);
			$text.val('');
		},
		addAndClear: function(){ // adds a new line to a select and then clears the input box
			var $this = $(this),
				$text = $($this.data('text')),
				$select = $($this.data('select'));

			var r = Engine.addItemToSelect($select, $text.val(), "", true);

			if (r){ $text.val(""); }

			return r;
		},
		fillSelect: function(data){
			var i, len;

			for (i = 0, len = data.length; i < len; i++){
				Engine.addItemToSelect($(data['selectID']), data[i], "", true);
			}
		},
		//it checks if doesnt exist first then adds it to the end.
		addItemToSelect: function(objSelect, text, value, duplicates) {
			//alert(text);
			duplicates = (typeof duplicates === 'undefined') ? false : duplicates; //default param

			if (text === "") return false;

			if (duplicates){
				if (Engine.selectContains(objSelect, text)){
					alert("ERROR: That is already on the list.");
					return false;
				}
			}

			objSelect.append("<option value='"+ value +"'>" + text + "</option>");

			return true;
		},
		selectContains: function(objSelect, text){
			var result = false;
			
			objSelect.find("option ").each(function(i, objOption){
				//alert(objOption.text + "\n" + text);
				if (objOption.text === text){ result = true; }
			});

			return result;
		},
		openSections: function(){
			formEngine.sectionOpeners.apply($("#t1")[0]);
			formEngine.sectionOpeners.apply($("#t2")[0]);
			formEngine.sectionOpeners.apply($("#t3")[0]);
			formEngine.sectionOpeners.apply($("#t4")[0]);
			formEngine.sectionOpeners.apply($("#t5")[0]);
			formEngine.sectionOpeners.apply($("#t6")[0]);
			formEngine.sectionOpeners.apply($("#hOther")[0]);
			formEngine.sectionOpeners.apply($("#sOther")[0]);
		}
	};

	$(document).ready(function(){
		Engine.errorDiag();
		Engine.datepickers();
		$("input[type=button], input[type=submit]").button();
		$('.section-open').click(formEngine.sectionOpeners);
		Engine.formReset();
		Engine.autoComplete();
		Engine.buttons();
		Engine.distroLists();
		Engine.formValidation();

		if (formEngine.mgrReview){
			Engine.openSections();
			formEngine.selectOptions();
		}
	});
}(jQuery));
