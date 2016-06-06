jQuery(document).ready(function($) {

	function SecondSelection(){
		if($( "#language_code_second" ).val()==''){
			$( "#third_language_line").css('display','none');
			$( "#fourth_language_line").css('display','none');
		}
		else{
			$( "#third_language_line").css('display','block');
			ThirdSelection();
		}
	}
	function ThirdSelection(){
		if($( "#language_code_third" ).val()==''){
			$( "#fourth_language_line").css('display','none');
		}
		else{
			$( "#fourth_language_line").css('display','block');
		}
	}
	
	SecondSelection();
	ThirdSelection();
	
	$( "#language_code_second" ).change(function( event ) {
		SecondSelection();
	});
	
	$( "#language_code_third" ).change(function( event ) {
		ThirdSelection();
	});
	
});
