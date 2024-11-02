import path from 'path';

class ApiClient {
  constructor(baseUrl, apiToken) {
    this.baseUrl = baseUrl;
    this.apiToken = apiToken;
  }

  async upsertArticle(data) {
    console.log("Upserting article " + data.slug);
    const url = this.baseUrl + '/article/upsert';
    await this.#execute(url, 'POST', data);
  }

  async delete(slug) {
    console.log("Deleting article " + slug);
    const url = this.baseUrl + '/article/delete';
    await this.#execute(url, 'DELETE', {slug});
  }

  async uploadFile(blob, filepath, filesDir) {
    console.log("Uploading file " + filepath)
    const url = this.baseUrl + '/file/upload';
    const form = new FormData();

    const transformedPath = path.basename(filesDir) + filepath.replace(filesDir, '')

    form.set('file', blob, transformedPath);

    const result = await fetch(url, {
      method: 'POST',
      headers: {
        'api-token': this.apiToken,
      },
      body: form,
    })

    await this.#handleError(result);
  }

  async deleteFile(path) {
    console.log("Deleting file " + path)
    const url = this.baseUrl + '/file/delete';
    await this.#execute(url, 'DELETE', {path});
  }

  async #execute(url, method, data) {
    const result = await fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'api-token': this.apiToken,
      },
      body: JSON.stringify(data)
    });

    await this.#handleError(result);
  }

  async #handleError(response) {
    if (response.ok) {
      return;
    }

    let readableResponse = '';
    try {
      readableResponse = await response.text();
    } catch (error) {

    }

    console.error(`Request error. status: ${response.status}. Response: ${readableResponse}`)
  }
}

export default ApiClient;
