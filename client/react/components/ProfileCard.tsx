import * as React from "react";

const ProfileCard = () => {
    return (
        <div>
            <div className="profile-card-hero">
                <div className="cover-image">
                    <div className="profile-image feature"
                         style={{backgroundImage: process.env.USER_IMAGES + "/normal/{{ pic }}.jpg"}}/>
                </div>
                <div className="profile-data">
                    <div className="stats"><span className="stat-num">100</span> followers</div>
                    <div className="stats"><span className="stat-num">100</span> following</div>
                    <div className="stats"><span className="stat-num">100</span> posts</div>
                    <div className="blank"/>
                    <div className="profile-actions">
                        <button className="follow-btn">Follow</button>
                    </div>
                </div>
                <div className="user-details">
                    <div className="name">
                        Dedipyaman Das
                    </div>
                    <div className="username">@ded</div>
                    <div className="bio">
                        I write code.
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ProfileCard;
