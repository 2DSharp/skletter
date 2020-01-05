import React from 'react';
import PushButton from "./PushButton";

export interface DecisionButtonGroupProps {
    positiveAction(): void;

    negativeAction(): void;

    positiveText: string;
    negativeText: string;
}

const DecisionButtonGroup = (props: DecisionButtonGroupProps) => {
    return (
        <React.Fragment>
            <div className="spaced" style={{textAlign: "center"}}>
                <PushButton action={props.negativeAction}>
                    <span className="bold spaced">{props.negativeText}</span>
                </PushButton>
                <PushButton className="main" action={props.positiveAction}>
                    <span className="bold spaced">{props.positiveText}</span>
                </PushButton>
            </div>
        </React.Fragment>
    );
};

export default DecisionButtonGroup;
