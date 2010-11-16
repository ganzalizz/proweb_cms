var prompt;
var prTimer = null;
var promptId;

var show_img = '/img/admin/ico_show-.gif';
var hide_img = '/img/admin/ico_show+.gif';
// массив куков для отображения/сокрытия блоков

var cookie_array = ['seo_block', 'page_settings', 'tags_block'];

$.cookie('the_cookie');

function set_cookie(key, value){
	$.cookie(key, value, {
			 expires: 3600,
			 path: "/"
		});
}

function show_hide_block(id, img_id){
	if($("#"+id).css('display')=='none'){ // show
		$("#"+id).css('display', 'block');
		$("#"+img_id).attr('src', show_img);
		set_cookie(id, 'block');
	} else { // hide
		$("#"+id).css('display', 'none');
		$("#"+img_id).attr('src', hide_img);
		set_cookie(id, 'none');
	}
	return false;
	
}

	// заполнение сео блока по названию элемента
	function fill_seoBlock(title_id){
		var value = $("#"+title_id).val();
		if($("#seo_title").val()==''){
			$("#seo_title").val(value);
		}
		if($("#seo_keywords").val()==''){
			$("#seo_keywords").val(value);
		}
		if($("#seo_description").val()==''){
			$("#seo_description").val(value);
		}		

	}


	/*
	*	блок для загрузки файла
	*
	*/
	
	var file_index = 	1;	
	var max_index = 	6;
	
	function createFileBlock(index, title){
		var html_block = '';
		html_block += "<div class=\"file\" id=\"file"+index+"\">";		
		html_block += 	"<input type=\"file\" name=\"image"+index+"\">";
		html_block+= title ? " <label>Название <input type=\"text\" name=\"title_image"+index+"\"></label>" : "";
		
		html_block += 	"<a href=\"#\" class=\"delete_file_block\" id_block=\"file"+index+"\"><img src=\"/img/unchecked.gif\"></a>";
		html_block += "</div>";
		
		return html_block;
							
	}
	
	function reindexFiles(){		
		var total = $(".file").size();
		$(".file").each(function (index, domEle) {
			if(index>0){
				current = (index+1)*1;
				$(this).attr("id", "file"+current);
				$(this).find("input").attr("name", "image"+current);
				$(this).find("a").attr("id_block", "file"+current);
				//var html_content = $(this).html();						
				//$(this).html(html_content.replace(/file[\d]{1}/gi, "file"+current));						
			}
		});
		file_index = total;	
	}

   $(document).ready(function(){
		 $.datepicker.setDefaults(
			$.extend($.datepicker.regional["ru"])
		 );
		$("input[id='datepicker']").datepicker(
			{
				showOn: 'both', 
				buttonImage: '/img/admin/ui-lightness/calendar.gif', 
				buttonImageOnly: true  			

		});
		$("input.datepicker").datepicker(
			{
				showOn: 'both', 
				buttonImage: '/img/admin/ui-lightness/calendar.gif', 
				buttonImageOnly: true  			

		});
		
		 $("#select_all").toggle(
				  function () {
					$(".checkbox").attr("checked", "checked");
					return false;
				  },
				  
				  function () {
					$(".checkbox").removeAttr("checked");
					return false;
				  }
			);
		// добавить поле для загрузки файла	
		$("#add_btn").click(function(){		
			if(file_index<max_index){
				file_index = (file_index+1)*1;
				var html_block = createFileBlock(file_index, 1);
				$("#files_block").append(html_block);
			} else {
				$(this).attr("disabled", "disabled");
			}	
		});
	
		// удалить поле для загрузки файла
		$(".delete_file_block").live('click',function(){
			var id_block = $(this).attr("id_block");
			$("#"+id_block+"").remove();		
			if(file_index>1){
				file_index = (file_index-1)*1;
			}
			if(file_index < max_index){
				$("#add_btn").removeAttr("disabled");

			}
			reindexFiles();
			return false;
			
		});	
		
		$("#add_title").click(function(){
			$("#template").clone().appendTo("#title1").wrap("<div class='padd_top_5'></div>");
		});
		
		
		
   
   })	
 




	// показать скрыть блоки по значениям куков
	for(i = 0; i < cookie_array.length; i++) {
		if ($.cookie(cookie_array[i])){				
			$("#"+cookie_array[i]).css('display', $.cookie(cookie_array[i]));
			if ($.cookie(cookie_array[i])=='none'){
				$("#"+cookie_array[i]+'_img').attr('src', hide_img);
			} else{
				$("#"+cookie_array[i]+'_img').attr('src', show_img);
			}
		}
  
	}


	$('.pr-wrap').hover(
		function () {
			$('.prompt').addClass('hidden');
			if( prTimer != null)
			{	clearTimeout( prTimer );
				prTimer = null;
			}
			
			var offset = $(this).offset();
			prompt = $('#pr-' + $(this).attr('id'));
			promptId = prompt.attr('id');
			prompt.removeClass('hidden');
			prompt.css({
				top: offset.top + $(this).height() + 5 + 'px',
				left: offset.left + 'px'
			});
			
			if( document.all) 
			{	//$('select').css('visibility','inherit');
				var selects = $('select');
				for (i = 0; i < selects.length; i++)
				{	var select = selects.eq(i);
					var offsetSelect = select.offset();
					var PX1 = offset.left;
					var PX2 = offset.left + prompt.width() + 22;
					var PY1 = offset.top + $(this).height() + 5;
					var PY2 = offset.top + $(this).height() + 5 + prompt.height();
					//alert(PX1 + ';' + PX2 + '; = ;' + PY1 + ';' + PY2);
					
					var SX1 = offsetSelect.left;
					var SX2 = offsetSelect.left + select.width();
					var SY1 = offsetSelect.top;
					var SY2 = offsetSelect.top + select.height();
					//alert(SX1 + ';' + SX2 + '; = ;' + SY1 + ';' + SY2);
					
					if (	(PX1 < SX2) && (PX2 > SX1) && (PY1 < SY2) && (PY2 > SY1) ) 
					{	select.css('visibility', 'hidden');
					}
				}
			}
		},
		function () 
		{	
			//prTimer = setTimeout( "addH( promptId )", 200 );
		}
	);

	$('.prompt').hover(
		function () 
		{	if( prTimer != null)
			{	clearTimeout( prTimer );
				prTimer = null;
			}
		},
		function () 
		{	$(this).addClass('hidden');
			if( document.all) $('select').css('visibility','visible');
		}
	);



function translit(input, mode) {
	/* AUTOFILL: TRANSLIT */
	var rusBig = new Array( "Э", "Ч", "Ш", "Ё", "Ё", "Ж", "Ю", "Ю", "\Я", "\Я", "А", "Б", "В", "Г", "Д", "Е", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Щ", "Ъ", "Ы", "Ь");
	var rusSmall = new Array("э", "ч", "ш", "ё", "ё","ж", "ю", "ю", "я", "я", "а", "б", "в", "г", "д", "е", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "щ", "ъ", "ы", "ь" );
	var engBig = new Array("E\'", "CH", "SH", "YO", "JO", "ZH", "YU", "JU", "YA", "JA", "A","B","V","G","D","E", "Z","I","J","K","L","M","N","O","P","R","S","T","U","F","H","C", "W","~","Y", "\'");
	var engSmall = new Array("e\'", "ch", "sh", "yo", "jo", "zh", "yu", "ju", "ya", "ja", "a", "b", "v", "g", "d", "e", "z", "i", "j", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "w", "~", "y", "\'");
	var rusRegBig = new Array( /Э/g, /Ч/g, /Ш/g, /Ё/g, /Ё/g, /Ж/g, /Ю/g, /Ю/g, /Я/g, /Я/g, /А/g, /Б/g, /В/g, /Г/g, /Д/g, /Е/g, /З/g, /И/g, /Й/g, /К/g, /Л/g, /М/g, /Н/g, /О/g, /П/g, /Р/g, /С/g, /Т/g, /У/g, /Ф/g, /Х/g, /Ц/g, /Щ/g, /Ъ/g, /Ы/g, /Ь/g);
	var rusRegSmall = new Array( /э/g, /ч/g, /ш/g, /ё/g, /ё/g, /ж/g, /ю/g, /ю/g, /я/g, /я/g, /а/g, /б/g, /в/g, /г/g, /д/g, /е/g, /з/g, /и/g, /й/g, /к/g, /л/g, /м/g, /н/g, /о/g, /п/g, /р/g, /с/g, /т/g, /у/g, /ф/g, /х/g, /ц/g, /щ/g, /ъ/g, /ы/g, /ь/g);
	var engRegBig = new Array( /E'/g, /CH/g, /SH/g, /YO/g, /JO/g, /ZH/g, /YU/g, /JU/g, /YA/g, /JA/g, /A/g, /B/g, /V/g, /G/g, /D/g, /E/g, /Z/g, /I/g, /J/g, /K/g, /L/g, /M/g, /N/g, /O/g, /P/g, /R/g, /S/g, /T/g, /U/g, /F/g, /H/g, /C/g, /W/g, /~/g, /Y/g, /'/g);
	var engRegSmall = new Array(/e'/g, /ch/g, /sh/g, /yo/g, /jo/g, /zh/g, /yu/g, /ju/g, /ya/g, /ja/g, /a/g, /b/g, /v/g, /g/g, /d/g, /e/g, /z/g, /i/g, /j/g, /k/g, /l/g, /m/g, /n/g, /o/g, /p/g, /r/g, /s/g, /t/g, /u/g, /f/g, /h/g, /c/g, /w/g, /~/g, /y/g, /'/g);

	var textar = input;
	var res = "";

	if(mode == "E_TO_R") {
		if (textar) {
		for (i=0; i<engRegSmall.length; i++) {
			textar = textar.replace(engRegSmall[i], rusSmall[i])
		}
		
		for (var i=0; i<engRegBig.length; i++) {
			textar = textar.replace(engRegBig[i], rusBig[i])
			textar = textar.replace(engRegBig[i], rusBig[i])
		}
		
		res = textar;
		}
	}
	if(mode == "R_TO_E") {
		if (textar) {
		for (i=0; i<rusRegSmall.length; i++) {
			textar = textar.replace(rusRegSmall[i], engSmall[i])
		}
		
		for (var i=0; i<rusRegBig.length; i++) {
			// textar = textar.replace(rusRegBig[i], engBig[i])
			textar = textar.replace(rusRegBig[i], engSmall[i])
		}
		
		res = textar.toLowerCase();
		}
	}
		 //
	res = res.replace(/[\/\\'\.,\t\|\+&\?%#@]*/g, "");
	res = res.replace(/[ ]+/g, "_");
	//
	
	//document.post.message.value = res;
	return res;
}

function ttAdd()
{
	var input = document.getElementById('name');	
	trans = translit(input.value, "R_TO_E");	
	var url = document.getElementById('path');
	var h1 = document.getElementById('h1');
	var page_title = document.getElementById('page_title');
	var keywords = document.getElementById('keywords');
	var descriptions = document.getElementById('descriptions');
	url.value = trans;
	h1.value = input.value;
	page_title.value = input.value;
	keywords.value = input.value;
	descriptions.value = input.value;
	
}

function ttEdit()
{
	var input = document.getElementById('name');
	trans = translit(input.value, "R_TO_E");
	var url = document.getElementById('path');
	var h1 = document.getElementById('h1');
	h1.value = input.value;
	
}


//Список ингридиентов
$(document).ready(function(){
    $(".hide").hide();
    $(".show_ingr").click(function(){
        id = $(this).attr('id')+'_list';
        $("#"+id).toggle();
        return false;
    });
})