/*创建腾讯地图*/
export function TMap() {

  return new Promise(function(resolve, reject) {
    window.txmapinit = function() {
      resolve(window.qq)
    }
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://map.qq.com/api/js?v=2.exp&key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77&callback=txmapinit";
    script.onerror = reject;
    document.head.appendChild(script);
  })
}
