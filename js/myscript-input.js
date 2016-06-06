jQuery(document).ready(function($) {

// We check if a recognition has been done
	var auto_refresh = setInterval(
		function ()
		{
			//console.log('test');

			// Error management
			var RetourJson = $('#IframeMyScript').contents().find("#label").html();
			if(RetourJson!='') { // Something has been sent by server in label so it's an error
				
				//console.log(RetourJson);
				var donnees = jQuery.parseJSON(RetourJson);
				//console.log(donnees);
				if(donnees != null){
					if(donnees.type=='error'){
						var error = donnees.error.result.error;
						//QuotaException
						if(error == 'QuotaException'){
							$("#MyScriptError").html('Quota Exceeded: 200REQUEST/DAY limit reached ');
							$("#MyScriptError").removeClass('MyScriptHidden');
						}
						//InvalidApplicationKeyException
						else if(error=='InvalidApplicationKeyException'){
							$("#MyScriptError").html('Application key is wrong');
							$("#MyScriptError").removeClass('MyScriptHidden');
							
						}
						else{
							$("#MyScriptError").html('Error: '+error);
							$("#MyScriptError").removeClass('MyScriptHidden');
							
						}
					}
				clearTimeout(auto_refresh);
				}
			}
			
			// Success management
			var MyScriptResult = $('#IframeMyScript').contents().find("#MyScriptResult").html();
			if(MyScriptResult){
				if(MyScriptResult!=' '){ // If so we display the add button
					$(".MyScriptAdd").removeClass('MyScriptHidden');
				}
			}

	}, 1000); // Refresh every 1000 milliseconds

	function scroll_to_bottom(){
		$('#comment').scrollTop($('#comment')[0].scrollHeight);
	}
 	  	
	$( "#comment" ).click(function( event ) {
		$("#comment").removeClass('MyScriptTextarea');
		$("#IframeMyScript").addClass('MyScriptHidden');
		$(".MyScriptBar").addClass('MyScriptHidden');
		$(".MyScriptLoading").addClass('MyScriptHidden');
		$(".MyScriptAdd").addClass('MyScriptHidden');
		$("#MyScriptSignature").addClass('MyScriptHidden');
		$('.MyScriptInstructions').addClass('MyScriptHidden');
		$('.MyScriptHolder').addClass('MyScriptHeight');
	});
	
	$( "#comment" ).focusout(function( event ) {
		//scroll_to_bottom();
		$("#comment").addClass('MyScriptTextarea');
		$("#IframeMyScript").removeClass('MyScriptHidden');
		$(".MyScriptBar").removeClass('MyScriptHidden');
		$(".MyScriptLoading").removeClass('MyScriptHidden');
		$(".MyScriptAdd").addClass('MyScriptHidden');
		$("#MyScriptSignature").removeClass('MyScriptHidden');
		$('.MyScriptInstructions').removeClass('MyScriptHidden');
		$('.MyScriptHolder').removeClass('MyScriptHeight');
	});
	
    $(document).on("click", "#AddButton", function() {
		var MyScriptCandidate = $('#IframeMyScript').contents().find("#MyScriptResult").html();
		//alert(MyScriptCandidate);
		if(MyScriptCandidate!=' '){
			$('#comment').val($('#comment').val() + MyScriptCandidate + ' ');
			$(".MyScriptAdd").addClass('MyScriptHidden');
			scroll_to_bottom();
			//$('#IframeMyScript').contents().find("myscript-text-web").delete();
			var iframe = document.getElementById("IframeMyScript").contentWindow.location.href;
			$( '#IframeMyScript' ).attr( 'src', iframe);
		}	
    });
 
    $(document).on("click", "#MyScriptSelectedLanguage", function() {
		$('.MyScriptLanguages').toggle();
    });
 
    $(document).on("click", ".MyScriptInstructions", function() {
		$('.MyScriptInstructions').addClass('MyScriptHidden');
    });

	
    $(document).on("click", ".MyScriptLanguages img", function() {
		//We get the next language
		NextLanguage = $(this).attr('class');
		//We create the new iframe url
		OldUrl = $('#IframeMyScript').attr('src');
 		// We remove the previous language
		var OldLanguage = OldUrl.substr(OldUrl.lastIndexOf('=')+1);
 		// We create the new url
		var NewUrl = OldUrl.substring(0, OldUrl.indexOf('&language='))+'&language='+NextLanguage;
 		// We use the new url for the iframe
		$( '#IframeMyScript' ).attr( 'src', NewUrl);
		// We update the flag
        var NewPic = $('#MyScriptSelectedLanguage').attr("src").replace(OldLanguage, NextLanguage);	
		$('#MyScriptSelectedLanguage').attr("src", NewPic);
		// we hide the others
		$(".MyScriptAdd").addClass('MyScriptHidden');
		$('.MyScriptLanguages img').removeClass('MyScriptHidden');
		$('.'+ NextLanguage).addClass('MyScriptHidden');
		$('.MyScriptLanguages').toggle();
    });
	
});