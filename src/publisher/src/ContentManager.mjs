import { Marked } from 'marked';
import { baseUrl } from "marked-base-url";
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
    marked.use(baseUrl("/assets/"));
    marked.use(imageRenderer())
    marked.use(youtubeRenderer())

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
    const fileNameWithExtension = filePath.split('/').pop();
    const fileName = fileNameWithExtension.slice(0, fileNameWithExtension.lastIndexOf('.'));
    const directoryPath = filePath.substring(0, filePath.lastIndexOf('/'));

    return {fileName, directoryPath};
  }
}

export default ContentManager;
