import Create from './Create';
import Post from './Post';
import PropTypes from 'prop-types';
import React from 'react';
import listPosts from '../containers/posts/list';

export default class ListPosts extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      error: null,
      isLoaded: false,
      posts: []
    };
  }

  componentDidMount() {
    listPosts()
      .then(response => response.json())
      .then(
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
      )
  }

  render() {
    const { error, isLoaded, posts } = this.state;

    if (error) {
      return <div>Error: {error.message}</div>;
    }

    if (!isLoaded) {
      return <div>Loading...</div>;
    }

    const renderedPosts = posts.map((post) => <Post key={post.id.toString()} post={post} />);

    return (
      <div className="container">
        <Create />
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
