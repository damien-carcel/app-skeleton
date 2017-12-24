import React from 'react';

import Create from './Create';
import Post from './Post';

export default class ListPosts extends React.Component {
  render() {
    const posts = this.props.posts;
    const renderedPosts = posts.map((post) => <Post key={post.id.toString()} post={post} />);

    return (
      <div className="blog-posts">
        <Create />
        {renderedPosts}
      </div>
    );
  }
}
