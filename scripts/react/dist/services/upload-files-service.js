var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

//console.log(baseDataURL);
var http = axios.create({
  baseURL: baseDataURL + "/react",
  headers: {
    "Content-type": "application/json"
  }
});

var userId = $('#userId').val();
console.log('user id in service: ', userId);

var UploadFilesService = function () {
  function UploadFilesService() {
    _classCallCheck(this, UploadFilesService);
  }

  _createClass(UploadFilesService, [{
    key: "upload",
    value: function upload(file, onUploadProgress) {

      var formData = new FormData();

      formData.append("file", file);

      //return formData; 

      return http.post("/upload", formData, {
        headers: {
          "Content-Type": "multipart/form-data"
        },
        onUploadProgress: onUploadProgress
      });
    }
  }, {
    key: "getFiles",
    value: function getFiles() {
      //console.log("/files/"+userId);
      return http.get("/files/" + userId);
    }
  }]);

  return UploadFilesService;
}();

export default new UploadFilesService();