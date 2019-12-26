import React, {Component} from "react";
import Button from "./Controls/Button";
import UpdateDPStep from "./AccountSetupWizard/UpdateDPStep";

export interface AccountSetupWizardProps {
    step: number;
}

class AccountSetupWizard extends Component<AccountSetupWizardProps, {}> {
    constructor(props: AccountSetupWizardProps) {
        super(props);
    }

    state = {
        step: 1,
    };

    componentDidMount() {
    }

    renderStep(step: number) {
        switch (step) {
            case 1:
                return <UpdateDPStep/>;
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
