import React, {MouseEventHandler} from 'react';

export interface PushButtonProps {
    className?: string;
    action?: MouseEventHandler;
    children?: object;
}

const PushButton = (props: PushButtonProps) => {
    return (
        <button className={"push-btn " + props.className} onClick={props.action}>
            {props.children}
        </button>
    );
};

export default PushButton;
