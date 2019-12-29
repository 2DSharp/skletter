import React from "react";
import ProfilePicture from "../ProfilePicture";

export interface PostCard {
    data: any
}

const PostCard: React.FunctionComponent<PostCard> = (props: PostCard) => {
    return (
        <div>
            <div className="post-card">
                <ProfilePicture username={props.data.username} variant="small"/>
                <div className="post-text">
                    <div className="subject">{props.data.subject}</div>
                    <div className="byline">
                        <div className="profile-meta"><b>{props.data.name} </b> ({props.data.username})</div>
                    </div>
                    <div className="post-content">
                        <p>
                            {props.data.content}
                        </p>
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
