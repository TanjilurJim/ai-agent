function getScriptQueryParam(param) {
  const scripts = document.getElementsByTagName('script');
  for (const script of scripts) {
    const src = script.getAttribute('src');
    if (src && src.includes(param)) {
      const urlParams = new URLSearchParams(src.split('?')[1]);
      return urlParams.get(param);
    }
  }
  return null;
}



// api kaey 

const API_KEY = getScriptQueryParam('api_key');
