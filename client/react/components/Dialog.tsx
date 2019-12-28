import React, {Component} from "react";
import Button from "./Controls/Button";
import ReactDOM from "react-dom";
import TransitionGroup from "react-transition-group/TransitionGroup";
import CSSTransition from "react-transition-group/CSSTransition";

export interface DialogProps {
  heading: string;
  closable: boolean;
  overlayed: boolean;
  onClose?: any
}

class Dialog extends Component<DialogProps, { dialogDisplayed: boolean }> {
  constructor(props: DialogProps) {
    super(props);
    this.closeDialog = this.closeDialog.bind(this);
    this.state = {dialogDisplayed: true};
  }

  render() {
    const closable = this.props.closable;
    return ReactDOM.createPortal(
        <div>
          {this.state.dialogDisplayed && (
              <div>
                {this.props.overlayed && (
                    <div onClick={this.closeDialog} className="overlay"/>
                )}
                <div className="simple-modal">
                  <TransitionGroup>
                    <CSSTransition classNames="dialog-transition" appear={true}
                                   timeout={{appear: 500, exit: 500, enter: 500}}>
                      <div className="dialog-container simple-modal__content">
                        <div className="header">
                          {closable && (
                              <Button action={this.closeDialog} type="close"/>
                          )}
                          <span>
                      <b style={{textAlign: "left", margin: "10px"}}>
                        {this.props.heading}
                      </b>
                    </span>
                        </div>
                        <div className="dialog-content modal-main">
                          {this.props.children}
                        </div>
                      </div>
                    </CSSTransition>
                  </TransitionGroup>
                </div>
              </div>
          )}
        </div>,
        document.getElementById("dialog-root")
    );
  }

  closeDialog() {
    this.setState({dialogDisplayed: false});
    this.props.onClose();
  }
}

export default Dialog;
