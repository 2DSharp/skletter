import * as React from "react";
import Card from "./Card";

const MessageCard = (props: { title: string, icon?: string, children?: object }) => {
    return (
        <Card>
            <div style={{textAlign: "center", width: "100%"}}>
                <img style={{width: 48}} src={props.icon} alt="Message"/>
                <div className="message-head">
                    {props.title}
                </div>
                <div className="message-body">{props.children}</div>
            </div>

        </Card>
    );
};

export default MessageCard;
