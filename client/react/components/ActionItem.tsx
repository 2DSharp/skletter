import React from 'react';

export interface ActionItemProps {
    linkClass: string,
    iconClass: string,
    name: string,
    action?: any,
    id: number
}

const ActionItem = (props: ActionItemProps) => {
    return (
        <li onClick={() => props.action(props.id)} className={"action " + props.linkClass}>
            <span className={props.iconClass}/>
            {props.name}
        </li>
    );
};

export default ActionItem;
