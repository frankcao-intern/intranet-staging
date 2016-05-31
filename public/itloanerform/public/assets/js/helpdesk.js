/**
 * Created by: cravelo
 * Date: 12/6/11
 * Time: 10:54 AM
 */

(function($) {
	var Engine = {
		autoComplete: function(){
			$("#uUserName").autocomplete({
				source: "public/getnames.php?SID=" + formEngine.SID + '&p=samaccountname',
				minLength: 2,
				select: function( event, ui ) {
					$("#uUserName").val(ui.item.label);
					$("#userEmail").val(ui.item.value);

					return false;
				}
			});

			$("#hpUserName").autocomplete({
				source: "public/getnames.php?SID=" + formEngine.SID + '&p=samaccountname',
				minLength: 2,
				select: function( event, ui ) {
					$("#hpUserName").val(ui.item.label);
					$("#hpEmail").val(ui.item.value);

					return false;
				}
			});
		},
		hpCollapse: function(){
			var $h1 = $("#helpdesk h1");

			$h1.prependTo('.maincont').html('+ &nbsp;' + $h1.text() + '&nbsp; +').css('cursor', 'pointer').click(function(){
				$("#helpdesk").slideToggle();
			});
			$("#helpdesk").css('paddingTop', '24px');
		},
		jobSave: function(){
			$("#btnJobNumber").click(function(){
				var urlData = 'a=savejob' +
					'&jobNumber=' + $("#jobNumber").val() +
					'&f=' + $('#requestSerial').val();
				
				$.post('public/hpproc.php', urlData, function(data){
					formEngine.displayError(data.strResult);
				}, 'json');
			});
		},
		signatureButtons: function(){
			$("#btnUserSign").click(function(){
				var urlData = 'SID=' + formEngine.SID +
					   '&user=' + $('#uUserName').val() +
					   '&passw=' + $('#userPassw').val() +
					   '&formID=' + 'hpForm';
				//alert(urlData);

				$.post('public/checkpassw.php', urlData, Engine.userSigned, 'json');
			});

			$("#btnHPSign").click(function(){
				if (
					($('#powerSupply').is(':checked') === $('#powerSupply1').is(':checked')) &&
					($('#netCable').is(':checked') === $('#netCable1').is(':checked')) &&
					($('#USBCable').is(':checked') === $('#USBCable1').is(':checked'))
				){
					var urlData = 'SID=' + formEngine.SID +
						   '&user=' + $('#hpUserName').val() +
						   '&passw=' + $('#hpPassw').val() +
						   '&formID=' + 'hpForm';
					//alert(urlData);

					$.post('public/checkpassw.php', urlData, Engine.hpSigned, 'json');
				}else{
					formEngine.displayError('The same equipment loaned should be returned.');
				}
			});
		},
		userSigned: function(data){
			var urlData;

			if (data.isError){
				formEngine.displayError(data.strResult);
			}else{
				urlData = 'a=usersign' +
					'&powerSupply=' + $("#powerSupply").is(':checked') +
					'&netCable=' + $("#netCable").is(':checked') +
					'&USBCable=' + $("#USBCable").is(':checked') +
					'&tested=' + $("#tested").is(':checked') +
					'&trained=' + $("#trained").is(':checked') +
					'&userEmail=' + $('#userEmail').val() +
					'&userSignDate=' + $('#userSignDate').val() +
					'&f=' + $('#requestSerial').val();

				$.post('public/hpproc.php', urlData, function(data){
					formEngine.displayError(data.strResult);
				}, 'json');
			}
		},
		hpSigned: function(data){
			var urlData;

			if (data.isError){
				formEngine.displayError(data.strResult);
			}else{
				urlData = 'a=hpsign' +
					'&powerSupply=' + $("#powerSupply1").is(':checked') +
					'&netCable=' + $("#netCable1").is(':checked') +
					'&USBCable=' + $("#USBCable1").is(':checked') +
					'&hpEmail=' + $('#hpEmail').val() +
					'&hpSignDate=' + $('#hpSignDate').val() +
					'&f=' + $('#requestSerial').val();

				$.post('public/hpproc.php', urlData, function(data){
					formEngine.displayError(data.strResult);
				}, 'json');
			}
		}
	};

	$(document).ready(function(){
		Engine.autoComplete();
		Engine.hpCollapse();
		Engine.jobSave();
		Engine.signatureButtons();
	});
}(jQuery));
