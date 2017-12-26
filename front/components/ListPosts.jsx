import Create from './Create';
import Post from './Post';
import PropTypes from 'prop-types';
import React from 'react';

export default class ListPosts extends React.Component {
  render() {
    const posts = this.props.posts;
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
  posts: PropTypes.string
};
