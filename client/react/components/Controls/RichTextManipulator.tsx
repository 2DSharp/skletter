import React, {useState} from "react";
import classNames from "classnames";

export interface RichTextManipulatorProps {
    type: ActionType;
    className?: string;
    stayPressed: boolean;
    handleToggle: (event: React.MouseEvent<HTMLElement>) => void;
    selected?: boolean;
}

export enum ActionType {
    BOLD = "fas fa-bold",
    ITALICS = "fas fa-italic",
    PHOTO = "far fa-image"
}

const RichTextManipulator = React.forwardRef(
    (props: RichTextManipulatorProps, ref: React.Ref<HTMLSpanElement>) => {
        const [hover, setHover] = useState(false);

        const toggleBtn = (event: React.MouseEvent<HTMLElement>): void => {
            props.handleToggle(event);
        };
        const btnClass = classNames(
            "manipulator fa-stack-1x fa-inverse stacked-ico",
            props.type,
            props.className,
            {
                pressed: props.selected && props.stayPressed
            }
        );
        const stackClass = classNames(
            "fas fa-circle fa-stack-2x",
            props.className,
            {
                "stack-bg": !hover,
                "stack-bg-hover": hover
            }
        );

        return (
            <span
                ref={ref}
                onMouseDown={e => toggleBtn(e)}
                onMouseEnter={() => setHover(true)}
                onMouseLeave={() => setHover(false)}
                className="fa-stack"
            >
        <i className={stackClass} aria-hidden="true"/>
        <i className={btnClass}/>
      </span>
        );
    }
);

export default RichTextManipulator;
