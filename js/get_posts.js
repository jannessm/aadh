let wrapper;
let queryString;
let paged;

jQuery(document).ready($ => {
  try {
    if (!args) {
      return
    }
  } catch {
    return;
  }

  wrapper = $(args.container);

  queryString = new URLSearchParams(window.location.search);

  paged = parseInt(queryString.get('paged') || args.paged);
  let query = queryString.get('q') || '';
  parsed_query = parseQuery(query);
  query = parsed_query.query;
  const categories = parsed_query.categories;

  function refresh() {
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
    }, data => displayPosts(data));
  }

  refresh();
});

function displayPosts(data) {
  console.log(data);
  queryString = new URLSearchParams(window.location.search);
  paged = parseInt(queryString.get('paged') || args.paged);
  const query = queryString.get('q') || '';
  queryString.set('q', query);
  

  let html = data.posts.reduce((res, p) => res + get_post(p), '');

  if (data.pages > 1) {
    const firstPageQuery = new URLSearchParams(queryString.toString());
    const prevPageQuery = new URLSearchParams(queryString.toString());
    const nextPageQuery = new URLSearchParams(queryString.toString());
    const lastPageQuery = new URLSearchParams(queryString.toString());
    
    firstPageQuery.set('paged', 1);
    prevPageQuery.set('paged', Math.max(paged - 1, 1));
    nextPageQuery.set('paged', Math.min(paged + 1, data.pages));
    lastPageQuery.set('paged', data.pages);

    html += `<div class="pagination">\n`;

    if (paged !== 1) {
      html += `<a href="?${firstPageQuery.toString()}">&lt;&lt;</a>
               <a href="?${prevPageQuery.toString()}">&lt;</a>\n`;
    }
    
    let start_page = Math.max(1, paged - 2);
    let stop_page = Math.min(data.pages, paged + 2);
    if (start_page === 1) {
      stop_page = Math.min(data.pages, start_page + 4);
    } else if (stop_page === data.pages) {
      start_page = Math.max(1, data.pages - 4);
    }

    for (let index = start_page; index <= stop_page; index++) {
      const nextQuery = new URLSearchParams(queryString);
      nextQuery.set('paged', index);

      const sep = index < stop_page ? '&middot;' : '';
      html += index != paged ? `<a href="?${nextQuery.toString()}">${index}</a> ${sep}\n` : `${index} ${sep}\n`;
    }

    if (paged !== data.pages) {
      html +=  `<a href="?${nextPageQuery.toString()}">&gt;</a>
                <a href="?${lastPageQuery.toString()}">&gt;&gt;</a>\n`;
    }

      html += `</div>`;
  }

  wrapper.html(html);
}

function get_post(post) {
  const target = post.link.indexOf(window.location.host) < 0 ? 'target="blank"' : '';
  
  cats = post.categories.reduce((res, cat) => {
    if (!!cat.name) {
      return `${res}<div class="aadh-hash" onclick="add_hash(event.target.innerText)">#${cat.name}</div>`;
    }
    return res;
  }, '');
  
  let html = `
    <div class="post-preview">
      <a href="${post.link}" ${target}>
        <div class="post-preview-content">`;
  
  if (!!post.image_url) {
    html += `
          <div class="post-preview-image" style="background-image: url(${post.image_url})"></div>`;
  } else {
    html += '';//`
          //<div class="post-preview-image"></div>`;

  }
  return html + `
          <div class="post-preview-text">
            <h4>${post.title}</h4>
            <div class="post-preview-meta">
              <span>${post.date}</span>
              <div class="post-preview-hashes" onclick="event.preventDefault();">${cats}</div>
            </div>
            <p class="post-preview-content">${post.content}</p>
          </div>
        </div>
      </a>
    </div>`;
}

function parseQuery(query) {
	words = query.split(' ');

	return words.reduce((obj, val) => {
		if (val.indexOf('#') == 0) {
			obj.categories.push(args.cat_ids[args.categories.indexOf(val.substring(1))]);
		} else {
			obj.query += `${val} `;
		}
		return obj;
	}, {
		categories: [],
		query: ''
	})
}