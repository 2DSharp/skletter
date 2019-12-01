import React, {Component} from 'react';
import SearchSuggestion from "./SearchSuggestion";

class Suggestions extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        let resId = 0;

        if (!this.props.searched || this.props.results.length < 1)
            return null;
        return (
            <div id="suggestions">
                <ul id="suggestions-ul">
                    {this.props.results.map(result => (
                        <SearchSuggestion id={resId} key={resId++} data={result}/>
                    ))}
                </ul>
            </div>
        );
    }
}

export default Suggestions;