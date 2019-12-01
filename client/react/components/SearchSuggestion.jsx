import React from 'react';

const SearchSuggestion = (props) => {
    return (
        <li tabIndex={props.id} className="suggest-list">
            <div style={{fontWeight: "bold"}}>{props.data.data.name}</div>
            <div>@{props.data.data.username}</div>
        </li>);
};

export default SearchSuggestion;
