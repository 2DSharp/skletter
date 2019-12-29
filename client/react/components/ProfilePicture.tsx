import React, {useEffect, useState} from 'react';
import Axios from "axios";

export interface ProfilePictureProps {
    variant: string;
    username: string;
    updated?: boolean;
}

const ProfilePicture = (props: ProfilePictureProps) => {
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

    useEffect(() => fetchAndAddPicture(props.username));

    return (
        <div
            style={{
                backgroundImage: "url(" + picture + ")",
            }}
            className={"profile-image " + props.variant}
        />
    );
};

export default ProfilePicture;
