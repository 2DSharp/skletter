import React, {useState} from "react";
import ActionItem from "./ActionItem";
import Dialog from "./Dialog";

const LeftMenu = () => {
  const [showDialog, setDialogShow] = useState(false);
  const actions = [
    {
      name: "Home",
      iconClass: "fas fa-home icon",
      linkClass: "active"
    },
    {
      name: "Profile",
      iconClass: "far fa-user icon"
    },
    {
      name: "Vault",
      iconClass: "fas fa-lock icon"
    },
    {
      name: "Favorites",
      iconClass: "far fa-bookmark icon"
    },
    {
      name: "Discover",
      iconClass: "fas fa-globe icon"
    },
    {
      name: "Drafts",
      iconClass: "far fa-sticky-note icon"
    },
    {
      name: "Compose",
      iconClass: "fas fa-feather icon",
      linkClass: "composer",
      action: () => {
        setDialogShow(true);
      }
    }
  ];
  const showAction = (i: number) => {
    actions[i].action();
  };
  let i = 0;
  return (
      <div className="left-menu">
        <ul className="action-menu">
          {actions.map(item => (
              <ActionItem
                  action={showAction}
                  id={i}
                  key={i++}
                  linkClass={item.linkClass}
                  iconClass={item.iconClass}
                  name={item.name}
              />
          ))}
        </ul>
        {showDialog && (
            <Dialog heading="Adjust the image" closable overlayed={false}>
              <div>
                <h1>Hello world</h1>
              </div>
            </Dialog>
        )}
      </div>
  );
};

export default LeftMenu;
