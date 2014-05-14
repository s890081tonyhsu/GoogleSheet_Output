var test;

function GetKey(name){
	if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
      return decodeURIComponent(name[1]);
}

function padLeft(str,lenght){
    if(str.length >= lenght)
        return str;
    else
        return padLeft("0" +str,lenght);
}

function GenerateForm(detail, obj){
	var FormData = new Hash();
	FormData['Title'] = new Element('h1',{
			html:detail.title.$t
	});
	var length = detail.openSearch$totalResults.$t.length;
	var tableFront;
	Array.each(detail.entry, function(data,index){
		switch(data.gsx$datatype.$t){
			case 'title':
				FormData['sub'+padLeft(index,length)] = new Element('h3',{
					html:data.gsx$datadetail.$t
				});
				break;
			case 'description':
				FormData['sub'+padLeft(index,length)] = new Element('p',{
					html:data.gsx$datadetail.$t
				});
				break;
			case 'subtitle':
				var header = new Array();
				Object.each(data,function(subTitle,index){
					if(index.contains('gsx') && index!='gsx$datatype' && index!='gsx$datadetail')
						header.push(subTitle.$t);
				});
				FormData['sub'+padLeft(index,length)] = new HtmlTable({
					headers: header
				});
				tableFront = FormData['sub'+padLeft(index,length)];
				break;
			case 'detail':
				var row = new Array();
				Object.each(data,function(subTitle,index){
					if(index.contains('gsx') && index!='gsx$datatype' && index!='gsx$datadetail')
						row.push(subTitle.$t);
				});
				tableFront.push(row);
				break;
			default:
				break;
		}
	});
	FormData.each(function(data){
		obj.grab(data);
	});
}

function Parsing(parse_url){
	var ParseSheet = new Request.JSONP({
		url: parse_url,
		data:{},
		onComplete: function(data) {
			// Log the result to console for inspection
			GenerateForm(data.feed, $$('data'));
			console.info(data);
		}
	}).send();
}

function Loader(){
	if (!GetKey('sheet_url')){
		var form = document.id('Generator');
		form.show();
		form.getElements('input[type=submit]').addEvent('click',function(){
			var sheet_url = form.getElements('input[name=sheet_url]').get('text')[0];
			location.search = 'sheet_url='+sheet_url;
			location.reload();
		});
	}else{
		var sheet_url = GetKey('sheet_url').split('/');
		var parse_url = 'http://cors.io/spreadsheets.google.com/feeds/list/'+sheet_url[5]+'/od6/public/values?alt=json';
		Parsing(parse_url);
	}
}

window.addEvent('domready',Loader);
