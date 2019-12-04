import React, {Component} from "react";
import Axios from "axios";
import Button from "./Button";

class AccountSetupWizard extends Component {
    constructor(props) {
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

    uploadPicturePrompt(stepData) {
        return (
            <div style={{textAlign: "center"}}>
                <h3 className="dialog-subhead">{stepData.title}</h3>
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
                <div>{stepData.description}</div>
            </div>
        );
    }

    renderStep(step, stepData) {
        switch (step) {
            case 1:
                return this.uploadPicturePrompt(stepData);
            case 2:
                return null;
        }
    }

    render() {
        const {step, stepData} = this.state;
        //return null;
        return this.renderStep(step, stepData);
    }
}

export default AccountSetupWizard;
