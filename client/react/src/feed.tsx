import React from "react";
import ReactDOM from "react-dom";
import Feed from "../components/Feed";
import Dialog from "../components/Dialog";
import * as queryString from "query-string";
import AccountSetupWizard from "../components/AccountSetupWizard";

ReactDOM.render(<Feed/>, document.getElementById("feed-root"));
const params = queryString.parse(window.location.search);

if (params.accountSetupWizard === "1")
  ReactDOM.render(
      <Dialog heading="Set up account" closable overlayed={true}>
          <AccountSetupWizard step={1}/>
      </Dialog>,
      document.getElementById("dialog-root")
  );
