import React, {FormEvent, useEffect, useRef, useState} from "react";
import Dialog from "../Dialog";
import ProfilePicture, {ProfilePictureVariant} from "../ProfilePicture";
import Axios, {AxiosResponse} from "axios";
import PushButton from "../Controls/PushButton";
import {CharacterMetadata, convertToRaw, DraftEditorCommand, EditorState, RichUtils} from "draft-js";
import "draft-js/dist/Draft.css";
import createHashtagPlugin from 'draft-js-hashtag-plugin';
import 'draft-js-hashtag-plugin/lib/plugin.css';
import 'draft-js-mention-plugin/lib/plugin.css';

import RichTextManipulator, {ActionType} from "../Controls/RichTextManipulator";
import classNames from "classnames";
// To enable plugins
import Editor from 'draft-js-plugins-editor';

const hashtagPlugin = createHashtagPlugin();

export interface ComposerProps {
  onClose: any;
}

/**
 * Get character list under selection (not just cursor)
 * The function is pulled out of the component since it was performing better for some reason
 * The lambdas defined inside are *probably* re-created with every re-render or something
 * @param editorState
 */
function getSelectedCharacters(
    editorState: EditorState
): Immutable.Iterable<number, CharacterMetadata> {
  const selectionState = editorState.getSelection();
  const anchorKey = selectionState.getAnchorKey();
  const currentContent = editorState.getCurrentContent();
  const currentContentBlock = currentContent.getBlockForKey(anchorKey);
  const start = selectionState.getStartOffset();
  const end = selectionState.getEndOffset();
  return currentContentBlock.getCharacterList().slice(start, end);
}

/**
 * Get the current styles under a selection or under the cursor
 * **The function is pulled out of the component since it was performing better for some reason
 * The lambdas defined inside are *probably* re-created with every re-render or something**
 * @param editorState
 * @param styleAttribute
 * @return boolean
 */
function getStyle(editorState: EditorState, styleAttribute: string): boolean {
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
    const content = JSON.stringify(convertToRaw(editorState.getCurrentContent()));
    const formData = new FormData();
    formData.append("title", title);
    formData.append("content", content);
    Axios.post(process.env.API_URL + "/post", formData)
        .then(
            function (response: AxiosResponse) {
              console.log(response)
            }
        )
        .catch(function (response) {
          console.log(response);
        });
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

  const handleKeyCommand = (command: DraftEditorCommand) => {
    switch (String(command).toLowerCase()) {
      case "bold":
        setBoldBtnPressed(!boldBtnPressed);
        break;
      case "italic":
        setItalicBtnPressed(!italicBtnPressed);
        break;
      default:
        break;
    }
    const newState = RichUtils.handleKeyCommand(editorState, command);
    if (newState) {
      setEditorState(newState);
      return "handled";
    }
    return "not-handled";
  };

  const updateState = (editorState: EditorState) => {
    setEditorState(editorState);

    setBoldBtnPressed(getStyle(editorState, "BOLD"));
    setItalicBtnPressed(getStyle(editorState, "ITALIC"));
  };
  const [boldBtnPressed, setBoldBtnPressed] = useState(false);
  const [italicBtnPressed, setItalicBtnPressed] = useState(false);
  const [actionsDisabled, setActionsDisabled] = useState(true);

  const editor = useRef<Editor>();
  const focusEditor = () => editor.current.focus();

  const actionsClass = classNames("actions", {
    disabled: actionsDisabled && !editorState.getCurrentContent().hasText()
  });

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
                      handleKeyCommand={handleKeyCommand}
                      placeholder="Share your story"
                      onChange={updateState}
                      onFocus={() => setActionsDisabled(false)}
                      onBlur={() => setActionsDisabled(true)}
                      plugins={[hashtagPlugin]}
                  />
                </div>
                <div className={actionsClass}>
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
