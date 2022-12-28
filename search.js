var last_search = undefined;
var last_page = 0;

function init_search(){
	var urlParams = new URLSearchParams(window.location.search);
	
	jQuery('.search_bar').bind('keyup', function(){
		window.clearTimeout(search_timer);
		search_timer = window.setTimeout(function(){search()}, 500);
	});
	jQuery('.search_bar').bind('input', function(){
		update_search_title();
	})
	jQuery('.search_bar').val(decodeURIComponent(urlParams.get('q') || ''));
	jQuery('.hash').bind('click', function(){
		add_hash(this.innerText);
	});

	update_search_title();
	search();
}

function search(page_id=undefined){
	console.log('hi');
	if (page_id !== undefined && page_id.indexOf('N') >= 0)
		page_id = last_page + 1;
	else if (page_id !== undefined && page_id.indexOf('Z') >= 0)
		page_id = last_page - 1;

	var search_string = document.getElementsByClassName('search_bar')[0].value;
	console.log(search_string);
	if (search_string === last_search && page_id === last_page)
		return;
	// reset page_id if query is different but ids equal
	if(page_id === last_page || page_id === undefined)
		page_id = 1;
	
	// add query to url
	var urlParams = new URLSearchParams(window.location.search);
	urlParams.set('q', search_string);
	var html = document.innerHTML;
  var title = document.title;
	if (search_string) {
    	window.history.pushState({"html": html,"pageTitle": title},"", '?'+urlParams.toString());	
	}

	last_page = page_id;
	last_search = search_string;
	jQuery('#post_wrapper').css('opacity', 0);

	jQuery.ajax({
	    type : "post",
	    dataType : "json",
	    url : myAjax.ajaxurl,
	    data : {action: "get_news", search_key: search_string, paged: page_id},
	    success: function(response) {
            jQuery("#post_wrapper").html(response.content).ready(function(){
            	jQuery('#post_wrapper').css('opacity', 1);
            	jQuery('.page-numbers').click(function(evt){
					evt.preventDefault();
					search(this.innerText);
				});
            })
	    },
			error: function(res) {
				console.log('err', res);
			}
	}) 
}

function update_search_title(){
	var search_string = document.getElementsByClassName('search_bar')[0].value;

	if(search_string.length > 0)
		document.getElementsByClassName('search_title')[0].className = 'search_title search_active';
	else
		document.getElementsByClassName('search_title')[0].className = 'search_title';
}

function clear_search(){
	new Promise(function(res, rej){

		document.getElementsByClassName('search_bar')[0].value = '';
		var i = 0;
		while(true){
			if(document.getElementsByClassName('search_bar')[0].value === ''){
				res(null);
				return
			}
			i++;
		}
	}).then(function(){update_search_title(); search();})
}

function add_hash(hash){
	var value = document.getElementsByClassName('search_bar')[0].value;
	var updated = value.indexOf(hash) < 0 || value.length === 0;
	if (value.indexOf(hash) < 0)
		document.getElementsByClassName('search_bar')[0].value = value + ' ' + hash;
	else if (value.length === 0)
		document.getElementsByClassName('search_bar')[0].value = hash;

	update_search_title();
	
	if (updated)
		search();
}