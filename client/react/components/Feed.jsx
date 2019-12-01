import React from "react";
import PostCard from "./PostCard";

class Feed extends React.Component {
    componentDidMount() {

        axios.get('https://gist.githubusercontent.com/2DSharp/86fe64a377af70d1d9ee09b82dbeceaa/raw/2685ec1a373ef285bde9d1d2e7abe3171a24f546/test.json')
            .then(function (response) {
                this.setState({
                    isLoaded: true,
                    posts: response.data.posts
                });
            }.bind(this))
            .catch(function (error) {
                console.log(error);
            })
            .finally(function () {
                // always executed
            });
    }

    state = {
        isLoaded: false,
        posts: []
    };

    render() {
        const {isLoaded, posts} = this.state;
        if (isLoaded) {
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
