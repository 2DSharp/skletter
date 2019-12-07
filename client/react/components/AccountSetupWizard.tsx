import React, {Component} from "react";
import Axios from "axios";
import Button from "./Button";

export interface AccountSetupWizardProps {
    step: number
}

class AccountSetupWizard extends Component<AccountSetupWizardProps, {}> {
    constructor(props: AccountSetupWizardProps) {
        super(props);
        this.uploadPicture = this.uploadPicture.bind(this);
    }

    state = {
        step: 1,
        stepData: {}
    };

    componentDidMount() {
        //this.setState({ step: this.props.step });
        Axios.get(process.env.API_URL + "/setupAccount?step=1").then(response =>
            this.setState({stepData: response.data})
        );
    }

    uploadPicture() {
        alert("Hello");
    }

    uploadPicturePrompt() {
        return (
            <div style={{textAlign: "center"}}>
                <h3 className="dialog-subhead">Add a profile picture</h3>
                <div
                    style={{
                        backgroundImage: "url(http://localhost/static/img/test.jpg)",
                        display: "inline-block",
                        width: "128px",
                        height: "128px"
                    }}
                    className="profile-image"
                />
                <div>
                    <div className="spacer medium"/>
                    <div className="upload-btn-wrapper">
                        <Button
                            bindClass="std primary-btn medium"
                            type="action"
                            action={this.uploadPicture}
                        >
                            <input type="file"/>
                            <span className="fas fa-upload icon not-far"/>
                            Upload Image
                        </Button>
                        <input type="file"/>
                    </div>
                </div>
                <div className="spacer large"/>
                <div>
                    A profile picture is associated with your identity making you unique.
                </div>
            </div>
        );
    }

    renderStep(step: number) {
        switch (step) {
            case 1:
                return this.uploadPicturePrompt();
            case 2:
                return null;
        }
    }

    render() {
        const {step, stepData} = this.state;
        //return null;
        return (
            <div>
                {this.renderStep(step)}
                <div>
                    <Button
                        bindClass="std primary-btn small"
                        type="action"
                        action={this.uploadPicture}
                    >
                        Next <span className="fas fa-angle-double-right icon not-far"/>
                    </Button>
                </div>
            </div>
        );
    }
}

export default AccountSetupWizard;
