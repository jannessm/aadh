jQuery(document).ready($ => {

  const wrapper = $(args.container);

  const paramString = window.location.search.split('?')[1];
  const queryString = new URLSearchParams(paramString);

  let paged = parseInt(queryString['paged'] || args.paged);
  let query = queryString['q'] || '';

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
      categories: args.cat_ids,
      date_format: args.date_format
    }, data => {
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
        console.log(data.pages, paged, nextPageQuery);

        html += `<div class="pagination">
                  <a href="?${firstPageQuery.toString()}">&lt;&lt;</a>
                  <a href="?${prevPageQuery.toString()}">&lt;</a>\n`;
        
        const max_pages = Math.min(paged + 4, data.pages);
        for (let index = Math.max(1, paged - 3); index <= max_pages; index++) {
          const nextQuery = new URLSearchParams(queryString);
          nextQuery.set('paged', index);

          const sep = index < max_pages ? '&middot;' : '';
          html += index != paged ? `<a href="?${nextQuery.toString()}">${index}</a> ${sep}\n` : `${index} ${sep}\n`;
        }

        html +=  `<a href="?${nextPageQuery.toString()}">&gt;</a>
                  <a href="?${lastPageQuery.toString()}">&gt;&gt;</a>
                </div>`;
      }

      wrapper.html(html);
    });
  }

  refresh();
  // console.log(args);
});

function get_post(post) {
  console.log(post);
  cats = post.categories.reduce((res, cat) => {
    if (!!cat.name) {
      return `${res}<div class="aadh-hash">#${cat.name}</div>`;
    }
    return res;
  }, '');
  
  let html = `
    <div class="post-preview">
      <a href="${post.link}">
        <div class="post-preview-content">`;
  
  if (!!post.image_url) {
    html += `
          <div class="post-preview-image" style="background-image: url(${post.image_url})"></div>`;
  } else {
    html += `
          <div class="post-preview-image"></div>`;

  }
  return html + `
          <div class="post-preview-text">
            <h4>${post.title}</h4>
            <div class="post-preview-meta">
              <span>${post.date}</span>
              <div class="post-preview-hashes">${cats}</div>
            </div>
            <p class="post-preview-content">${post.content}</p>
          </div>
        </div>
      </a>
    </div>`;
}
