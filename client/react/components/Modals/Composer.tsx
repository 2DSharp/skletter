import React, {FormEvent, useEffect, useState} from "react";
import Dialog from "../Dialog";
import ProfilePicture, {ProfilePictureVariant} from "../ProfilePicture";
import Axios from "axios";
import PushButton from "../Controls/PushButton";
import {Editor, EditorState} from 'draft-js';
import 'draft-js/dist/Draft.css';

export interface ComposerProps {
  onClose: any;
}

const Composer = (props: ComposerProps) => {
  const [username, setUsername] = useState(null);
  const [title, setTitle] = useState("");
  const [contentChanged, setContentChanged] = useState(false);

  const placeholder = "Share your story";

  const [content, setContent] = useState("");
  const [editorState, setEditorState] = React.useState(
      EditorState.createEmpty(),
  );
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

  const handleSubmission = (event: FormEvent) => {
    event.preventDefault();
    console.log(title, content);
  };
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
              <form onSubmit={handleSubmission}>
                <input
                    autoFocus={true}
                    className="subject compose-header"
                    type="text"
                    name="title"
                    value={title}
                    placeholder="What's up?"
                    onChange={e => setTitle(e.target.value)}
                />
                <div className="post-content body rich-editor">
                  <Editor editorState={editorState} placeholder="Share your story" onChange={setEditorState}/>
                </div>
                <div className="actions">
                  <div className="manipulators">
                    <i className="fas fa-bold manipulator"/>
                    <i className="fas fa-italic manipulator"/>
                    <i className="far fa-image manipulator"/>
                  </div>
                  <PushButton>
                  <span style={{fontSize: 14}}>
                    <span style={{fontWeight: "normal"}}>Writing to: </span>
                    Public
                    <i
                        style={{fontSize: 10}}
                        className="fas fa-chevron-down spaced-right-icon far"
                    />
                  </span>
                  </PushButton>
                  <PushButton className="main">
                    <>Post</>
                  </PushButton>
                </div>
              </form>
            </div>
          </div>
        </div>
      </Dialog>
  );
};

export default Composer;
