
export default axios.create({
  baseURL: "http://bbf:8080/react",
  headers: {
    "Content-type": "application/json"
  }
});