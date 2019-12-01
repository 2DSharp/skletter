import React from "react";
import ReactDOM from "react-dom";
import Feed from "../components/Feed";
import Search from "../components/Search";

ReactDOM.render(<Feed/>, document.getElementById('feed-root'));
ReactDOM.render(<Search/>, document.getElementById('search-root'));