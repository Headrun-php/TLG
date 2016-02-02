function getCities(regionCode, targetSelectorId){

		$.ajax({
			  type: "POST",
			  url: ajaxUrl+"getCities",
			  dataType: 'json',
			  async: true,
			  data:{'id':regionCode, 'countryCode':"IN"},
			  success: function(response, textStatus, jqXHR)
			  {
				   $('#'+targetSelectorId).empty();
				   $('#'+targetSelectorId).append('<option value=""></option');
				   $.each(response, function (index, item) {
				         $('#'+targetSelectorId).append(
				              $('<option></option>').val(index).html(item)
				          );
				     });
			  },
			  error: function (jqXHR, textStatus, errorThrown)
			  {
		 
			  }
		});
}