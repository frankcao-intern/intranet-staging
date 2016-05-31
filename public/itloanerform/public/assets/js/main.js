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
			$("#needDate, #returnDate").datepicker({
				showButtonPanel: true
			});
		},
		formReset: function(){
			$("#btnReset").click(function(){
				var bClear = confirm("Are you sure you want to clear all the data in the form? (It can't be recovered)"),
					objForm = $('#form');

				if (bClear){
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
				source: "public/getnames.php?SID=" + formEngine.SID + '&p=displayname',
				minLength: 2,
				select: function( event, ui ) {
					$("#userName").val(ui.item.label);
					$("#reqEmail").val(ui.item.value);

					return false;
				}
			});

			$("#mUserName").autocomplete({
				source: "public/getnames.php?SID=" + formEngine.SID + '&p=displayname',
				minLength: 2,
				select: function( event, ui ) {
					$("#mUserName").val(ui.item.label);
					$("#mUserEmail").val(ui.item.value);

					return false;
				}
			});
		},
		formValidation: function(){
			$('#form').bind('submit', function(){
				var reqTextFields = $('#userName').add('#reqEmail').add("#needDate").add('#returnDate')
						.add('#mUserEmail').add('#mUserName'),
					reqSelectFields = $('#account'),
					reqLists = $(),
					$this = $(this),
					bValid = true,
					AllFields, urlData, $option;

				if (formEngine.mgrReview){
					reqTextFields.add($('#mPassw'));
				}

				reqTextFields = reqTextFields.add('#hReason').add('#uReason');

				$('#equipment input[type=checkbox]')
						.closest('label')
						.removeClass('ui-state-error');
				if ($('#equipment input[type=checkbox]:checked').length === 0){
					$('#equipment input[type=checkbox]').closest('label').addClass('ui-state-error');
					bValid = false;
				}
				
				$('#usage input[type=checkbox]')
						.closest('label')
						.removeClass('ui-state-error');
				if ($('#usage input[type=checkbox]:checked').length === 0){
					$('#usage input[type=checkbox]').closest('label').addClass('ui-state-error');
					bValid = false;
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

				if (formEngine.mgrReview){ //this function will check the mgr's password and then send the form.
					urlData = 'SID=' + formEngine.SID +
							   '&user=' + $('#mUser').val() +
							   '&passw=' + $('#mPassw').val() +
							   '&formID=' + $this.attr('id');
					//alert(urlData);
					$.post('public/checkpassw.php', urlData, Engine.checkPassword, 'json');
					
					return false;
				}else{
					$option = $('#account option:selected');
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
		}
	};

	$(document).ready(function(){
		Engine.errorDiag();
		Engine.datepickers();
		$("input[type=button], input[type=submit]").button();
		$('.section-open').click(formEngine.sectionOpeners);
		Engine.formReset();
		Engine.autoComplete();
		Engine.formValidation();
	});
}(jQuery));
