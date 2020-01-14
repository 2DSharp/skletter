import React from "react";
import PostCard from "./Post/PostCard";
import Axios, {AxiosResponse} from "axios";
import MessageCard from "./Post/MessageCard";

export interface FeedState {
  isLoaded: boolean;
  posts: any[];
  lastPostId: string;
  hasUpdates: boolean;
  loadedPosts: any[];
}

class Feed extends React.Component<{}, FeedState> {
  componentDidMount() {
    Axios.get(process.env.API_URL + "/fetchTimeline")
        .then(
            function (response: AxiosResponse) {
              this.setState({
                isLoaded: true,
                lastPostId: response.data.length > 0 ? response.data[0].id : "",
                posts: response.data
              });
            }.bind(this)
        )
        .catch(function (error) {
          console.log(error);
        });
    setInterval(this.checkForNewPosts.bind(this), 3000);
  }

  loadNewPosts() {
    this.setState({
      posts: [...this.state.loadedPosts, ...this.state.posts],
      loadedPosts: [],
      hasUpdates: false
    });
  }

  async checkForNewPosts() {
    if (this.state.isLoaded) {
      Axios.get(
          process.env.API_URL +
          "/fetchTimelineUpdate?lastPostId=" +
          this.state.lastPostId
      )
          .then(
              function (response: AxiosResponse) {
                if (response.data.length > 0) {
                  this.setState({
                    isLoaded: true,
                    hasUpdates: true,
                    lastPostId: response.data[0].id,
                    loadedPosts: [...response.data, ...this.state.loadedPosts]
                  });
                }
              }.bind(this)
          )
          .catch(function (error) {
            console.log(error);
          });
    }
  }

  state: Readonly<FeedState> = {
    isLoaded: false,
    loadedPosts: [],
    posts: [],
    lastPostId: null,
    hasUpdates: false
  };

  displayAvailablePosts() {
    return (
        this.state.hasUpdates && (
            <div
                className="loaded-post-placeholder"
                onClick={this.loadNewPosts.bind(this)}
            >
                Load new posts ({this.state.loadedPosts.length <= 10 ? this.state.loadedPosts.length : "10+"})
            </div>
        )
    );
  }

  render() {
    const {isLoaded, posts} = this.state;

    if (isLoaded) {
      if (posts.length == 0)
        return (
            <div>
              {this.displayAvailablePosts()}
              <MessageCard
                  icon={process.env.img_assets + "/party_popper.png"}
                  title="You made it!"
              >
                <div>
                  Now that you are done with the hard bit, follow some people or
                  topics to fill this space up. Or, you can start writing now!
                </div>
              </MessageCard>
            </div>
        );
      return (
          <div>
            {this.displayAvailablePosts()}
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
