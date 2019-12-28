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
        <div onClick={() => props.action(props.id)}
             className={"action" + (props.linkClass == null ? "" : " " + props.linkClass)}>
            <span className={props.iconClass}/>
            <span className="action-text">{props.name}</span>
        </div>
    );
};

export default ActionItem;
