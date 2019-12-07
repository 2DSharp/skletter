import React, {MouseEventHandler} from "react";

export interface ButtonProps {
    bindClass?: string;
    action: MouseEventHandler;
    children?: object;
    type: string;
}

interface MapLayout {
    [key: string]: React.DetailedHTMLProps<React.ButtonHTMLAttributes<HTMLButtonElement>,
        HTMLButtonElement>;
}

const Button: (
    props: ButtonProps
) => React.ClassAttributes<HTMLButtonElement> &
    React.ButtonHTMLAttributes<HTMLButtonElement> = (props: ButtonProps) => {
    let btnMap: MapLayout = {
        action: (
            <button className={props.bindClass} onClick={props.action}>
                {props.children}
            </button>
        ),
        close: (
            <button onClick={props.action} className="close-button">
                <svg
                    enableBackground="new 0 0 32 32"
                    version="1.1"
                    viewBox="0 0 32 32"
                    className="close-icon"
                    xmlSpace="preserve"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <g id="Cancel">
                        <path
                            clipRule="evenodd"
                            d="M22.729,21.271l-5.268-5.269l5.238-5.195   c0.395-0.391,0.395-1.024,0-1.414c-0.394-0.39-1.034-0.39-1.428,0l-5.231,5.188l-5.309-5.31c-0.394-0.396-1.034-0.396-1.428,0   c-0.394,0.395-0.394,1.037,0,1.432l5.301,5.302l-5.331,5.287c-0.394,0.391-0.394,1.024,0,1.414c0.394,0.391,1.034,0.391,1.429,0   l5.324-5.28l5.276,5.276c0.394,0.396,1.034,0.396,1.428,0C23.123,22.308,23.123,21.667,22.729,21.271z"
                            fillRule="evenodd"
                        />
                    </g>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                </svg>
            </button>
        )
  };
  return btnMap[props.type];
};

export default Button;
