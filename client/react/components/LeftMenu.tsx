import React, {useState} from "react";
import ActionItem from "./ActionItem";
import Composer from "./Modals/Composer";

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
      linkClass: "composer-btn",
      action: () => {
        setDialogShow(true);
      }
    }
  ];
  const showAction = (i: number) => {
    actions[i].action();
  };
  return (
      <>
        <div className="left-menu">
          <div className="action-menu">
            {actions.map((item, i) => (
                <ActionItem
                    action={showAction}
                    id={i}
                    key={i}
                    linkClass={item.linkClass}
                    iconClass={item.iconClass}
                    name={item.name}
                />
            ))}
          </div>
        </div>
        {showDialog && (
            <Composer onClose={() => setDialogShow(false)}/>
        )}
      </>
  );
};

export default LeftMenu;
