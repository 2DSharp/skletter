import React, {createRef, FormEvent, useEffect, useState} from "react";
import Dialog from "../Dialog";
import ProfilePicture, {ProfilePictureVariant} from "../ProfilePicture";
import Axios from "axios";
import PushButton from "../Controls/PushButton";
import {convertToRaw, Editor, EditorState} from 'draft-js';
import 'draft-js/dist/Draft.css';
import RichTextManipulator, {ActionType} from "../Controls/RichTextManipulator";

export interface ComposerProps {
  onClose: any;
}

const Composer = (props: ComposerProps) => {
  const [username, setUsername] = useState(null);
  const [title, setTitle] = useState("");
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
    console.log(convertToRaw(editorState.getCurrentContent()));
  };

  const editor = createRef<Editor>();
  const focusEditor = () => editor.current.focus();

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
                <div onClick={focusEditor} className="post-content body rich-editor">
                  <Editor ref={editor} editorState={editorState} placeholder="Share your story"
                          onChange={setEditorState}/>
                </div>
                <div className="actions">
                  <div className="manipulators">
                    <RichTextManipulator type={ActionType.BOLD} onToggle={() => console.log("Bold")}/>
                    <RichTextManipulator type={ActionType.ITALICS} onToggle={() => console.log("Italics")}/>
                    <RichTextManipulator type={ActionType.PHOTO} onToggle={() => console.log("Photo")}/>

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
