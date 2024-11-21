#!/usr/bin/env node

import path from 'path';
import ApiClient from './ApiClient.mjs';
import FilesManager from './FilesManager.mjs';
import ContentManager from './ContentManager.mjs';

const host = process.env.HOST;
const apiToken = process.env.API_TOKEN;

const baseUrl = `${host}/api/v1`;
const mode = process.argv[2]
const projectPath = process.argv[3]
const articlePath = process.argv[4]

console.log(`Running publisher.\n
Host: ${host}, \n
Mode: ${mode},\n 
ProjectPath: ${projectPath},\n 
ArticlePath: ${articlePath}`
);

const apiClient = new ApiClient(baseUrl, apiToken);
const fileManager = new FilesManager(projectPath);
const contentManager = new ContentManager();

const processFile = async (p) => {
  const fileContent = fileManager.getFileContent(p);
  const data = contentManager.extractArticleData(fileContent, p);
  await apiClient.upsertArticle(data);

  const filesDir = p
    .replace(/^\.\//, '')
    .replace(/\.md$/, '');
  if (fileManager.isDirExists(filesDir)) {
    const filesList = fileManager.getAllFilesList(filesDir);

    await apiClient.deleteFile(path.basename(filesDir));

    for (const path of filesList) {
      const blob = fileManager.getFileBlob(path);
      await apiClient.uploadFile(blob, path, filesDir);
    }
  }
}

if (mode === 'add') {
  await processFile(articlePath);
} else if (mode === 'delete') {
  const destination = articlePath.replace(/\.md$/, '')
  const slug = path.basename(destination)
  await apiClient.deleteFile(slug);
  await apiClient.delete(slug)
} else {
  console.error('Unknown mode ' + mode);
}
