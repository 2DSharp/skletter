import React from 'react';
import Button from "./Button";

export interface DecisionButtonGroupProps {
    positiveAction(): void;

    negativeAction(): void;

    positiveText: string;
    negativeText: string;
}

const DecisionButtonGroup = (props: DecisionButtonGroupProps) => {
    return (
        <React.Fragment>
            <div style={{textAlign: "center"}}>
                <Button
                    bindClass="confirmation negative spaced"
                    type="action"
                    action={props.negativeAction}>
                    <span>{props.negativeText}</span>
                </Button>
                <Button
                    bindClass="confirmation positive spaced"
                    type="action"
                    action={props.positiveAction}
                >
                    <span>{props.positiveText}</span>
                </Button>
            </div>
        </React.Fragment>
    );
};

export default DecisionButtonGroup;
