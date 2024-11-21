import { Marked } from 'marked';
import { baseUrl } from 'marked-base-url';
import { markedHighlight } from "marked-highlight";
import hljs from 'highlight.js';
import imageRenderer from './imageRenderer.mjs';
import youtubeRenderer from './youtubeRenderer.mjs';

class ContentManager {
  extractArticleData(markdown, path) {
    const {fileName, directoryPath} = this.#extractFileNameAndPath(path);
    const lines = markdown.replace('\r\n', '\n').replace('\r', '\n').split('\n');
    const title = lines[0].replace('#', '').trim();
    let metaDescription = '';
    let metaKeywords = '';
    let publishedAt = null;
    let start = 0;

    for (const line of lines.slice(1)) {
      start++;
      if (line.startsWith('###### Meta Description:')) {
        metaDescription = line.replace('###### Meta Description:', '').trim()
      } else if (line.startsWith('###### Meta Keywords:')) {
        metaKeywords = line.replace('###### Meta Keywords:', '').trim()
      } else if (line.startsWith('###### Published at:')) {
        publishedAt = line.replace('###### Published at:', '').trim()
      } else if (line.length === 0) {
        continue;
      } else {
        break;
      }
    }

    const contentMarkdown = lines.slice(start).join('\n');
    const marked = new Marked();
    marked
      .use({ gfm: true })
      .use(baseUrl("/assets/"))
      .use(imageRenderer())
      .use(youtubeRenderer())
      .use(markedHighlight({
        emptyLangClass: 'hljs',
        langPrefix: 'hljs language-',
        highlight(code, lang, info) {
          const language = hljs.getLanguage(lang) ? lang : 'plaintext';
          return hljs.highlight(code, { language }).value;
        }
      }));

    let content = marked.parse(contentMarkdown);

    return {
      title,
      slug: fileName,
      path: directoryPath,
      content,
      metaDescription,
      metaKeywords,
      publishedAt,
    }
  }

  #extractFileNameAndPath(filePath) {
    filePath = filePath.replace(/^\.\//, '');
    const fileNameWithExtension = filePath.split('/').pop();
    const fileName = fileNameWithExtension.slice(0, fileNameWithExtension.lastIndexOf('.'));
    const directoryPath = filePath.substring(0, filePath.lastIndexOf('/'));

    return {fileName, directoryPath};
  }
}

export default ContentManager;
