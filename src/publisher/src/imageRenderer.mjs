const imageRenderer = () => ({
  renderer: {
    image(data) {
      const title = data.title || '';
      let [width, height] = title.startsWith('=') ? title.slice(1).split('x').map(v => v.trim()).filter(Boolean) : [];

      if (!width) {
        width = 815;
      }

      let srcFull = data.href

      if (width || height) {
        srcFull += '?';

        if (width) {
          srcFull += `width=${width}`
        }

        if (height) {
          if (width) {
            srcFull += `&height=${height}`
          } else {
            srcFull += `height=${height}`
          }
        }
      }

      return `<p><a href="${data.href}" target="_blank"><img src="${srcFull}"/></a></p>`;
    }
  }
});

export default imageRenderer;
