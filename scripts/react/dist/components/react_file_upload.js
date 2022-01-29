var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var e = React.createElement;
var Component = React.Component;

var v = new Date().getTime();
import UploadFiles from "./upload-files-component.js";

//class UploadFiles extends React.Component {

//}


var ReactFileUpload = function (_Component) {
  _inherits(ReactFileUpload, _Component);

  function ReactFileUpload(props) {
    _classCallCheck(this, ReactFileUpload);

    return _possibleConstructorReturn(this, (ReactFileUpload.__proto__ || Object.getPrototypeOf(ReactFileUpload)).call(this, props));
  }

  _createClass(ReactFileUpload, [{
    key: "render",
    value: function render() {

      return React.createElement(
        "div",
        null,
        React.createElement(
          "h5",
          null,
          "Upload a Gazetteer County Subdivision Geography Map"
        ),
        React.createElement(UploadFiles, null)
      );
    }
  }]);

  return ReactFileUpload;
}(Component);

var domContainer = document.querySelector('#react_file_upload');
ReactDOM.render(e(ReactFileUpload), domContainer);