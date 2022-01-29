
const e = React.createElement;
const Component = React.Component;

let v = new Date().getTime();
import UploadFiles from "./upload-files-component.js";

//class UploadFiles extends React.Component {
	
//}


class ReactFileUpload extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    

    return (
      	<div>
		<h5>Upload a Gazetteer County Subdivision Geography Map</h5>	
		<UploadFiles />	
		</div>
    );
  }
}

const domContainer = document.querySelector('#react_file_upload');
ReactDOM.render(e(ReactFileUpload 
), domContainer);