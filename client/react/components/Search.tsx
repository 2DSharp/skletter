import React, {ChangeEvent, Component} from "react";
import Axios from "axios";
import Suggestions from "./Suggestions";


export interface State {
    searched: boolean,
    key: string,
    results: any[]
}

class Search extends Component<{}, State> {

    state: Readonly<State> = {
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

    updateSearch(event: ChangeEvent<HTMLInputElement>) {
        Axios.get(process.env.API_URL + "/suggest?q=" + event.target.value)
            .then(response => {
                this.setState({searched: true, results: response.data});
            })
            .catch(error => console.log(error));
    }
}

export default Search;
