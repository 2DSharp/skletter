import React, {useEffect, useState} from "react";
import ImageUploader from "../ImageUploader";
import Axios from "axios";

const UpdateDPStep = () => {
    const [picture, setPicture] = useState(
        "http://localhost/static/upload/default.png"
    );
    const fetchAndAddPicture = (username: string) => {
        Axios.get(
            process.env.API_URL +
            "/getProfilePicture?username=" +
            username +
            "&variant=big"
        )
            .then(response => {
                setPicture(response.data.url);
            })
            .catch(error => {
                console.log(error);
            });
    };
    const updatePicture = () => {
        Axios.get(process.env.API_URL + "/getCurrentUserDetails")
            .then(response => {
                fetchAndAddPicture(response.data.username);
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
            <div
                style={{
                    backgroundImage: "url(" + picture + ")",
                    display: "inline-block",
                    width: "128px",
                    height: "128px"
                }}
                className="profile-image"
            />
            <div>
                <div className="spacer medium"/>
                <ImageUploader
                    onUpdate={updatePicture}
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
