const PostCard = (props) => {
    return (
        <div>
            <div className="post-card">
                <div className="profile-image"
                     style={{backgroundImage: `url(${props.data.profile_picture})`}}/>
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
