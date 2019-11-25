import PostCard from "./PostCard.js";

'use strict';

const e = React.createElement;

class Feed extends React.Component {
    state = {
        posts: [
            {
                id: 1,
                name: "Dedipyaman Das",
                username: "@twodee23",
                profile_picture: "http://localhost/static/img/test.jpg",
                subject: "Lorem ipsum dolor sit amet, consectetur adipiscing",
                content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore " +
                    "et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip " +
                    "ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat " +
                    "nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim" +
                    " id est laborum."
            },
            {
                id: 2,
                name: "John Doe",
                username: "@jdoe",
                profile_picture: "http://localhost/static/img/test.jpg",
                subject: "Sed ut perspiciatis unde omnis iste natus",
                content: "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium," +
                    " totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta " +
                    "sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia" +
                    " consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui" +
                    " dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora " +
                    "incidunt ut labore et dolore magnam aliquam quaerat voluptatem."
            }

        ]
    };

    render() {
        return (
            <div>
                {this.state.posts.map(post => (
                    <PostCard key={post.id} data={post}/>
                ))}
            </div>
        );
    }
}

export default Feed;
const domContainer = document.querySelector('#feed-root');
ReactDOM.render(e(Feed), domContainer);