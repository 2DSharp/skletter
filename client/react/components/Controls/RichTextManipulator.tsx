import React, {useState} from 'react';
import classNames from 'classnames';

export interface RichTextManipulatorProps {
    type: ActionType,
    className?: string,

    onToggle(event: React.MouseEvent<HTMLElement>): void;
}

export enum ActionType {
    BOLD = "fas fa-bold",
    ITALICS = "fas fa-italic",
    PHOTO = "far fa-image"
}

const RichTextManipulator = (props: RichTextManipulatorProps) => {

    const [selected, toggle] = useState(false);
    const [hover, setHover] = useState(false);

    const toggleBtn = (event: React.MouseEvent<HTMLElement>): void => {
        props.onToggle(event);
        toggle(!selected);
    };
    const btnClass = classNames('manipulator fa-stack-1x fa-inverse stacked-ico', props.type, props.className, {
        'pressed': selected,
    });
    const stackClass = classNames('fas fa-circle fa-stack-2x', props.className, {
        'stack-bg': !hover,
        'stack-bg-hover': hover,
    });

    return (
        <span onClick={e => toggleBtn(e)} onMouseEnter={() => setHover(true)} onMouseLeave={() => setHover(false)}
              className="fa-stack">
            <i className={stackClass} aria-hidden="true"/>
            <i className={btnClass}/>
        </span>

    );
};

export default RichTextManipulator;
