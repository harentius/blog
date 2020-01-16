import configReader from '../../../src/BlogBundle/src/Resources/js/config-reader';

window.disqus_config = function () {
  this.page.url = configReader.get('article_url');
  this.page.identifier = configReader.get('page_identifier');
};

(() => {
  const d = window.document;
  const s = d.createElement('script');
  s.src = `//${configReader.get('discuss_user_name')}.disqus.com/embed.js`;

  s.setAttribute('data-timestamp', +new Date());
  (d.head || d.body).appendChild(s);
})();
