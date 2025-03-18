import request from '@/utils/request'

let MessageApi = {
  /*消息列表*/
  messageList(data, errorback) {
    return request._post('/admin/message/index', data, errorback);
  },
  /*添加消息*/
  addMessage(data, errorback) {
    return request._post('/admin/message/add', data, errorback);
  },
  /*修改消息*/
  editMessage(data, errorback) {
    return request._post('/admin/message/edit', data, errorback);
  },
  /*删除消息*/
  deleteMessage(data, errorback) {
    return request._post('/admin/message/delete', data, errorback);
  },
  /*消息字段列表*/
  fieldList(data, errorback) {
    return request._post('/admin/message/field', data, errorback);
  },
  /*保存消息字段*/
  saveField(data, errorback) {
    return request._post('/admin/message/saveField', data, errorback);
  },
}

export default MessageApi;
