const youtubeRenderer = () => ({
  renderer: {
    link(token) {
      if (token.href && !token.href.startsWith('https://www.youtube.com')) {
        return false;
      }

      return `<iframe class="video" frameborder="0" src="${token.href}" allow="accelerometer; autoplay; encrypted-media; gyroscope;" allowfullscreen></iframe>`;
    }
  }
});

export default youtubeRenderer;
