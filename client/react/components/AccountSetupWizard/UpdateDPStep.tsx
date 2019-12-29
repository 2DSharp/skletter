import React, {useEffect, useState} from "react";
import ImageUploader from "../ImageUploader";
import Axios from "axios";
import ProfilePicture, {ProfilePictureVariant} from "../ProfilePicture";

const UpdateDPStep = () => {

    const [username, setUsername] = useState(null);
    const [count, setCount] = useState(0);
    const updatePicture = () => {
        Axios.get(process.env.API_URL + "/getCurrentUserDetails")
            .then(response => {
                setUsername(response.data.username);
            })
            .catch(error => {
                console.log(error);
            });
    };

    useEffect(() => updatePicture());
    return (
        <div style={{textAlign: "center"}}>
            <h1>Let's get you up to speed</h1>
            <h3 className="dialog-subhead">Add a profile picture</h3>
            <ProfilePicture key={count} variant={ProfilePictureVariant.BIG} username={username}/>
            <div>
                <div className="spacer medium"/>
                <ImageUploader
                    onUpdate={() => setCount(count + 1)}
                    placeholder="Uploading Profile Picture..."
                    endpoint={process.env.API_URL + "/uploadPicture"}
                />
            </div>
            <div className="spacer large"/>
            <div>
                A profile picture is associated with your identity making you unique.
            </div>
        </div>
    );
};

export default UpdateDPStep;
