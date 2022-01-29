//console.log(baseDataURL);
const http = axios.create({
  baseURL: baseDataURL+"/react",
  headers: {
    "Content-type": "application/json"
  }
});

const userId = $('#userId').val();
console.log('user id in service: ',userId);

class UploadFilesService {
	
  upload(file, onUploadProgress) {
	
    let formData = new FormData();

    formData.append("file", file);
	
	//return formData; 

    return http.post("/upload", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
      onUploadProgress,
    });
	
  }

  getFiles() {
	//console.log("/files/"+userId);
    return http.get("/files/"+userId);
  }
}

export default new UploadFilesService();