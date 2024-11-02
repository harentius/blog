import fs from 'node:fs';
import path from 'path';

class FilesManager {
  constructor(projectPath) {
    this.projectPath = projectPath;
  }

  getFileContent(path) {
    return fs.readFileSync(this.#getFullPath(path), 'utf8');
  }

  isDirExists(path) {
    return fs.existsSync(this.#getFullPath(path));
  }

  getAllFilesList(dirPath, fileList = []) {
    const files = fs.readdirSync(this.#getFullPath(dirPath));
    files.forEach((file) => {
      const fullPath = path.join(dirPath, file);
      if (fs.statSync(this.#getFullPath(fullPath)).isDirectory()) {
        this.getAllFilesList(fullPath, fileList);
      } else {
        fileList.push(fullPath);
      }
    });

    return fileList;
  }

  getFileBlob(path) {
    return new Blob([fs.readFileSync(this.#getFullPath(path))])
  }

  #getFullPath(path) {
    return this.projectPath + '/' + path;
  }
}

export default FilesManager;
