import React, {Component} from 'react';
import SearchSuggestion from "./SearchSuggestion";

export interface SuggestionProps {
    results: any[],
    searched: boolean
}

class Suggestions extends Component<SuggestionProps, {}> {
    constructor(props: SuggestionProps) {
        super(props);
    }

    render() {

        if (!this.props.searched || this.props.results.length < 1)
            return null;
        return (
            <div id="suggestions">
                <ul id="suggestions-ul">
                    {this.props.results.map((result, i) => (
                        <SearchSuggestion id={i} key={i} data={result}/>
                    ))}
                </ul>
            </div>
        );
    }
}

export default Suggestions;