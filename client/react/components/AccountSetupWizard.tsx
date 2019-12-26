import React, {Component} from "react";
import Button from "./Button";
import ImageUploader from "./ImageUploader";
import Axios, {AxiosResponse} from "axios";

export interface AccountSetupWizardProps {
    step: number;
}

class AccountSetupWizard extends Component<AccountSetupWizardProps, {}> {
    constructor(props: AccountSetupWizardProps) {
        super(props);
        this.updatePicture = this.updatePicture.bind(this);
        this.fetchAndAddPicture = this.fetchAndAddPicture.bind(this);
    }

    state = {
        step: 1,
        pic: "http://localhost/static/upload/default.png"
    };

    componentDidMount() {
        this.updatePicture();
    }

    uploadPicturePrompt() {
        return (
            <div style={{textAlign: "center"}}>
                <h1>Let's get you up to speed</h1>
                <h3 className="dialog-subhead">Add a profile picture</h3>
                <div
                    style={{
                        backgroundImage: "url(" + this.state.pic + ")",
                        display: "inline-block",
                        width: "128px",
                        height: "128px"
                    }}
                    className="profile-image"
                />
                <div>
                    <div className="spacer medium"/>
                    <ImageUploader
                        onUpdate={this.updatePicture}
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

    updatePicture() {
        Axios.get(process.env.API_URL + "/getCurrentUserDetails")
            .then(
                function (response: AxiosResponse) {
                    const username = response.data.username;
                    this.fetchAndAddPicture(username);
                }.bind(this)
            )
            .catch(function (error) {
                console.log(error);
            });
    }

    fetchAndAddPicture(username: string) {
        Axios.get(process.env.API_URL + "/getProfilePicture?username=" + username)
            .then(
                function (response: AxiosResponse) {
                    this.setState({pic: response.data.url});
                }.bind(this)
            )
            .catch(function (error) {
                console.log(error);
            });
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
        const {step} = this.state;
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
