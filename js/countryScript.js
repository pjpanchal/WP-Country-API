jQuery(document).ready(function($) {
 
	$(document).on('change', '#pj_country', function() {
		
		$('.cloader').show();
		var cid = $(this).val();
		var cName = $(this).find('option:selected').attr("name");
		$('#title').attr('value', cName);

	    $.ajax({
	        url: ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
	        data: {
	            'action': 'getCountryData',
	            'cid' : cid
	        },
	        success:function(data) {

				// This outputs the result of the ajax request
				data =  $.parseJSON(data);
				console.log(data.topLevelDomain);
				$('#pj_topLevelDomain').attr('value', data.topLevelDomain);
				$('#pj_alpha2Code').attr('value',data.alpha2Code);
				$('#pj_alpha3Code').attr('value',data.alpha3Code);
				$('#pj_callingCodes').attr('value',data.callingCodes);
				$('#pj_timezones').attr('value',data.timezones);
				$('#pj_currencies').attr('value',data.currencies);
				$('#pj_countryflag').attr('value',data.countryflag);
				$('#pj_publishingtime').attr('value',data.publishingtime);
				$('#pj_countryflag').attr('value',data.flag);
				$('#pj_countryflagImg').attr('src',data.flag);
				$('#pj_publishingtime').attr('value',data.pTime);

				$('.cloader').hide();
		        
	        },
	        error: function(errorThrown){
	            console.log(errorThrown);
	        }
	    });
	});  
              
});