(function($){
		  // очищаем select
		  $.fn.clearSelect = function() {
			  return this.each(function(){
				  if(this.tagName=='SELECT') {
				      this.options.length = 0;
				      $(this).attr('disabled','disabled');
				  }
			  });
		  }
		  // заполняем select
		  $.fn.fillSelect = function(dataArray) {
			  return this.clearSelect().each(function(){
				  if(this.tagName=='SELECT') {
					  var currentSelect = this;
					  var first_option = new Option('Выбрать', '');
					   if($.browser.msie) {
							   currentSelect.add(first_option);
						  } else {
							 
							  currentSelect.add(first_option,null);
						  }
					  $.each(dataArray,function(index,data){					  	  	
						  var option = new Option(data.title,data.id);
						  
						  if($.browser.msie) {
							   currentSelect.add(option);
						  } else {
							 
							  currentSelect.add(option,null);
						  }
					  });					  
				  }
			  });
		  }
		})(jQuery);