import React, {Component} from "react";
import Axios from "axios";
import Suggestions from "./Suggestions";

class Search extends Component {

    state = {
        searched: false,
        key: '',
        results: []
    };

    render() {
        const {searched, results} = this.state;
        return (
            <div>
                <div className="search-block">
                    <i className="fas fa-search embed-icon"/>
                    <input
                        type="text"
                        onChange={this.updateSearch.bind(this)}
                        id="main-search"
                        className="search-box"
                        placeholder="Search Skletter"
                    />
                </div>
                <Suggestions searched={searched} results={results}/>
            </div>
        );
    }

    updateSearch(event) {
        Axios.get("/search?q=" + event.target.value)
            .then(response => {
                this.setState({searched: true, results: response.data});
            })
            .catch(error => console.log(error));
    }
}

export default Search;
