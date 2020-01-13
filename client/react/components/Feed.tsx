import React from "react";
import PostCard from "./Post/PostCard";
import Axios, {AxiosResponse} from "axios";
import MessageCard from "./Post/MessageCard";

export interface FeedState {
    isLoaded: boolean,
    posts: any[]
}

class Feed extends React.Component<{}, FeedState> {
    componentDidMount() {

        Axios.get(process.env.API_URL + "/fetchTimeline")
            .then(function (response: AxiosResponse) {
                this.setState({
                    isLoaded: true,
                    posts: response.data
                });
            }.bind(this))
            .catch(function (error) {
                console.log(error);
            });
    }

    state: Readonly<FeedState> = {
        isLoaded: false,
        posts: []
    };

    render() {
        const {isLoaded, posts} = this.state;

        if (isLoaded) {
            if (posts.length == 0)
                return (<MessageCard icon={process.env.img_assets + "/party_popper.png"} title="You made it!">
                    <div>
                        Now that you are done with the hard bit, follow some people or topics to fill this space up. Or,
                        you can start writing now!
                    </div>
                </MessageCard>);
            return (
                <div>
                    {posts.map(post => (
                        <PostCard key={post.id} data={post}/>
                    ))}
                </div>
            );
        } else {
            let rows = [];
            for (let i = 0; i < 3; i++) {
                rows.push(
                    <div key={i} className="post-card">
                        <div className="skeleton-feed"/>
                    </div>
                );
            }
            return <div>{rows}</div>;
        }
    }
}

export default Feed;
