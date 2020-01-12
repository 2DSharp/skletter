import React from "react";
import ProfilePicture, {ProfilePictureVariant} from "../ProfilePicture";
import {ContentBlock, convertFromRaw, Editor, EditorState} from "draft-js";

export interface PostCard {
    data: any
}

function trimEmptyBlocks(contentBlock: ContentBlock) {
    const length = contentBlock.getLength();
    if (length === 0) {
        return 'empty-block'
    }
}
const PostCard: React.FunctionComponent<PostCard> = (props: PostCard) => {
    const editorState = EditorState.createWithContent(convertFromRaw(JSON.parse(props.data.content)));
    return (
        <div>
            <div className="post-card">
                <ProfilePicture username={props.data.username} variant={ProfilePictureVariant.SMALL}/>
                <div className="post-text">
                    <div className="subject">{props.data.title}</div>
                    <div className="byline">
                        <div className="profile-meta"><b>{props.data.composerName} </b> ({props.data.username})</div>
                    </div>
                    <div className="post-content">
                        <Editor blockStyleFn={trimEmptyBlocks} onChange={() => console.log()} editorState={editorState}
                                readOnly/>
                    </div>
                </div>
                <div className="post-action">
                    <span style={{fontSize: 14 + 'px'}} className="fas fa-ellipsis-v"/>
                </div>
            </div>
        </div>
    );
};

export default PostCard;
