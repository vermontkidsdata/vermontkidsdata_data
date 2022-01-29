
const Component = React.Component;
let v = new Date().getTime();
import UploadService from "../services/upload-files-service.js";

const userId = $('#userId').val();
console.log(userId);

export default class UploadFiles extends Component {
	
  constructor(props) {
	
	super(props);
	
	/*
	IMPORTANT - you must bind functions to the scope!
	*/
	
	this.selectFile = this.selectFile.bind(this);
    this.upload = this.upload.bind(this);
	
	this.state = {
	      selectedFiles: undefined,
	      currentFile: undefined,
	      progress: 0,
	      message: "",	
	      fileInfos: [],
		  recordsLoaded: [],
		  recordsRejected: []
	    };
  }

	selectFile(event) {
		console.log('select files', event);
	    this.setState({
	      selectedFiles: event.target.files,
	    });
  	}

	  upload() {
		
	    let currentFile = this.state.selectedFiles[0];
	
	    this.setState({
	      progress: 0,
	      currentFile: currentFile,
	    });

		//console.log(UploadService); return false;
	
	    UploadService.upload(currentFile, (event) => {
	      this.setState({
	        progress: Math.round((100 * event.loaded) / event.total),
	      });
	    })
	      .then((response) => {
			console.log('upload response', response);
	        this.setState({
	          message: response.data.message,
			  recordsLoaded: response.data.records.loaded,
			  recordsRejected: response.data.records.rejected,
	        });
			
	        return UploadService.getFiles(userId);
	      })
	      .then((files) => {
	        this.setState({
	          fileInfos: files.data,
	        });
	      })
	      .catch(() => {
	        this.setState({
	          progress: 0,
	          message: "Could not upload the file!",
	          currentFile: undefined,
	        });
	      });
	
	    this.setState({
	      selectedFiles: undefined,
	    });


	  }

	componentDidMount() {
		//console.log('upload service', UploadService);
		//let userId = $('#userId').val();
		console.log('getting files for',userId);
	    UploadService.getFiles(userId).then((response) => {
		console.log('files', response);
	      this.setState({
	        fileInfos: response.data,
	      });
	    });
		
	 }

  render() {
	const {
      	selectedFiles,
      	currentFile,
      	progress,
     	message,
      	fileInfos,
		recordsLoaded,
		recordsRejected
    } = this.state;

    return (
      <div>
        {currentFile && (
          <div className="progress">
            <div
              className="progress-bar progress-bar-info progress-bar-striped"
              role="progressbar"
              aria-valuenow={progress}
              aria-valuemin="0"
              aria-valuemax="100"
              style={{ width: progress + "%" }}
            >
              {progress}%
            </div>
          </div>
        )}

        <label className="btn btn-default">
          <input type="file" onChange={this.selectFile} />
        </label>

        <button className="btn btn-success"
          disabled={!selectedFiles}
          onClick={this.upload}
        >
          Upload
        </button>

        <div className="alert alert-light" role="alert">
          <div>{message}</div>
		  <div>{ (Array.isArray(recordsLoaded) && recordsLoaded.length) ? 'records loaded: ' + recordsLoaded.length : '' }</div>
		  <div>{ (Array.isArray(recordsRejected) && recordsRejected.length) ? 'records with errors: ' + recordsRejected.length : '' }</div>
        </div>

        <div className="card">
          <div className="card-header">Uploaded Gazetteer Geography Maps</div>
          <ul className="list-group list-group-flush">
            {fileInfos &&
              fileInfos.map((file, index) => (
                <li className="list-group-item" key={index}>
                  <a href={file.url}>{file.name}</a>
                </li>
              ))}
          </ul>
        </div>
      </div>
    );
  }


}
