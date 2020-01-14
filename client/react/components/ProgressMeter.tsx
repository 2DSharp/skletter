import React from 'react';

export interface ProgressMeterInterface {
    classes?: string
    progress: number
}

const ProgressMeter = (props: ProgressMeterInterface) => {
    return (
        <div>
            <div className={"upload-meter " + ((props.classes != null) ? props.classes : "")}>
                <span className={props.classes != null ? props.classes : ""} style={{width: props.progress + "%"}}/>
            </div>
        </div>
    );
};

export default ProgressMeter;
