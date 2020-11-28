import request from '@/utils/request'

let ArticleApi = {
  /*文章列表*/
  articlelist(data, errorback) {
    return request._post('/shop/operate.article.article/index', data, errorback);
  },
  /*获取文章分类*/
  getCategory(data, errorback) {
    return request._post('/shop/operate.article.category/index', data, errorback);
  },
  /*添加文章*/
  toAddArticle(data, errorback) {
    return request._get('/shop/operate.article.article/add', data, errorback);
  },
  /*添加文章*/
  addArticle(data, errorback) {
    return request._post('/shop/operate.article.article/add', data, errorback);
  },
  /*文章详情*/
  toEditArticle(data, errorback) {
    return request._get('/shop/operate.article.article/edit', data, errorback);
  },
  /*编辑文章*/
  editArticle(data, errorback) {
    return request._post('/shop/operate.article.article/edit', data, errorback);
  },
  /*删除文章*/
  deleteArticle(data, errorback) {
    return request._post('/shop/operate.article.article/delete', data, errorback);
  },
  /*添加分类*/
  addCategiry(data, errorback) {
    return request._post('/shop/operate.article.category/add', data, errorback);
  },
  /*编辑分类*/
  editCategory(data, errorback) {
    return request._post('/shop/operate.article.category/edit', data, errorback);
  },
  /*删除分类*/
  deleteCategory(data, errorback) {
    return request._post('/shop/operate.article.category/delete', data, errorback);
  },


}
export default ArticleApi;
