import React from 'react';

export interface ProgressMeterInterface {
    placeholder: string,
    progress: number
}

const ProgressMeter = (props: ProgressMeterInterface) => {
    return (
        <div>
          <span style={{margin: "5px", fontWeight: "bold"}}>
            {props.placeholder}
          </span>
            <div className="upload-meter">
                <span style={{width: props.progress + "%"}}/>
            </div>
        </div>
    );
};

export default ProgressMeter;
