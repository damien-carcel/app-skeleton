import Create from './Create';
import Post from './Post';
import PropTypes from 'prop-types';
import React from 'react';
import { createPost, deletePost, listPosts, updatePost } from '../containers/posts';

export default class ListPosts extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isLoaded: false,
      posts: []
    };

    this.delete = this.delete.bind(this);
    this.submit = this.submit.bind(this);
  }

  componentDidMount() {
    this.getAllPosts();
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
          error: error
        });
      }
    );
  }

  submit(postId, data) {
    this.setState({
      isLoaded: false
    });

    if (null !== postId) {
      updatePost(postId, data).then((result) => {
        this.setState(prevState => ({
          isLoaded: true,
          posts: prevState.posts.map((post) => {
            if (post.id === postId) {
              post.title = data.title;
              post.content = data.content;
            }

            return post;
          })
        }));
      }, (error) => {
        this.setState({
          isLoaded: true,
          error: error
        });
      });
    } else {
      createPost(data).then(result => this.getAllPosts());
    }
  }

  getAllPosts() {
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
          error: error
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
                   handleSubmit={this.submit}
                   handleDelete={this.delete} />;
    });

    return (
      <div className="container">
        <Create handleSubmit={this.submit}/>
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
