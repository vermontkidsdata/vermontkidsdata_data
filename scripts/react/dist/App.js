import React from "react";

import UploadFiles from "./components/upload-files.component";

function App() {
  return React.createElement(
    "div",
    { className: "container", style: { width: "600px" } },
    React.createElement(
      "div",
      { style: { margin: "20px" } },
      React.createElement(
        "h3",
        null,
        "bezkoder.com"
      ),
      React.createElement(
        "h4",
        null,
        "React upload Files"
      )
    ),
    React.createElement(UploadFiles, null)
  );
}

export default App;