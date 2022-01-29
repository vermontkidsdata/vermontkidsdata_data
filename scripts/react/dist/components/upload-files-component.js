var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Component = React.Component;
var v = new Date().getTime();
import UploadService from "../services/upload-files-service.js";

var userId = $('#userId').val();
console.log(userId);

var UploadFiles = function (_Component) {
		_inherits(UploadFiles, _Component);

		function UploadFiles(props) {
				_classCallCheck(this, UploadFiles);

				/*
    IMPORTANT - you must bind functions to the scope!
    */

				var _this = _possibleConstructorReturn(this, (UploadFiles.__proto__ || Object.getPrototypeOf(UploadFiles)).call(this, props));

				_this.selectFile = _this.selectFile.bind(_this);
				_this.upload = _this.upload.bind(_this);

				_this.state = {
						selectedFiles: undefined,
						currentFile: undefined,
						progress: 0,
						message: "",
						fileInfos: [],
						recordsLoaded: [],
						recordsRejected: []
				};
				return _this;
		}

		_createClass(UploadFiles, [{
				key: "selectFile",
				value: function selectFile(event) {
						console.log('select files', event);
						this.setState({
								selectedFiles: event.target.files
						});
				}
		}, {
				key: "upload",
				value: function upload() {
						var _this2 = this;

						var currentFile = this.state.selectedFiles[0];

						this.setState({
								progress: 0,
								currentFile: currentFile
						});

						//console.log(UploadService); return false;

						UploadService.upload(currentFile, function (event) {
								_this2.setState({
										progress: Math.round(100 * event.loaded / event.total)
								});
						}).then(function (response) {
								console.log('upload response', response);
								_this2.setState({
										message: response.data.message,
										recordsLoaded: response.data.records.loaded,
										recordsRejected: response.data.records.rejected
								});

								return UploadService.getFiles(userId);
						}).then(function (files) {
								_this2.setState({
										fileInfos: files.data
								});
						}).catch(function () {
								_this2.setState({
										progress: 0,
										message: "Could not upload the file!",
										currentFile: undefined
								});
						});

						this.setState({
								selectedFiles: undefined
						});
				}
		}, {
				key: "componentDidMount",
				value: function componentDidMount() {
						var _this3 = this;

						//console.log('upload service', UploadService);
						//let userId = $('#userId').val();
						console.log('getting files for', userId);
						UploadService.getFiles(userId).then(function (response) {
								console.log('files', response);
								_this3.setState({
										fileInfos: response.data
								});
						});
				}
		}, {
				key: "render",
				value: function render() {
						var _state = this.state,
						    selectedFiles = _state.selectedFiles,
						    currentFile = _state.currentFile,
						    progress = _state.progress,
						    message = _state.message,
						    fileInfos = _state.fileInfos,
						    recordsLoaded = _state.recordsLoaded,
						    recordsRejected = _state.recordsRejected;


						return React.createElement(
								"div",
								null,
								currentFile && React.createElement(
										"div",
										{ className: "progress" },
										React.createElement(
												"div",
												{
														className: "progress-bar progress-bar-info progress-bar-striped",
														role: "progressbar",
														"aria-valuenow": progress,
														"aria-valuemin": "0",
														"aria-valuemax": "100",
														style: { width: progress + "%" }
												},
												progress,
												"%"
										)
								),
								React.createElement(
										"label",
										{ className: "btn btn-default" },
										React.createElement("input", { type: "file", onChange: this.selectFile })
								),
								React.createElement(
										"button",
										{ className: "btn btn-success",
												disabled: !selectedFiles,
												onClick: this.upload
										},
										"Upload"
								),
								React.createElement(
										"div",
										{ className: "alert alert-light", role: "alert" },
										React.createElement(
												"div",
												null,
												message
										),
										React.createElement(
												"div",
												null,
												Array.isArray(recordsLoaded) && recordsLoaded.length ? 'records loaded: ' + recordsLoaded.length : ''
										),
										React.createElement(
												"div",
												null,
												Array.isArray(recordsRejected) && recordsRejected.length ? 'records with errors: ' + recordsRejected.length : ''
										)
								),
								React.createElement(
										"div",
										{ className: "card" },
										React.createElement(
												"div",
												{ className: "card-header" },
												"Uploaded Gazetteer Geography Maps"
										),
										React.createElement(
												"ul",
												{ className: "list-group list-group-flush" },
												fileInfos && fileInfos.map(function (file, index) {
														return React.createElement(
																"li",
																{ className: "list-group-item", key: index },
																React.createElement(
																		"a",
																		{ href: file.url },
																		file.name
																)
														);
												})
										)
								)
						);
				}
		}]);

		return UploadFiles;
}(Component);

export default UploadFiles;