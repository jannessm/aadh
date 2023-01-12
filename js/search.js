try {
	let $;
} catch {}
let searchBar;
let last_search = '';

;(function() {
    var pushState = history.pushState;
    var replaceState = history.replaceState;

    history.pushState = function() {
        pushState.apply(history, arguments);
        window.dispatchEvent(new Event('pushstate'));
        window.dispatchEvent(new Event('locationchange'));
    };

    history.replaceState = function() {
        replaceState.apply(history, arguments);
        window.dispatchEvent(new Event('replacestate'));
        window.dispatchEvent(new Event('locationchange'));
    };

    window.addEventListener('popstate', function() {
        window.dispatchEvent(new Event('locationchange'))
    });
})();

function add_hash(hash){
	if (!searchBar || searchBar.value.indexOf(hash) >= 0) return;

	const value = searchBar.value;
	
	if (value.length === 0)
		searchBar.value = hash;
	else
		searchBar.value = value + ' ' + hash;

	update_search_title();
	search();
}

function update_search_title() {
	const search_string = searchBar.value;

	if(search_string.length > 0) {
		$('.aadh-search-title')[0].classList.add('aadh-search-non-empty');
	}	else {	
		$('.aadh-search-title')[0].classList.remove('aadh-search-non-empty');
	}
}

function clear_search() {
	if (!$) return;
	
	searchBar.value = '';

	setTimeout(() => {
		update_search_title();
		search(true);
	}, 1);
}

jQuery(document).ready(_$ => {
	$ = _$;
	searchBar = $('.aadh-search-bar')[0];
	let searchTimer = null;

	const queryString = new URLSearchParams(window.location.search);
	searchBar.value = queryString.get('q') || '';
	update_search_title();

	$('.aadh-search-bar').keyup(() => {
		if (searchTimer != null) {
			window.clearTimeout(searchTimer);
		}
		searchTimer = setTimeout(() => {
			update_search_title();
			search();
		}, 1000);
	});

	window.addEventListener('locationchange', () => {
		const queryString = new URLSearchParams(window.location.search);
		searchBar.value = queryString.get('q') || '';
		update_search_title();
		search();
	});

	$('.aadh-hash').one( 'click', e => {
		add_hash(e.target.innerText);
	});
});

function search(cleared = false) {
	let query = searchBar.value;
	const paged = 1; // reset page on each query

	if (query === last_search && !cleared)
		return;

	// add query to url
	const urlParams = new URLSearchParams(window.location.search);	
	urlParams.set('q', query);
	last_search = query;

	const parsed_query = parseQuery(query);
	categories = parsed_query.categories;
	query = parsed_query.query;

	urlParams.set('paged', paged);

	// add query to history
	window.history.pushState({}, '', '?' + urlParams.toString());

	$('#previews').css('opacity', 0);
	$.post(args.ajax_url, {
		_ajax_nonce: args.nonce,
		action: "paged_posts",

		paged,
		delimiter: args.delimiter,
		order: args.order,
		orderby: args.orderby,
		numberposts: args.posts_per_page,
		query,
		categories,
		date_format: args.date_format
	}, data => {displayPosts(data); $('#previews').css('opacity', 1);}); 
}
