import React from 'react';

const Card = (props: { children: object }) => {
    return (
        <div>
            <div className="post-card">
                {props.children}
            </div>
        </div>
    );
};

export default Card;
