# Blog

## Overview

`harentius/blog` is a minimalistic blogging engine.  
It is designed to use Markdown files for content and transform them into blogs.  
The directory structure represents a categories tree.  

It consists of the following parts:

**Symfony application (`src/BlogBundle`)**

It is used to store and display blogs.
Provides a REST API to manage blogs and a UI to display them.
It is also available as a standalone Symfony bundle [harentius/blog-bundle](https://github.com/harentius/blog-bundle),
which is a subsplit of this engine.

**Markdown renderer and publisher (`src/publisher`)**

Renders markdown and publish it in a blogging application.

## Installation
*Requirements: docker, make, nodejs (for publisher)*

1. Clone the repository

```
git clone git@github.com:harentius/blog.git
```

2. Start containers and do initial setup
```
make up
```

3. Build a DB schema
```
make build-db
```

4. Install npm modules for publisher
```
cd src/publisher && npm install
```

3. The blog application should be available on the address
[http://localhost:8080](http://localhost:8080)

Mentioned steps run required containers and mounts source folder and can be used for development and experimenting.
For more check `support/examples` configs for more production-ready setup.

## Usage

1. Generate API token
```
make api-key
```

2. Publish a blog post

```
HOST=https://blog.url API_TOKEN=your_api_token src/publisher/src/main.mjs add /path/to/dir/with-blog-files/ Category/Subcategory/blog-url.md
```

The markdown file has following structure:
```
# Title (is required)

###### Meta Description: Optional Meta Description
###### Meta Keywords: Optional Meta Keywords
###### Published at: Optional Published at. Default is "now"
```

The publisher also supports images and other files, including the cover file.
They should be stored in the directory with the same name as the blog, but without ".md".
For example:
```
/path/to/dir/with-blog-files/ Category/Subcategory/blog-url/
```

## Development

### Build docker images
```
make build
```

### Publish to dockerhub
```
make publish
```

### Publish to BlogBundle to blog-bundle repository
```
make publish-blog-bundle
```
