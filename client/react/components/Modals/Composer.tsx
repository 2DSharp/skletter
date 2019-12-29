import React, {useEffect, useState} from "react";
import Dialog from "../Dialog";
import ProfilePicture, {ProfilePictureVariant} from "../ProfilePicture";
import Axios from "axios";
import {TextareaAutosize} from "react-autosize-textarea/lib/TextareaAutosize";
import PushButton from "../Controls/PushButton";

export interface ComposerProps {
  onClose: any;
}

const Composer = (props: ComposerProps) => {
  const [username, setUsername] = useState(null);
  const getUsername = () => {
    Axios.get(process.env.API_URL + "/getCurrentUserDetails")
        .then(response => {
          setUsername(response.data.username);
        })
        .catch(error => {
          console.log(error);
        });
  };
  useEffect(() => getUsername());

  return (
      <Dialog
          heading="Compose"
          onClose={props.onClose}
          closable
          overlayed={false}
      >
        <div className="padded-container small">
          <div className="composer" style={{display: "flex"}}>
            <ProfilePicture
                variant={ProfilePictureVariant.SMALL}
                username={username}
            />
            <div className="post-text">
              <input
                  autoFocus={true}
                  className="subject compose-header"
                  type="text"
                  placeholder="What's up?"
              />
              <TextareaAutosize draggable={"false"}
                                className="post-content body"
                                placeholder="Share your story"
                                maxRows={14}
                                rows={6}
              />
              <div className="actions">
                <div className="manipulators">
                  <i className="fas fa-bold manipulator"/>
                  <i className="fas fa-italic manipulator"/>
                  <i className="fas fa-image manipulator"/>

                </div>
                <PushButton><>Post</>
                </PushButton>
              </div>
            </div>
          </div>
        </div>
      </Dialog>
  );
};

export default Composer;
