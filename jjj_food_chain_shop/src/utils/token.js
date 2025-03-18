/**
 * @description 获取token
 * @returns
 */
export function getToken(tokenTableName) {
  return sessionStorage.getItem(tokenTableName);
}

/**
 * @description 存储token
 * @param token
 * @returns 
 */
export function setToken(token, tokenTableName) {
  return sessionStorage.setItem(tokenTableName, token);
}
/**
 * @description 移除token
 * @returns
 */
export function removeToken(tokenTableName) {
  return sessionStorage.clear();
}
