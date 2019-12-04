import React, {Component} from "react";
import Button from "./Button";

class Dialog extends Component {
  constructor(props) {
    super(props);
    this.closeDialog = this.closeDialog.bind(this);
    this.state = {dialogDisplayed: true};
  }

  render() {
    const closable = this.props.closable;
    return (
        <div>
          {this.state.dialogDisplayed && (
              <div>
                <div onClick={this.closeDialog} className="overlay"/>
                <div className="dialog-container centered" style={{width: "800px", height: "450px"}}>
                  <div className="header">
                    {closable && <Button action={this.closeDialog} type="close"/>}
                    <span><h1>{this.props.heading}</h1></span>
                  </div>
                  <div className="dialog-content">
                    {this.props.content}
                  </div>
                </div>
              </div>
          )}
        </div>
    );
  }

  closeDialog() {
    this.setState({dialogDisplayed: false});
  }
}

export default Dialog;
