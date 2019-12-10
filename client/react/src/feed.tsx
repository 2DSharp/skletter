import React from "react";
import ReactDOM from "react-dom";
import Feed from "../components/Feed";
import Search from "../components/Search";
import Dialog from "../components/Dialog";
import * as queryString from 'query-string';
import AccountSetupWizard from "../components/AccountSetupWizard";

ReactDOM.render(<Feed/>, document.getElementById("feed-root"));
ReactDOM.render(<Search/>, document.getElementById("search-root"));

const params = queryString.parse(window.location.search);

if (params.accountSetupWizard === "1")
  ReactDOM.render(
      <Dialog
          heading="Set up account"
          content={<AccountSetupWizard step={1}/>}
          closable
          overlayed={true}
      />,
      document.getElementById("dialog-root")
  );
