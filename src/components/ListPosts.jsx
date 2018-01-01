import Create from './Create';
import Post from './Post';
import PropTypes from 'prop-types';
import React from 'react';
import {deletePost, listPosts} from '../containers/posts';

export default class ListPosts extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isLoaded: false,
      posts: []
    };

    this.delete = this.delete.bind(this);
  }

  componentDidMount() {
    listPosts().then(
      (result) => {
        this.setState({
          isLoaded: true,
          posts: result
        });
      },
      (error) => {
        this.setState({
          isLoaded: true,
          error
        });
      }
    );
  }

  delete(postId) {
    this.setState({
      isLoaded: false
    });

    deletePost(postId).then(
      (result) => {
        this.setState(prevState => ({
          isLoaded: true,
          posts: prevState.posts.filter(post => post.id !== postId)
        }));
      },
      (error) => {
        this.setState({
          isLoaded: true,
          error
        });
      }
    );
  }

  render() {
    const { error, isLoaded, posts } = this.state;

    if (error) {
      return <div>Error: {error.message}</div>;
    }

    if (!isLoaded) {
      return <div>Loading...</div>;
    }

    const renderedPosts = posts.map((post) => {
      return <Post key={post.id.toString()}
                   post={post}
                   handleDelete={this.delete} />;
    });

    return (
      <div className="container">
        <Create/>
        <div className="blog-posts">
          {renderedPosts}
        </div>
      </div>
    );
  }
}

ListPosts.propTypes = {
  posts: PropTypes.arrayOf(PropTypes.object)
};
