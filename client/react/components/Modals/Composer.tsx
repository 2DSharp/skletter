import React, {FormEvent, useEffect, useRef, useState} from "react";
import Dialog from "../Dialog";
import ProfilePicture, {ProfilePictureVariant} from "../ProfilePicture";
import Axios from "axios";
import PushButton from "../Controls/PushButton";
import {convertToRaw, Editor, EditorState, RichUtils} from "draft-js";
import "draft-js/dist/Draft.css";
import RichTextManipulator, {ActionType} from "../Controls/RichTextManipulator";

export interface ComposerProps {
  onClose: any;
}

function getSelectedCharacters(editorState: EditorState) {
  const selectionState = editorState.getSelection();
  const anchorKey = selectionState.getAnchorKey();
  const currentContent = editorState.getCurrentContent();
  const currentContentBlock = currentContent.getBlockForKey(anchorKey);
  const start = selectionState.getStartOffset();
  const end = selectionState.getEndOffset();
  return currentContentBlock.getCharacterList().slice(start, end);
}

function getStyle(editorState: EditorState, styleAttribute: string) {
  let isSelectionStyled = true;
  getSelectedCharacters(editorState).forEach(element => {
    isSelectionStyled =
        isSelectionStyled && element.getStyle().has(styleAttribute);
  });
  const inlineStyle = editorState.getCurrentInlineStyle();
  const isCharacterUnderCursorStyled = inlineStyle.has(styleAttribute);
  return isSelectionStyled && isCharacterUnderCursorStyled;
}

const Composer = (props: ComposerProps) => {
  const [username, setUsername] = useState(null);
  const [title, setTitle] = useState("");
  const [editorState, setEditorState] = React.useState(
      EditorState.createEmpty()
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
  const toggleBold = (event: React.MouseEvent) => {
    event.preventDefault();
    setEditorState(RichUtils.toggleInlineStyle(editorState, "BOLD"));
    setBoldBtnPressed(!boldBtnPressed);
  };
  const toggleItalic = (event: React.MouseEvent) => {
    event.preventDefault();
    setEditorState(RichUtils.toggleInlineStyle(editorState, "ITALIC"));
    setItalicBtnPressed(!italicBtnPressed);
  };

  const updateState = (editorState: EditorState) => {
    setEditorState(editorState);

    setBoldBtnPressed(getStyle(editorState, "BOLD"));
    setItalicBtnPressed(getStyle(editorState, "ITALIC"));
  };
  const [boldBtnPressed, setBoldBtnPressed] = useState(false);
  const [italicBtnPressed, setItalicBtnPressed] = useState(false);

  const editor = useRef<Editor>();
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
                <div
                    onClick={focusEditor}
                    className="post-content body rich-editor"
                >
                  <Editor
                      ref={editor}
                      editorState={editorState}
                      placeholder="Share your story"
                      onChange={updateState}
                  />
                </div>
                <div className="actions">
                  <div className="manipulators">
                    <RichTextManipulator
                        type={ActionType.BOLD}
                        handleToggle={toggleBold}
                        stayPressed
                        selected={boldBtnPressed}
                    />
                    <RichTextManipulator
                        type={ActionType.ITALICS}
                        handleToggle={toggleItalic}
                        stayPressed
                        selected={italicBtnPressed}
                    />
                    <RichTextManipulator
                        type={ActionType.PHOTO}
                        handleToggle={() => console.log("Photo")}
                        stayPressed={false}
                    />
                  </div>
                  <PushButton>
                  <span style={{fontSize: 14}}>
                    <span style={{fontWeight: "normal"}}>Writing to: </span>
                    <span className="bold">Public</span>
                    <i
                        style={{fontSize: 10}}
                        className="fas fa-chevron-down spaced-right-icon far"
                    />
                  </span>
                  </PushButton>
                  <PushButton className="main">
                    <span className="bold">Post</span>
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
