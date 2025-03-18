/*封装获取远程JS返回的JSON数据*/
export const getJson = (url, func, callback) => {
  window[func] = function(data) {
    callback(data);
  };
  let script = document.createElement('script');
  script.setAttribute('src', url);
  document.getElementsByTagName('head')[0].appendChild(script);
  script.onload = script.onreadystatechange = function(data) {
    window[func] = null;
  };
}
