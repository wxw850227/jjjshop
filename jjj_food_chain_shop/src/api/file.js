import request from '@/utils/request'

let FileApi = {

    /*文件类别列表*/
    fileCategory(data, errorback) {
        return request._post('/shop/file.file/category', data, errorback);
    },

    /*文件列表*/
    fileList(data, errorback) {
        return request._post('/shop/file.file/lists', data, errorback);
    },

    /*删除多文件*/
    deleteFiles(data, errorback) {
        return request._post('/shop/file.file/deleteFiles', data, errorback);
    },

    /*添加文件分类*/
    addCategory(data, errorback) {
        return request._post('/shop/file.file/addGroup', data, errorback);
    },

    /*修改文件分类*/
    editCategory(data, errorback) {
        return request._post('/shop/file.file/editGroup', data, errorback);
    },

    /*删除文件分类*/
    deleteCategory(data, errorback) {
        return request._post('/shop/file.file/deleteGroup', data, errorback);
    },

    /*上传*/
    uploadFile(formData, errorback) {
        return request._upload('/shop/file.upload/image', formData, errorback);
    },
    /*移动文件*/
    moveFile(formData, errorback) {
        return request._upload('/shop/file.upload/moveFiles', formData, errorback);
    },


}

export default FileApi;
