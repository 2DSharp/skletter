import React, {Component} from "react";
import Axios from "axios";
import Button from "./Button";
import ImageUploader from "./ImageUploader";

export interface AccountSetupWizardProps {
    step: number;
}

class AccountSetupWizard extends Component<AccountSetupWizardProps, {}> {
    constructor(props: AccountSetupWizardProps) {
        super(props);
    }

    state = {
        step: 1,
        stepData: {}
    };

    componentDidMount() {
        Axios.get(process.env.API_URL + "/setupAccount?step=1").then(response =>
            this.setState({stepData: response.data})
        );
    }

    uploadPicturePrompt() {
        return (
            <div style={{textAlign: "center"}}>
                <h1>Let's get you up to speed</h1>
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
                    <ImageUploader
                        placeholder="Uploading Profile Picture..."
                        endpoint={process.env.API_URL + "/uploadPicture"}
                    />
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
                <div style={{paddingRight: "20px"}} className="navigation">
                    <Button bindClass="std primary-btn small" type="action" action={null}>
                        Next <span className="fas fa-angle-double-right icon not-far"/>
                    </Button>
                </div>
            </div>
        );
    }
}

export default AccountSetupWizard;
