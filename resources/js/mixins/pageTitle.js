// ページタイトル表示
export const setTitle = pathTitle => {
  const siteTitle = 'STEP';
  const pageTitle = !pathTitle ? siteTitle : pathTitle + ' | ' + siteTitle;
  return (window.document.title = pageTitle);
};

export const globalMixins = {
  methods: {
    setTitle
  }
};
