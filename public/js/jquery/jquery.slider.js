(function($){
	$.fn.customSlider = function(o) {
		return this.each(function() {
			new $.customSlider(this,o);
		});
	};
	
	
	var defaults = {
		optionsClass: 'option',
		containerClass: 'container',
		priceClass: 'option_price',
		cartClass: 'zakaz_btn',
		currency: 'бр'
		
	};
	
	$.customSlider = function(el,o) {	
		var self = this;		
		this.o = $.extend({}, defaults, o || {});		
		this.$list = $(el);		
		self.init();
	};
	
	$.customSlider.fn = $.customSlider.prototype = {};
	$.customSlider.fn.extend = $.customSlider.extend = $.extend;
	
	
	$.customSlider.fn.extend({
		init: function() {			
			this.$index = 0;
			this.$items = this.$list.children("."+this.o.optionsClass);
			this.$total = this.$items.size()-1;
			this.$container = $(this.$list.children("."+this.o.containerClass).get(0));
			this.$price = $("."+this.o.priceClass, this.$list);
			this.$addToCart = $("."+this.o.cartClass, this.$list);			
			var first_option = $(this.$items[0]);
			this.$container.text(first_option.text());
			this.$price.html(first_option.attr('price')+' '+'<i>'+this.o.currency+'</i>');	
			this.$addToCart.attr('id_price', first_option.attr('id_price'));			
			var self = this;
			
			this.funcMoveBack = function() { self.moveBack(); };
			this.funcMoveForward = function() { self.moveForward(); };
			this.funcCheckIndex = function() { self.checkIndex(); };
			this.funcSetOption = function(index, title, price, id_price) { self.setOption(index, title, price, id_price)};
			
			this.$btnForward = $('.next',this.$list).click(this.funcMoveForward);
			this.$btnBack = $('.prev',this.$list).click(this.funcMoveBack);
			this.$btnBack.addClass('prev_off');
			
			
			
		},
		moveForward: function() {
			var self = this;
			//alert(this.$index);
			if(this.$index<this.$total){
				this.$index = this.$index+1;
				var current_item = $(this.$items[this.$index]);
				self.funcSetOption(this.$index, current_item.text(), current_item.attr('price'),current_item.attr('id_price'));
					
			}
			self.funcCheckIndex();
		
		},
		moveBack: function() {
		
			var self = this;
			//alert(this.$index);
			if(this.$index>0 ){
				this.$index = this.$index-1;
				var current_item = $(this.$items[this.$index]);
				self.funcSetOption(this.$index, current_item.text(), current_item.attr('price'),current_item.attr('id_price'));
				
					
			}
			self.funcCheckIndex();
		
		},
		setOption: function (index, title, price, id_price) {
				var current_item = $(this.$items[index]);
				this.$container.text(title);
				this.$price.html(price+' '+'<i>'+this.o.currency+'</i>');
				this.$addToCart.attr('id_price', id_price);
		},
		checkIndex: function(){			
			if(this.$index<=0){
				this.$index = 0;
				this.$btnBack.addClass('prev_off');
			} else {
				this.$btnBack.removeClass('prev_off');
			}
			if(this.$index>=this.$total){
				this.$index = this.$total;
				this.$btnForward.addClass('next_off');
				
			} else{
				this.$btnForward.removeClass('next_off');
			}
		}
	
	
	
	
	
	
	
	});
	
				
})(jQuery);