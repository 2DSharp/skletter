import React from 'react';

export interface SearchSuggestion {
    data: any,
    id: number
}

const SearchSuggestion: React.FunctionComponent<SearchSuggestion> = (props: SearchSuggestion) => {
    return (
        <li tabIndex={props.id} className="suggest-list">
            <div style={{fontWeight: "bold"}}>{props.data.name}</div>
            <div>@{props.data.username}</div>
        </li>);
};

export default SearchSuggestion;
