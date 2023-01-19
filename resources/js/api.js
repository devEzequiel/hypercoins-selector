import axios from "axios";

const api = axios.create({baseURL: 'http://localhost:8181/'});

export default api;